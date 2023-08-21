### Crunch Wordlist Generator

```text
crunch 8 9 							                  #this will use full charaterset and min length of 8 max length of 9 - 51tb eek!
crunch 8 9 abc123						              #this will limit the charset to abc123, drops the size to 110mb
crunch 11 11 0123456789 -t password@@@		#min and max 11 - password starts with password then uses defined charset 0123456789 for the @'s
crunch 1 1 -p abcde12345				          #min and max are ignored with -p is used, unique words from the charset provided
crunch 1 1 -p dog cat bird				        #will rotate thes words to unique combinations
crunch 5 5 -t ddd%% -p dog cat bird			  #will rotate thes words to unique combinations and add 2 digits to the end
crunch 5 5 aADE -t ddd@@ -p dog cat bird	#will rotate thes words to unique combinations and add two characters from the charset defined
```


### Piping crunch to aircrack

```text
crunch 11 11 -t password%%% | aircrack-ng -e wifu crunch-01.cap -w
```
