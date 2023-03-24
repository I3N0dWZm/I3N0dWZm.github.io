### 02-12-22
### FOPN foweb encrypted PDF - Printer Privilege Escalation

I was reviewing a pdf with FOPN_foweb encryption, and wanted to see if i could increase privileges to remove encryption and decompress the file completely.

```text
Filter/FOPN_foweb/V 1
```

This type of encryption requires an addon to most pdf viewers which connects to the internet to send a response of what privileges are allowed to the pdf file.

Im only testing with the example document PDF, but I could quickly see that a man in the middle attack was the best way foward to increase privileges on the printer rights to print to pdf thereby removing any encryption.

Monitoring the request/response information in burp suite, i could see the majority of parameters were being parroted back from request -> response, the only parameters of real interest was the "Code" and the "Perms", as these were not in the request but were in the reponse, it also appeared to remain the same for this pdf over multiple requests/reponses.

"code" must be used to decrypt the pdf in some way and perms must be a permission level.

response when opening default example - installcomplete.pdf
note - omited epoch timestamp
```text
Request=Setting = RetVal=Answer&Stamp=0000000000&StringFormat=ASCII&RequestSchema=Default
Request=DocPerm = RetVal=Answer&Stamp=0000000000&ServId=InstallComplete&DocuId=D-700&Ident3ID=number3&Ident4ID=number4&Code=mnopq&Perms=105
```

Plan of action - if i can re-send the code to open the file and change the perms field to increase privileges, i can then print.

Using mitmproxy i was able to build a basic python script to intercept the requests (store the unique id codes) and modify the responses.

I found running the script below with this command allowed me to increase privilege and print the pdf.

```text
mitmweb -s filters.py --web-host 127.0.0.1 --web-port 8081  --listen-port 8079 --listen-host 127.0.0.1 --ssl-insecure
```

filters.py
```python
from mitmproxy import ctx
from mitmproxy import http

server = "plugin.servername.com"    #destination server for authorization
mcode = "mnopq"                     #magic code found in docperm response
docuid = ""
servid = ""
epocht = ""
ident3id = ""
ident4id = ""

def response(flow: http.HTTPFlow):
    if ".ashx?Request=PrintPerm" in flow.request.pretty_url:   
        flow.response.status_code=200   
        data = "RetVal=Answer&"                                                                                      
        data = data + "Stamp="+ epocht+"&"                                                    
        data = data + "ServId="+servid+"&"    
        data = data + "DocuId="+docuid+"&"    
        data = data + "Ident3Id=&"   
        data = data + "Ident4Id=&"   
        data = data + "Perms=1&"
        data = data + "NotifyPrint=0&"                                                  
        data = data + "ServerSessionData=&"
        data = data + "DocumentSessionData="
        flow.response.content=bytes(data,"UTF-8")   
        
    if ".ashx?Request=Setting" in flow.request.pretty_url:
        flow.response.status_code=200        
        data = "RetVal=Answer&"    
        data = data + "Stamp="+epocht+"&" 
        data = data + "StringFormat=ASCII&"
        data = data + "RequestSchema=Default" 
        flow.response.content=bytes(data,"UTF-8")
        
    if ".ashx?Request=DocPerm" in flow.request.pretty_url:        
        flow.response.status_code=200        
        data = "RetVal=Answer&"    
        data = data + "Stamp="+epocht+"&" 
        data = data + "StringFormat=ASCII&"
        data = data + "ServId="+servid+"&"
        data = data + "DocuId="+docuid+"&"
        data = data + "Ident3ID="+ident3id+"&
        data = data + "Ident4ID="+ident4id+"&
        data = data + "Code="+mcode+"&"         #magic code
        data = data + "Perms=117"               #105 blocks printing, lets try 117    
        flow.response.content=bytes(data,"UTF-8")
        
def request(flow: http.HTTPFlow) -> None:
    global docuid
    global servid
    global epocht
    global ident3id
    global ident4id
    if flow.request.pretty_host == server:
        flow.request.host = "zzz.zzz"
        request = (str(flow.request))
        bits = str(request).split("&")
        for i in bits:
            if "Stamp=" in i:
                epocht = i.replace("Stamp=","")
            if "ServiceID" in i:
                servid =  i.replace("ServiceID=","")
            if "DocumentID" in i:
                docuid =  i.replace("DocumentID=","")
            if "Ident3ID" in i:
                ident3id =  i.replace("Ident3ID=","")
            if "Ident4ID" in i:
                ident4id =  i.replace("Ident4ID=","")
    print (epocht,servid,docuid,ident3id,ident4id)
```
