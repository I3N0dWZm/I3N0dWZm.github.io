### Check for wifi dev (get id)
```text
iwconfig
```

### Check the dev is up
```text
sudo ifconfig wlan0 up 
```

# Get ESSID's 
```text
iwlist wlan0 scanning
OR
sudo iw wlan0 scan | grep SSID
```
```text
#######DIDNT WORK##########
wpa_passphrase ESSID > /etc/wpa_supplicant/wpa_supplicant.conf
wpa_passphrase BOOKERBEST_GUEST > /etc/wpa_supplicant/wpa_supplicant.conf
###########################
```

```text
sudo nmcli --ask dev wifi connect LINKSYS
Password: ••••••••••••••••
Device 'wlan0' successfully activated with '5108ff5b-9e24-46a0-8991-c76c985aba80'.
```

```text
sudo nmcli dev wifi connect BOOKERBEST_GUEST password <password> 
```
```text
curl http://192.168.1.1/proof.txt  
```


