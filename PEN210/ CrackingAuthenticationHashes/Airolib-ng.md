## Airolib-ng

Precompute PMK hash for attack

Find ESSID NAME and put it in a text file

echo wifu > essid.txt

Create a database

airolib-ng wifu.sqlite --import essid essid.txt

Number of passwords stored

airolib-ng wifu.sqlite --stats

import passwords

airolib-ng wifu.sqlite --import passwd /usr/share/john/password.lst
airolib-ng wifu.sqlite --import passwd rockyou.txt

check if all processed

airolib-ng wifu.sqlite --batch
airolib-ng wifu.sqlite --stats


## crack - 23,000 per second!

aircrack-ng -r wifu.sqlite wpa1-01.cap






