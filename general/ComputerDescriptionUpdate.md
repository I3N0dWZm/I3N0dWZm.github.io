### Active Directory Computer Description Update With Username
### 30-04-24

The code below updates the computer description in active directory with the most recent username that has used it, (very had for quick reviews).

The code utlizes the security event 4776 - https://learn.microsoft.com/en-us/previous-versions/windows/it-pro/windows-10/security/threat-protection/auditing/event-4776

#### ComputerDescriptionUpdate.py
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
        wql = ("SELECT * FROM Win32_NTLogEvent WHERE Logfile=""'Security' AND EventCode='4776' AND NOT Message LIKE '%Administrator%' AND TimeGenerated > '"+previous_str+".000000-000'")
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
