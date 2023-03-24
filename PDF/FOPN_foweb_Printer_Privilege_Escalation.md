### 02-12-22
### FOPN foweb encrypted PDF - Printer Privilege Escalation

I was reviewing a pdf with FOPN_foweb encryption, and wanted to see if i could increase privilages to remove encryption and decompress the file completely.

This type of encryption requires a addon to most pdf viewers which connects to the internet to review how and what privilages you have to the pdf file.

I could quickly see that a man in the middle attack was the best way foward to increase privileges on the printer rights to print to pdf thereby removing any encryption.

monitoring the request response information in burp suite, i could the majority of parameters were being parroted back from request to response, the only fields of real interest was the "Code=" and the Perms=, as they were not in the request but was in the reponse, it also appeared to remain the same for the file over multiple requests/reponses.

"code" must be used to decrypt the pdf in some way and perms must be a persmission level.

response when opening default example - installcomplete.pdf
```text
RetVal=Answer&Stamp=000000000&ServId=InstallComplete&DocuId=D-700&Ident3ID=number3&Ident4ID=number4&Code=mnopq&Perms=105
```

Plan of action - if i can re-send the code to open the file and change the perms field to increase privilages i can then print ...

using mitmproxy i was able to build a basic python script to intercept the requests and modify the responses.

```python
from mitmproxy import ctx
from mitmproxy import http

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
        data = data + "StringFormat=ASCII&"#ASCII or Utf8
        data = data + "RequestSchema=All" 
        flow.response.content=bytes(data,"UTF-8")
        
    if ".ashx?Request=DocPerm" in flow.request.pretty_url:        
        flow.response.status_code=200        
        data = "RetVal=Answer&"    
        data = data + "Stamp="+epocht+"&" 
        data = data + "StringFormat=ASCII&"
        data = data + "ServId="+servid+"&"
        data = data + "DocuId="+docuid+"&"
        data = data + "Ident3ID="+ident3id+"&"#number3
        data = data + "Ident4ID="+ident4id+"&"#number4
        data = data + "Code=mnopq&"   #magic code
        data = data + "Perms=117"     #105 block printing lets try 117    
        flow.response.content=bytes(data,"UTF-8")
        
def request(flow: http.HTTPFlow) -> None:
    global docuid
    global servid
    global epocht
    global ident3id
    global ident4id
    if flow.request.pretty_host == "plugin.fileopen.com":
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
