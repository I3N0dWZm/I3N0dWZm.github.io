### Putty - Add print to pdf functionality for Windows
### 02-01-24

I was asked if its possible to add print from screen to pdf functionalty within puttty, this was how i added the option.

Download the putty source from earth.il - from https://the.earth.li/~sgtatham/putty/0.79/putty-src.zip

Extract the putty-src.zip file

Download libharu-2.4.4 - http://libharu.org/

Follow the instructions to build libharu and copy to the root of the putty source folder. 

libharu-2.4.4/include/*.c to root dir of putty source.

Libharu-2.4.4/src/debug/*.* to root dir of putty source and /debug/ folder.

Create the path with security access "C:\Program Files (x86)\PuTTY\print\" to store the pdf and debug information

### Now to add the the print funtions to the putty code! 

### windows\window.c

Define IDM_PRINT to window.c (around line number 54)
```text
#define IDM_PRINT   0x0210
```
Add the menu item (around line number 790)
```text
AppendMenu(m, MF_ENABLED, IDM_PRINT, "Print to PDF");
```
set IDM_PRINT to call function term_copyall_and_print (around line number 2543)
```text
case IDM_PRINT:
  term_copyall_and_print(term, clips_system, lenof(clips_system));
  break;
```

### windows\putty.vcxproj

Append Dependencies to the AdditionalDependencies line

```text
<AdditionalDependencies>..\Debug\hpdf.lib;
```

### terminal\terminal.c

Add libharu and locale includes (around line number 13)
```text
#include <hpdf.h>
#include "hpdf.h"
#include <locale.h>
```
Create a function called clipme_return - which is very similar to clipme but returns the data instead of adding it to the clipboard (around line number 6740)
```text
wchar_t* clipme_return(Terminal *term, pos top, pos bottom, bool rect, bool desel, const int *clipboards, int n_clipboards)
{
    clip_workbuf buf;
    int old_top_x;
    int attr;
    truecolour tc;

    buf.bufsize = 5120;
    buf.bufpos = 0;
    buf.textptr = buf.textbuf = snewn(buf.bufsize, wchar_t);
    buf.attrptr = buf.attrbuf = snewn(buf.bufsize, int);
    buf.tcptr = buf.tcbuf = snewn(buf.bufsize, truecolour);

    old_top_x = top.x;                 /* needed for rect==1 */

    while (poslt(top, bottom)) {
        bool nl = false;
        termline *ldata = lineptr(top.y);
        pos nlpos;

        /*
         * nlpos will point at the maximum position on this line we
         * should copy up to. So we start it at the end of the
         * line...
         */
        nlpos.y = top.y;
        nlpos.x = term->cols;

        /*
         * ... move it backwards if there's unused space at the end
         * of the line (and also set `nl' if this is the case,
         * because in normal selection mode this means we need a
         * newline at the end)...
         */
        if (!(ldata->lattr & LATTR_WRAPPED)) {
            while (nlpos.x &&
                   IS_SPACE_CHR(ldata->chars[nlpos.x - 1].chr) &&
                   !ldata->chars[nlpos.x - 1].cc_next &&
                   poslt(top, nlpos))
                decpos(nlpos);
            if (poslt(nlpos, bottom))
                nl = true;
        } else {
            if (ldata->trusted) {
                /* A wrapped line with a trust sigil on it terminates
                 * a few characters earlier. */
                nlpos.x = (nlpos.x < TRUST_SIGIL_WIDTH ? 0 :
                           nlpos.x - TRUST_SIGIL_WIDTH);
            }
            if (ldata->lattr & LATTR_WRAPPED2) {
                /* Ignore the last char on the line in a WRAPPED2 line. */
                decpos(nlpos);
            }
        }

        /*
         * ... and then clip it to the terminal x coordinate if
         * we're doing rectangular selection. (In this case we
         * still did the above, so that copying e.g. the right-hand
         * column from a table doesn't fill with spaces on the
         * right.)
         */
        if (rect) {
            if (nlpos.x > bottom.x)
                nlpos.x = bottom.x;
            nl = (top.y < bottom.y);
        }

        while (poslt(top, bottom) && poslt(top, nlpos)) {
#if 0
            char cbuf[16], *p;
            sprintf(cbuf, "<U+%04x>", (ldata[top.x] & 0xFFFF));
#else
            wchar_t cbuf[16], *p;
            int c;
            int x = top.x;

            if (ldata->chars[x].chr == UCSWIDE) {
                top.x++;
                continue;
            }

            while (1) {
                int uc = ldata->chars[x].chr;
                attr = ldata->chars[x].attr;
                tc = ldata->chars[x].truecolour;

                switch (uc & CSET_MASK) {
                  case CSET_LINEDRW:
                    if (!term->rawcnp) {
                        uc = term->ucsdata->unitab_xterm[uc & 0xFF];
                        break;
                    }
                  case CSET_ASCII:
                    uc = term->ucsdata->unitab_line[uc & 0xFF];
                    break;
                  case CSET_SCOACS:
                    uc = term->ucsdata->unitab_scoacs[uc&0xFF];
                    break;
                }
                switch (uc & CSET_MASK) {
                  case CSET_ACP:
                    uc = term->ucsdata->unitab_font[uc & 0xFF];
                    break;
                  case CSET_OEMCP:
                    uc = term->ucsdata->unitab_oemcp[uc & 0xFF];
                    break;
                }

                c = (uc & ~CSET_MASK);
#ifdef PLATFORM_IS_UTF16
                if (uc > 0x10000 && uc < 0x110000) {
                    cbuf[0] = 0xD800 | ((uc - 0x10000) >> 10);
                    cbuf[1] = 0xDC00 | ((uc - 0x10000) & 0x3FF);
                    cbuf[2] = 0;
                } else
#endif
                {
                    cbuf[0] = uc;
                    cbuf[1] = 0;
                }

                if (DIRECT_FONT(uc)) {
                    if (c >= ' ' && c != 0x7F) {
                        char buf[4];
                        WCHAR wbuf[4];
                        int rv;
                        if (is_dbcs_leadbyte(term->ucsdata->font_codepage, (BYTE) c)) {
                            buf[0] = c;
                            buf[1] = (char) (0xFF & ldata->chars[top.x + 1].chr);
                            rv = mb_to_wc(term->ucsdata->font_codepage, 0, buf, 2, wbuf, 4);
                            top.x++;
                        } else {
                            buf[0] = c;
                            rv = mb_to_wc(term->ucsdata->font_codepage, 0, buf, 1, wbuf, 4);
                        }

                        if (rv > 0) {
                            memcpy(cbuf, wbuf, rv * sizeof(wchar_t));
                            cbuf[rv] = 0;
                        }
                    }
                }
#endif

                for (p = cbuf; *p; p++)
                    clip_addchar(&buf, *p, attr, tc);

                if (ldata->chars[x].cc_next)
                    x += ldata->chars[x].cc_next;
                else
                    break;
            }
            top.x++;
        }
        if (nl) {
            int i;
            for (i = 0; i < sel_nl_sz; i++)
                clip_addchar(&buf, sel_nl[i], 0, term->basic_erase_char.truecolour);
        }
        top.y++;
        top.x = rect ? old_top_x : 0;

        unlineptr(ldata);
    }
