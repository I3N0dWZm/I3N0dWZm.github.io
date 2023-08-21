## Increase TX Power

Increasing TX power can be helpful for grabbing packets from the air, or making your rogue access point stronger than the orginal.

### Get WLAN information
```text
sudo iw list
```

### Set country (so parameters can be edited)
```text
sudo iw reg get
sudo iw reg set BZ
sudo iw reg get
```

### Increase power
```text
sudo iw dev
sudo ip link set wlan0 down
sudo iw dev wlan0 set txpower fixed 30mBm
sudo iw wlan0 set monitor control
sudo ip link set wlan0 up
```

### Check
```text
sudo iw dev
```
