### 29-03-23
### PDF Manipulation - Image Extraction

Image extraction script using mupdf/fitz. as pypdf no longer seems to work (for me anyway).

This script will output each image found in the given pdf as a png file with a simple incremented number.

```python
import fitz

workdir 	= "C:/folderpath/"  #change this
filename 	= "temp.pdf"        #change this
count 		= 1

pdf = fitz.Document(workdir+filename)
print(range(len(pdf)))
for i in range(len(pdf)):							      #loop over pages
	for img in pdf.get_page_images(i):				#loop over images
		print(img)
		xref 	= img[0]							            #get ref
		image 	= pdf.extract_image(xref)			  #extract image
		pix 	= fitz.Pixmap(pdf, xref)			    #create pixel maps		
		pix.save(workdir + str(count) + ".png")	#save pixel maps
		print (workdir + str(count) + ".png")
		count = count + 1
```
