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

#### limiting the char size reduces the output to 1500
```text
rsmangler --file wordlist.txt --min 12 --max 13 --output mangled.txt 	
```

#### piping it to aircrack - note no output used
```text
rsmangler --file wordlist.txt --min 12 --max 13 | aircrack-ng -e wifu rsmangler-01.cap -w -
```
