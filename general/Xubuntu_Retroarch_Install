### Creating Retroarch System on Xubuntu Minimal from terminal
### 08-09-24

I wanted to see if its possbile to create a custom build retroarch on an old base unit with xubuntu which has a accisable share to update files.

#### Install xubuntu

https://cdimages.ubuntu.com/xubuntu/releases/24.04/release/
- untick require password to login

```text
sudo update && upgrade
sudo apt install openssh-server
#sudo apt install open-vm-tools open-vm-tools-desktop # if on vmware
```

#### Install retroarch
```text
https://www.retroarch.com/index.php?page=linux-instructions
sudo add-apt-repository ppa:libretro/stable && sudo apt-get update && sudo apt-get install retroarch
```

#### Setup autologin
sudo nano /etc/lightdm/lightdm.conf.d/50-myconfig.conf
```text
[SeatDefaults]
autologin-user=administrator
autologin-user-timeout=0
```

### auto_restart script - should retroarch crash.

nano ~/start_retroarch.sh
```text
#!/bin/bash
while true; do
    /bin/retroarch --fullscreen
    sleep 1
done
```
chmod +x ~/start_retroarch.sh

#### autostart Retroarch
mkdir -p ~/.config/autostart
nano ~/.config/autostart/retroarch.desktop
```text
[Desktop Entry]
Type=Application
Exec=/home/administrator/start_retroarch.sh
Hidden=false
NoDisplay=false
X-GNOME-Autostart-enabled=true
Name=RetroArch
Comment=Start RetroArch on login
```

# Samba
```text
sudo apt install samba -y
systemctl status smbd
sudo mkdir /storage
sudo mkdir /storage/{playlists,roms,thumbnails}
sudo chmod 777 -R /storage
sudo mv /etc/samba/smb.conf /etc/samba/smb.old
sudo nano /etc/samba/smb.conf
```
```text
[global]
   workgroup = WORKGROUP
   server string = Samba Server
   netbios name = samba
   security = user
   map to guest = Bad User
   dns proxy = no

[storage]
   path = /storage/
   browsable = yes
   writable = yes
   guest ok = yes
   read only = no
   create mask = 0777
   directory mask = 0777

```
sudo service smb restart

#### Update Retroarch Config
make sure retroarch has been run at least once before updating this file
nano ~/.config/retroarch/retroarch.cfg
```text
thumbnails_directory = "/storage/thumbnails"
playlist_directory = "/storage/playlists"
menu_scan_content_dir = "/storage/roms"
libretro_directory = "~/.config/retroarch/cores"
```
#### UFW Setup
```text
sudo ufw limit ssh
sudo ufw allow 139
sudo ufw allow 445
sudo ufw enable
```
#### Download all Cores for retroarch!
```text
cd ~/.config/retroarch/cores
wget -r -l1 -nd -A "*.zip" http://buildbot.libretro.com/nightly/linux/x86_64/latest/
find . -name "*.zip" -exec unzip -o {} -d ./ \;
```
#### Disable password on wake
```text
sudo nano /etc/systemd/logind.conf
HandleLidSwitch=ignore
sudo systemctl restart systemd-logind
```
#### Add symlink to storage folder in user dir
```text
ln -s /storage/ /home/<user>/storage
```

At this point should be able to restart and retroarch should load in full screen without a login.
reccomed all core info files assets and controler sare updated from the main menu at this point, ready to load in the roms from samba!
























