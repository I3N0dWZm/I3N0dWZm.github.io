### 01-02-23
### Hack The Box - Gather information

When starting a new box on HTB the starting point should be the following commands


#### Find the ports and services

Service Version - TCP scan
```text
nmap -sV -sT XX.XX.XX.XX
```

Service Version - Default NSE Scripts
```text
nmap -sV -sC XX.XX.XX.XX
```

windows boxes seem to prefer port scan only
```text
nmap -sC -sV XX.XX.XX.XX -Pn
```

#### Add host to file
sometimes the hostname is exposed on nmap for a website.

```text
nano /etc/hosts
```

#### Find directories and files

should the box have a website (95%) seem to, its worth enumerating over it (and again when/if directories are found).

not limited to the below files, if you dont find anything try another.

```text
gobuster dir -w /usr/share/seclists/Discovery/Web-Content/raft-medium-directories.txt -u "http://name.htb/" -t 30 -k
gobuster dir -w /usr/share/seclists/Discovery/Web-Content/raft-medium-files.txt -u "http://name.htb/" -t 30 -k
gobuster dir -w /usr/share/seclists/Discovery/Web-Content/combined_directories.txt -u "http://name.htb/" -t 30 -k
```

#### Find subdomains

```text
wfuzz -u http://name.htb -H "Host: FUZZ.name.htb" -w /usr/share/seclists/Discovery/DNS/subdomains-top1million-110000.txt
or
ffuf -u http://name.htb -H "Host: FUZZ.name.htb" -w /usr/share/seclists/Discovery/DNS/subdomains-top1million-110000.txt
```
try with different flags exluding reponse code or response size, different results?





