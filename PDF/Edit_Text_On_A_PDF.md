### PDF Manipulation - Editing PDF'S
### 17-11-21

Ive been asked a few times if its easy to edit a PDF, even just change a couple of letters or a number caused by a typo, the answer is it depends... 

if the PDF is mostly text anyway, you could uncompress it and edit it manualy in notepad!

This command will take the ORGINAL.pdf and create NEW.pdf

```text
qpdf --decrypt --stream-data=uncompress --compress-streams=n ORIGINAL.pdf NEW.pdf
```
should now be able to open the NEW.pdf in notepad and search for the text you wish to change.
