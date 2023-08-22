## coWPAtty

Pre compute hashes - rainbow tables


We run genpmk with -f to define our wordlist, -d to output to a file, and -s to specify the ESSID.
```text
genpmk -f /usr/share/john/password.lst -d wifuhashes -s wifu
```


```text
cowpatty -r wpajohn-01.cap -d wifuhashes -s wifu
```


