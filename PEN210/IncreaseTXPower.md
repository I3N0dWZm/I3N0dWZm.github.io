sudo iw list


#set country
sudo iw reg get
sudo iw reg set BZ
sudo iw reg get



#increase power
sudo iw dev
sudo ip link set wlan0 down
sudo iw dev wlan0 set txpower fixed 30mBm
sudo iw wlan0 set monitor control
sudo ip link set wlan0 up



#check
sudo iw dev
