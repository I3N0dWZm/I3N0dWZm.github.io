### Normal setup

```text
sudo systemctl stop NetworkManager
sudo airmon-ng check kill
sudo iw dev
```
#### Create Monitor Mode Device

```text
sudo ip link set wlan0 down
sudo iw dev wlan0 interface add wlan0mon type monitor
sudo ip link set wlan0mon up					            
```

#### Set channel if needed
```text
iw wlan0mon set channel 7
```

### Check for networks with WPS available
```text
sudo airodump-ng wlan0mon
```
```text
BSSID              PWR  Beacons    #Data, #/s  CH   MB   ENC CIPHER  AUTH ESSID 
30:91:8F:E8:89:5F  -25      240      131    0   6  130   WPA2 CCMP   PSK  linksys

BSSID              STATION            PWR   Rate    Lost    Frames  Notes  Probes
30:91:8F:E8:89:5F  E4:84:D3:2E:1F:E2  -38    1e- 1e   948      265
```

### Attacks to retrieve the pin

