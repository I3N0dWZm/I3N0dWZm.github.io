### PDF Manipulation - Clean PDF before processing
### 04-10-21

When i was dealing with inconsistant results from processing incoming pdfs from different suppliers, this is the most simple method i could find to standardize PDFs before processing.

```text
gswin64c.exe -sDEVICE=pdfwrite -dCompatibilityLevel=1.5 -dPDFSETTINGS=/printer -dNOPAUSE -dBATCH -dQUIET -sOutputFile=out.pdf in.pdf
```
