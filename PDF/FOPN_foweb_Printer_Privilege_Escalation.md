### 02-12-22
### FOPN foweb encrypted PDF - Printer Privilege Escalation

I was reviewing a pdf with FOPN_foweb encryption, and wanted to see if i could increase privilages to remove encryption and decompress the file completely.

This type of encryption requires a addon to most pdf viewers which connects to the internet to review how and what privilages you have to the pdf file.

I could quickly see that a man in the middle attack was the best way foward to increase privileges on the printer rights to print to pdf thereby removing any encryption.

monitoring the request response information in burp suite, i could the majority of parameters were being parroted back from request to response, the only fields of real interest was the "Code=" and the Perms=, as they were not in the request but was in the reponse, it also appeared to remain the same for the file over multiple requests/reponses.

```text
RetVal=Answer&Stamp=000000000&ServId=InstallComplete&DocuId=D-700&Ident3ID=number3&Ident4ID=number4&Code=mnopq&Perms=105
```

Plan of action - if i can rensend the code to open the file and change the perms field to increase privilages i can the print ...



```python
qpdf --decrypt --stream-data=uncompress --compress-streams=n ORIGINAL.pdf NEW.pdf
```
