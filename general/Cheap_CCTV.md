#### 25-07-22
#### Cheap CCTV is not a bargin

I was running a nessus scan and came upon a CCTV system, it was alowing path traversal. The CCTV system was running the latest version of the firmware!

Even worse is the root password was DES encrypted

![cheap_cctv_exploit](https://wanatry.github.io/images/1_cheap_cctv.png)

path traversal, passwd file viewable, weak encryption and a root password that seems locked, great ...


```text
1500 	descrypt, DES (Unix), Traditional DES 
hashcat -m 1500 -a 3 absxcfbgXtb3o
```

Cracked in 10 seconds, even with a simple brute force command.

absxcfbgXtb3o:xc3511

This CCTV system has been removed and changed for a more secure unit when this was proved.