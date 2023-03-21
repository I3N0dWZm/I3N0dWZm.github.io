### 01-03-23
### Hack The Box - M0rsarchive Challenge


Recently completed the M0rsarchive Challenge on HTB, this is a zip file with an image, the image uses morse code to display the password, each time you enter the password on the zip file and extract you are presented with another zip and image file and another and another. 

I found i cracked it in a different way to others, i created a morse code image libary, then chopped up each image to compare and output the password

this involved two scripts and some manual updating of the libary (in the end!)

the first script was a simple brute force script to attempt every combination and once succesful extract and zip and chop the images name them with the relivant number or character and add to the libary.I could see the password was increasing in complexity each time so this would have limited success but a good starting point.

### brute_forcer.py
```python
from zipfile import ZipFile
import cv2
import time
from datetime import datetime
#################################################
#brute forcer was made to crack alpha numeric password on zip file 6 in length and smaller 
#################################################
def_path 	= "C:/HTB/Challenges/M0rsarchive/"
#set 		= ['','a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','0','1','2','3','4','5','6','7','8','9']
#set 		= ['','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','0','1','2','3','4','5','6','7','8','9']
set 		= ['','0','1','2','3','4','5','6','7','8','9']

def cropper(img,password):
	bits 	= [l for l in password]
	h, w 	= img.shape
	y 		= 0 
	x 		= 0
	for b in bits:
		try:
			crop_img = img[y+1:y+2, x:x+w]	
			print ("writing : ",str(b))
			cv2.imwrite(def_path + "lookup/test/"+str(b) + "_0.jpg", crop_img)				
			y = y + 2
		except:
			print("error writing to lookup")

def read_image():
	blackAndWhiteImage = ""
	try:
		originalImage = cv2.imread(def_path + 'flags/flag/pwd.png')
		grayImage = cv2.cvtColor(originalImage, cv2.COLOR_BGR2GRAY)
		(thresh, blackAndWhiteImage) = cv2.threshold(grayImage, 128, 255, cv2.THRESH_OTSU | cv2.THRESH_BINARY) 
	except:
		print ("cant read the image!")
	return blackAndWhiteImage

def brute_cracker(zip_file):
	z	= 1
	x 	= "" #final code
	for a in set:
		for b in set:
			for c in set:
				for d in set:
					for e in set:
						for f in set:
							for g in set:
								p=a+b+c+d+e+f+g
								try:
									with ZipFile(zip_file) as zf:
										zf.extractall(pwd=bytes(p,'utf-8'),path=def_path+"flags")
									print(p)
									x = p
									z = 0
									break
								except:
									pass
							if z == 0:
								break
						if z == 0:
							break
					if z == 0:
						break
				if z == 0:
					break
			if z == 0:
				break
		if z == 0:
			break							
	return x
                
def mainy():
	zip_no		= 999	
	while zip_no > 993:
		start = datetime.now()
		print ('Brute forcing : flag_'+str(zip_no)+'.zip')
		zip_file 	= def_path + 'flags/flag/flag_'+str(zip_no)+'.zip'
		im = read_image()
		password = brute_cracker(zip_file)
		cropper(im, password)
		zip_no = zip_no -1
		end = datetime.now()
		et = (end - start).total_seconds()
		print ("seconds taken : " + str(et))
		print('-------------------------------------------')

mainy()
```


