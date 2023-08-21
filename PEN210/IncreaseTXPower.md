## Increase TX Power


### Get device information
```text
sudo iw list
```

### set country (to allow the power change)
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
