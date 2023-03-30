### Hack The Box - Privilege Escalation
### 30-03-23

Starting point for privilege escalation on a linux system.


### Special permission on the account/groups?

id
sudo -l

#### suid bit set on any files?

find / -type f -perm -u=s -ls 2>/dev/null


#### Worth trying commands - but normally dont work

cat /etc/shadow
cat /home/<user>/.bash_history
bash -p

  
#### Custom files check
  
ls -la /opt/
ls -la /home/<user>/
ls -la /var/www/
