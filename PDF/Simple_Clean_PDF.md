

When i was suffering with inconsistant result for processing incoming pdfs from different suppliers, this is the most simple method i could find to standardize PDFs before processing.

gswin64c.exe -sDEVICE=pdfwrite -dCompatibilityLevel=1.5 -dPDFSETTINGS=/printer -dNOPAUSE -dBATCH -dQUIET -sOutputFile=out.pdf in.pdf