#if SELECTION_NUL_TERMINATED
    clip_addchar(&buf, 0, 0, term->basic_erase_char.truecolour);
#endif
	return buf.textbuf;
}
```

Create the term_copyall_and_print function which grabs the info and converts to PDF storing in the dir "C:\Program Files (x86)\PuTTY\print\" (around line number 6925)
```text
void term_copyall_and_print(Terminal *term, const int *clipboards, int n_clipboards)
{
    pos top;
    pos bottom;
    tree234 *screen = term->screen;
    top.y = -sblines(term);
    top.x = 0;
    bottom.y = find_last_nonempty_line(term, screen);
    bottom.x = term->cols;
    wchar_t* result = clipme_return(term, top, bottom, false, true, clipboards, n_clipboards);
	/*write result to file*/
	const wchar_t* filename1 = L"C:\\Program Files (x86)\\PuTTY\\print\\putty-clipboard.txt";
	FILE* file1 = _wfopen(filename1, L"w");
	if (file1 == NULL) {
		fwprintf(stderr, L"Error: Unable to open the file.\n");
	} else {
		fwprintf(file1, L"%ls", result);
		fclose(file1);
	}
	/*convert wchar_t to char*/
	const wchar_t * p;
	mbstate_t mbs;
	char buffer[5120];
	int ret;
	setlocale(LC_ALL, "");
	mbrlen (NULL,0,&mbs);    /* initialize mbs */
	p = result;
	ret = wcsrtombs ( buffer, &p, sizeof(buffer), &mbs );
	if (ret==5120) buffer[5119]='\0';
	if (ret) printf ("multibyte string: %s \n",buffer);
	char *token;
	token = strtok(buffer, "\r");
	FILE *filePointer = fopen("C:\\Program Files (x86)\\PuTTY\\print\\debug.txt", "w");
	fprintf(filePointer, "debugging...\n");
	const char *pdffile = "C:\\Program Files (x86)\\PuTTY\\print\\print.pdf";
	fprintf(filePointer, "filename set...\n");
	setlocale(LC_ALL, ".437");
	FILE *file = stdout;  // Use stdout for error messages
    HPDF_Doc pdf = HPDF_New(NULL, NULL);
    if (pdf == NULL) {
        fprintf(filePointer, "Error: cannot create PDF document\n");
        return;
    }
	fprintf(filePointer, "HPDF_New...\n");	
	HPDF_Page page = HPDF_AddPage(pdf);
	HPDF_Page_SetSize(page, HPDF_PAGE_SIZE_A4, HPDF_PAGE_PORTRAIT);
	HPDF_Font font = HPDF_GetFont(pdf, "Courier", NULL);
	HPDF_Page_SetFontAndSize(page, font, 12);
	HPDF_Page_BeginText(page);
	HPDF_Page_MoveTextPos(page, 10, 780); //start position
	HPDF_Page_ShowText(page, "");
	while (token != NULL) {	
		HPDF_Page_MoveTextPos (page, 0, -15);
		HPDF_Page_ShowText(page, token);
        printf("Token: %s\n", token);
        token = strtok(NULL, "\r");
    }
	HPDF_Page_EndText(page);
	fprintf(filePointer, "HPDF settings page details...\n");
	if (HPDF_SaveToFile(pdf, pdffile) != HPDF_OK) {
		fprintf(filePointer, "Error: cannot write PDF file\n");
		HPDF_Free(pdf);
		return;
	}
	fclose(filePointer);
	/* open file */
	const char* commandFormat = "start \"\" \"%s\"";
    char command[512];  // Adjust the size based on your needs
    snprintf(command, sizeof(command), commandFormat, pdffile);
    int com = system(command);
}
```

### putty.h

Add export function detail to putty.h (around line number 2208)
```text
void term_copyall_and_print(Terminal *, const int *, int);
```

### Compile the exe

Compile the programs in the cmd root of the putty source directory.
```text
cmake .
cmake --build .
```

### Notes

Open the new puty.exe from the debug directory, copy the hpdf.dll into the debug folder, Print to PDF options should now be available.

You may also require the files ucrtbased.dll and vcruntime140d.dll if they are not already installed in windows.

If a visual c++ error occurs like "stream !=nullptr" when clicking "Print to PDF" its probably because the program doesnt have enough rights to write to the path - "C:\Program Files (x86)\PuTTY\print\"

link for those modified files is stored here - https://github.com/I3N0dWZm/I3N0dWZm.github.io/tree/ca0627fd5686936eea1e82cdfbb4086a7783cc9c/Putty_Print










