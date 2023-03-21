### 17-11-21
### PDF Manipulation - Removing a watermark

first run the following command on the pdf to uncompress the file.

qpdf --stream-data=uncompress input.pdf output.pdf

then open the output.pdf in notepad and search for something like "Watermark" (if they have made it easy)

change this

<< /Name (Watermark) /Type /OCG /Usage << /Export << /ExportState /ON >> /PageElement << /Subtype /FG >> /Print << /PrintState /ON >> /View << /ViewState /ON >> >> >>

to this

<< /Name (Watermark) /Type /OCG /Usage << /Export << /ExportState /OFF >> /PageElement << /Subtype /FG >> /Print << /PrintState /OFF >> /View << /ViewState /OFF >> >> >>
