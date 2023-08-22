## coWPAtty

Pre compute hashes - rainbow tables


We run genpmk with -f to define our wordlist, -d to output to a file, and -s to specify the ESSID.
```text
genpmk -f /usr/share/john/password.lst -d wifuhashes -s wifu

-f 	Dictionary file
-d 	Output hash file
-s 	Network SSID
-h 	Print this help information and exit
-v 	Print verbose information (more -v for more verbosity)
-V 	Print program version and exit
```


```text
cowpatty -r wpajohn-01.cap -d wifuhashes -s wifu


```


