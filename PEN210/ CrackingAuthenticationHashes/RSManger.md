## RSmangler

RSmangler - mangles input of words into a password list, case changes, switching order, leetsspeak, adding numbersand more

### Example wordlist.txt
```text
bird
cat
dog
```

#### this will output 6000 password from these three words
```text
rsmangler --file wordlist.txt --output mangled.txt 				
```
```text
bird
cat
dog
birdcat
.....
122bcd
bcd122
123bcd
bcd123
```


#### limiting the char size reduces the output to 1500
```text
rsmangler --file wordlist.txt --min 12 --max 13 --output mangled.txt 	
```
```text
adminbirdcat
birdcatadmin
adminbirddog
birddogadmin
.....
122dogcatbird
dogcatbird122
123dogcatbird
dogcatbird123
```


#### piping it to aircrack - note no output used
```text
rsmangler --file wordlist.txt --min 12 --max 13 | aircrack-ng -e wifu rsmangler-01.cap -w -
```
