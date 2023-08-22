## Changing Wifi Mac

This can be handy when your mac has been blocked

```text
macchanger -r wlan0
```

```text
while [ 1 ]
do
	ifconfig wlan0 down
	macchanger -r wlan0
	ifconfig wlan0 up
	sleep 5
done
```
