### Cheap CCTV is not a bargin
### 25-07-22

I was running a nessus scan and came upon a CCTV system, it was alowing path traversal. The CCTV system was running the latest version of the firmware!

Even worse is the root password was DES encrypted

![cheap_cctv_exploit](https://i3n0dwzm.github.io/images/1_cheap_cctv.png)

http not https, path traversal, passwd file viewable, weak encryption, but is the root password easy to guess? 


```text
1500 	descrypt, DES (Unix), Traditional DES 
hashcat -m 1500 -a 3 absxcfbgXtb3o
```

Cracked in 10 seconds, even with a simple brute force command.

absxcfbgXtb3o:xc3511

This CCTV system has been removed and changed for a more secure unit when this was proved.
