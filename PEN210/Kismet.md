## Kismet - Web Interface


sudo apt install kismet

ls -al /etc/kismet/

#### Convert PcapNg to Pcap for wireshark
tshark -F pcap -r ${pcapng file} -w ${pcap file}
tshark -F pcap -r Kismet-20230101-09-03-18-1.pcapng -w out.pcap

#### Check for handshakes in file
aircrack-ng out.pcap

#### Filter file based on handshake needed BSSID MAC
tshark -r out.cap -R "(wlan.fc.type_subtype == 0x08 || wlan.fc.type_subtype == 0x05 || eapol) && wlan.addr == 11:22:33:44:55:66" -2 -w out2.cap -F pcap


### create override for logfile stores

sudo mkdir /var/log/kismet

sudo nano /etc/kismet/kismet_site.conf
log_prefix=/var/log/kismet/
log_types=kismet,pcapng

### start kismet
cd ~
sudo kismet -c wlan0 --no-ncurses

limit channels
sudo kismet -c wlan0:channels="4,5,6"

run as a background proccess
sudo kismet --daemonize

login via browser to 127.0.0.1:2501
create a user

### remote data capture

local version and remote (with wifi adapter)

sudo kismet
Launching local capture server on 127.0.0.1:3501

port foward - local server
ssh kali@192.168.62.192 -L 8000:localhost:3501

start remote capture version
sudo kismet_cap_linux_wifi --connect 127.0.0.1:8000 --source=wlan0

