## Bettercap


bettercap -iface wlan0

wifi.recon on
wifi.recon.channel 1,2,3
wifi.show

### filter by mac

wifi.recon 11:22:33::44:55
wifi.show


### filter by start with c0 mac

set wifi.show.filter ^c0
wifi.show

### clear filter

set wifi.show.filter ""


### set minimum for strength of signal

set wifi.rssi.min -49


### deauth

wifi.deauth c6:2d:56:2a:53:f8

### one liner setup

bettercap -iface wlan0 -eval "set ticker.commands 'clear; wifi.show'; wifi.recon on; ticker on"

