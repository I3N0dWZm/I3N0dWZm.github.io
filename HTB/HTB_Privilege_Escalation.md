### Hack The Box - Privilege Escalation - RECON
### 30-03-23

Starting point for privilege escalation on a linux system.


### Special permission on the account/groups?

```text
id
sudo -l
```

#### suid bit set on any files?

```text
find / -type f -perm -u=s -ls 2>/dev/null
```

#### Worth trying commands - but normally dont work

```text
cat /etc/shadow
cat /home/<user>/.bash_history
bash -p
```
  
#### Custom files check

```text
ls -la /opt/
ls -la /home/<user>/
ls -la /var/www/
```
#### Installed packages

```text
grep installed /var/log/dpkg.log
```

