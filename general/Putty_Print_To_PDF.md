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

