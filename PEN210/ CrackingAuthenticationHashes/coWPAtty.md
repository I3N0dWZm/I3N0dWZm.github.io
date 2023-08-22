## coWPAtty

Pre compute hashes - rainbow tables

### create pre-compute file

```text
genpmk -f /usr/share/john/password.lst -d wifuhashes -s wifu

-f 	Dictionary file
-d 	Output hash file
-s 	Network SSID
-h 	Print this help information and exit
-v 	Print verbose information (more -v for more verbosity)
-V 	Print program version and exit
```

### Lets get cracking - 55,000 a second!
```text
cowpatty -r wpajohn-01.cap -d wifuhashes -s wifu


```


