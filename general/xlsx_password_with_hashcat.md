### 03-04-23
### XLSX password crack with Hashcat

I had to crack a password on a spreadsheet(xlsx file), previoculy when i needed to do this i was abl to open the file in libre office, but this bypass no longer worked.

I saw john the ripper offered some python code to output the hash with "office2john.py", running it was a bit more of a challenge, it only appeared to work with python2.7.

So i installed a copy of python 2.7 to a seperate folder and run the following

#### Get Hash
```text
C:\Python27\python office2john.py file.xlsx
```
#### Output

```text
file.xlsx:$office$*2013*100000*256*16*b36ceb903059d6b81cef6ef491f33043*e0449ab8cfb2b2c17bea6760218e262a*06911117dff58728126891ad908060556764c189940371d7f5d92ae9037589f9
```

Then i had to modify the string (remove the filename at the start) and save it to a file

#### xlsx.txt

```text
$office$*2013*100000*256*16*b36ceb903059d6b81cef6ef491f33043*e0449ab8cfb2b2c17bea6760218e262a*06911117dff58728126891ad908060556764c189940371d7f5d92ae9037589f9
```
even though this was generated in office 365 the password extraction appears to be 2013.

https://hashcat.net/wiki/doku.php?id=example_hashes
9400 - office 2007
9500 - office 2010
9600 - office 2013
25300 - office 2016

```text
hashcat -a 0 -m 9600 xslx.txt rockyou.txt
```
cracked in about 15 seconds

```text
$office$*2013*100000*256*16*b36ceb903059d6b81cef6ef491f33043*e0449ab8cfb2b2c17bea6760218e262a*06911117dff58728126891ad908060556764c189940371d7f5d92ae9037589f9:12345
```
