### Headers
Recovering Files from a USB stick thats files with random corrupt files names

A user came to me with a corrupted usb stick, most of the original files seemed to be intact but a lot of random large files had been generated, 
After spinning up a copy of kali linux I was able to view the data in an enclosed environment (and to make sure it wasn’t windows malware!)

these files made it difficult to do anything with the USB stick as they had absorbed all the space.

Extract from terminal view of listing the usb stick.

![alt text](https://wanatry.github.io/images/1_usb_stick.jpg)

![alt text](https://wanatry.github.io/images/2_usb_stick.jpg)

As you can see these files were odd, and some were 1gb or more.

I set about writing a small script to recover the usable data and copying it to a backup folder on my documents folder in linux.

Original files conformed to a standard alpha numeric these corrupted files did not.

The code below checks each files extension if the extension conforms to the “alpha” list it will attempt to copy, if not exclude it, simple!

Usbstick_recovery.py

```
import glob
import shutil
import os
alpha = "abcdefghijklmnopqrstuvwxyz0123456789 -',"
for f in glob.glob('/media/kali/0312-74C3/**/*.*', recursive=True):
    halt = 0
    try:
        ext = f.split(".")[-1].lower()
        for i in ext:
            if i not in alpha:
                print("odd : ",ext,f)
                halt = 1
                break
            
        if halt == 0:    
            print(f)
            dest = f.replace("/media/kali/0312-74C3","/home/kali/Documents/Drive")
            print(dest)
            os.makedirs(os.path.dirname(dest), exist_ok=True)
            shutil.copy(f,dest)
    except:
        print("cant read filename")


```

Shortly after recovering the files the usb stick completely failed.

![alt text](https://wanatry.github.io/images/3_usb_stick.jpg)

