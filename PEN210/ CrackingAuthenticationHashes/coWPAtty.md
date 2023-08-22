## coWPAtty

Pre compute hashes - rainbow tables

### Create pre-compute file

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
sudo cowpatty -r out.cap -d wifuhashes -s wifu
```

### Without pre-computing - 880 a second!
```text
sudo cowpatty -r out.cap -f /usr/share/john/password.lst -s wifu
```

### Without pre-computing - aircrack is faster - 2000 a second!
```text
sudo aircrack-ng -s wifu -w /usr/share/john/password.lst out.cap
sudo aircrack-ng -s wifu -w /usr/share/seclists/Passwords/xato-net-10-million-passwords.txt out.cap
```
