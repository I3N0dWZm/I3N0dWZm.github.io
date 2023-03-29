### 28-03-23
### Scanning files and sub folders for a keyword

When looking for a callback in a program, i would go to the installation folder, but it has lots of dll's, exe, ini etc. below is how i would pinpoint the file(s) i want to look at.

This small script will read any file/s in a given directory and look for the keyword provided in the lookup variable, 

The difference is that this will look for the keyword with 00 (null) betwen each letter as well, which alot of dlls seem to encode strings with.

example : domain/ = d o m a i n /

#### folder_scanner.py
```python
import glob
import re
import os

def_path 			= "C:/folderpath/"	#change this
lookup				= "domain.com/"		#change this
##############################################################
lookup 				= lookup.encode('utf-8')
hex_lookup 			= lookup.hex()
hex_buf_lookup_tmp 		= re.findall('..',hex_lookup)
hex_buf_lookup 			= '00'.join(hex_buf_lookup_tmp)
##############################################################
print("Looking for: " + str(lookup))
print("Looking for hex version: " + str(hex_lookup))
print("Looking for hex version with null space (00): " + str(hex_buf_lookup))
print("----------------------------------------------")
##############################################################
for item in glob.glob(def_path + '/**/*.*', recursive=True):
	ok = 0
	if os.path.isfile(item):
		try:
			with open(item, 'rb') as f:
				hexdata = f.read().hex()
				ok = 1
		except:
			print(item,"cant read file")

		if ok == 1:
			#if str(lookup) in str(hexdata):
			#	print (item, lookup)
			if str(hex_lookup) in str(hexdata):
				print (item, hex_lookup)
			if str(hex_buf_lookup) in str(hexdata):
				print (item, hex_buf_lookup)
```
