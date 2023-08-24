## Setup an Access Point


### Make sure the wifi device has the abilty to act an an access point

sudo iw list

...
Supported interface modes:
* IBSS
* managed
* AP
* AP/VLAN
* monitor
* mesh point
* P2P-client
...

### Check device is available
ip a

### Set IP on device
sudo ip addr add 20.0.0.1/24 dev wlan0
sudo ip link set wlan0 up
ip a

### Setup DHCP

nano accesspoint-dnsmasq.conf

domain-needed
bogus-priv
no-resolv
filterwin2k
expand-hosts
domain=localdomain
local=/localdomain/
listen-address=20.0.0.1
dhcp-range=20.0.0.100,20.0.0.199,12h
dhcp-lease-max=100
dhcp-option=option:router,20.0.0.1
dhcp-authoritative
# DNS: Primary and secondary Google DNS
server=8.8.8.8
server=8.8.4.4

ufw allow 53/tcp
ufw allow 53/udp
ufw allow 67/udp
sudo pkill dnsmasq
sudo dnsmasq --conf-file=accesspoint-dnsmasq.conf

sudo tail /var/log/dnsmasq.log

### Routing -  allow traffic

echo 1 | sudo tee /proc/sys/net/ipv4/ip_forward
sudo apt install nftables
sudo nft add table nat
sudo nft 'add chain nat postrouting { type nat hook postrouting priority 100 ; }'
sudo nft add rule ip nat postrouting oifname "eth0" ip daddr != 20.0.0.1/24 masquerade


### accesspoint display

nano fake-ap.conf

interface=wlan0
ssid=Fake-AP
channel=11  
# 802.11n
hw_mode=g   
#ieee80211n=1
#FOR DIFFICULT WIFI DEVICES
country_code=US
ieee80211d=1
# WPA2 PSK with CCMP
wpa=2
wpa_key_mgmt=WPA-PSK
rsn_pairwise=CCMP
wpa_passphrase=Neverevereverver1111

sudo hostapd fake-ap.conf















