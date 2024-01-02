### Putty - add print to pdf functionality
### 02-01-24

I was asked if its possible to add print from screen to pdf functionalty within puttty, this was how i added the option.

Download the putty source from earth.il - from https://the.earth.li/~sgtatham/putty/0.79/
putty-src.zip

Extract the putty-src.zip file

Download libharu-2.4.4 - http://libharu.org/

Follow the instructions to build libharu and copy to the root of the putty source folder.

Now to add the the print funtions to the putty code! 

#### windows\window.c

Define IDM_PRINT to window.c (around line number 54)
```text
#define IDM_PRINT   0x0210
```
Add the menu item (around line number 790)
```text
AppendMenu(m, MF_ENABLED, IDM_PRINT, "Print To PDF");
```
set IDM_PRINT to call function term_copyall_and_print
```text
case IDM_PRINT:
  term_copyall_and_print(term, clips_system, lenof(clips_system));
  break;
```

#### terminal\terminal.c

Add libharu and locale includes (around line number 13)
```text
#include <hpdf.h>
#include "hpdf.h"
#include <locale.h>
```

Create a function called clipme_return - which is very similar to clipme but returns the data instead of adding it to the clipboard (around line number 6758)
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











