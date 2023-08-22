## HASHCAT


command displays the specifications of our Kali system's installed devices and indicates OpenCL is set up properly
```text
hashcat -I 
```

### PEN210 states:

```text
2500 | WPA/WPA2 EAPOL hashes

hashcat -b -m 2500
hashcat -m 2500 output.hccapx /usr/share/john/password.lst
```

### had more luck with 

```text
22000 | WPA-PBKDF2-PMKID+EAPOL
22001 | WPA-PMK-PMKID+EAPOL

hcxpcapngtool -o hash.hc22000 -E wordlist out.cap
hashcat -m 22000 hc2200.txt rockyou.txt
```
