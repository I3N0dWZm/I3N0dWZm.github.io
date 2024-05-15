### Adding UserName To Computer Description Programmcailly
### 15-05-24

Recently i found a way to add the current users to each computer in active directory using event viewer and python/powershell.

Security event 4776 shows users logging into PCS on the PDC or BDC, this can be a very helpful event.

```text
SELECT * FROM Win32_NTLogEvent WHERE Logfile=""'Security' AND EventCode='4776'
```

The below code will need to be run on the PDC or BDC to update the decription with the current user logged in.

It will check the event viewer for the last 4 hours (this may need to be adjsuted depending on how many event are logged).

```python
import wmi
import os
import time
import datetime

def set_user_on_computer(pc,description):
    try:
        os.system('powershell set-adcomputer -identity "'+pc+'" -description "'+description+'"')
    except:
        pass

def logins():
    event_dict = {}
    try:
        wmi_o = wmi.WMI('.')
        print("Event check ...")
        previous = datetime.datetime.now() - datetime.timedelta(hours=4) #limit to look back 4 hours
        previous_str = previous.strftime("%Y%m%d%H%M%S")
        print("Logins from : " + previous_str)
        wql = ("SELECT * FROM Win32_NTLogEvent WHERE Logfile=""'Security' AND EventCode='4776' AND TimeGenerated > '"+previous_str+".000000-000'")
        wql_r = wmi_o.query(wql)
        for event in wql_r:        
            if "Audit Success" in str(event.Type):
                details = str(event.InsertionStrings).split("', u'")          
                if "$" not in str(details[1]):
                    event_dict[str(details[2])] = str(details[1])            
    except:
        pass
    return event_dict
    
def worker():
    events = logins()
    if events:
        #print(events)
        for event in events:
                if len(str(events[event])) < 50 and "&" not in str(events[event]):
                    print(str(event)+ "\t : \t"+str(events[event]))
                    set_user_on_computer(event,str(events[event]).split("@")[0])   

if __name__ == '__main__':
    worker()
```

```

