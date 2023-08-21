#check for wifi dev (get id)
iwconfig

#check the dev is up
sudo ifconfig wlan0 up 


#get essids 
iwlist wlan0 scanning
OR
sudo iw wlan0 scan | grep SSID

#######DIDNT WORK##########
wpa_passphrase ESSID > /etc/wpa_supplicant/wpa_supplicant.conf
wpa_passphrase BOOKERBEST_GUEST > /etc/wpa_supplicant/wpa_supplicant.conf
###########################

sudo nmcli --ask dev wifi connect LINKSYS
Password: ••••••••••••••••
Device 'wlan0' successfully activated with '5108ff5b-9e24-46a0-8991-c76c985aba80'.


sudo nmcli dev wifi connect BOOKERBEST_GUEST password <password> 


curl http://192.168.1.1/proof.txt  



