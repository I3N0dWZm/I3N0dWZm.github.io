### Extending Active Directory Attributes
### 14-05-24

I was asked if its possible to add addtional computer information to active directory computer objects, such as bios, cpu, processor, memory amount, serial number etc in a automated way.

This takes 4 stages:
1. Create the new item to the schemas attributes.
2. Add the attribute to the computer class.
3. (optional) - Add the new attribute as an extraColumns in active directory users and computers.
4. Write some script to find the relevent information and import it into the attribute field.


### 1. Create the new item to the schemas attributes.

1. Register the schema.msc if you havnt alrady (regsvr32 schmmgmt.dll)
2. load "active directory schema" in mmc.
3. go to attrbutes folder and right click, then select "create attribute ..."
4. Create a common name for the new attribute 
5. Generate x500 Object ID with powershell code below.

```text
#--- 
$Prefix="1.2.840.113556.1.8000.2554" 
$GUID=[System.Guid]::NewGuid().ToString() 
$Parts=@() 
$Parts+=[UInt64]::Parse($guid.SubString(0,4),"AllowHexSpecifier") 
$Parts+=[UInt64]::Parse($guid.SubString(4,4),"AllowHexSpecifier") 
$Parts+=[UInt64]::Parse($guid.SubString(9,4),"AllowHexSpecifier") 
$Parts+=[UInt64]::Parse($guid.SubString(14,4),"AllowHexSpecifier") 
$Parts+=[UInt64]::Parse($guid.SubString(19,4),"AllowHexSpecifier") 
$Parts+=[UInt64]::Parse($guid.SubString(24,6),"AllowHexSpecifier") 
$Parts+=[UInt64]::Parse($guid.SubString(30,6),"AllowHexSpecifier") 
$OID=[String]::Format("{0}.{1}.{2}.{3}.{4}.{5}.{6}.{7}",$prefix,$Parts[0],$Parts[1],$Parts[2],$Parts[3],$Parts[4],$Parts[5],$Parts[6]) 
$oid 
#---
```
6. Select syntax - (i use - unicode string).
7. Click "OK" to save.

### 2. Add the attribute to the computer class.

1. Within active directory schema - select classes.
2. Select "computer" and the attributes tab.
3. Add the new attrbiute within the optional selection.
4. Click "OK" to save.


### 3. (optional) - Add the new attribute as a extraColumns in active directory users and computers. 

1. Open adsiedit.msc
2. Expand Configuration
3. Go To -> DisplaySpecfiers->CN=409->CN=orginizationUnit-Display
4. Tab “Attribute Editor” ->extraColumns
5. Add attribute in syntax
```text
%attributename%, %attributename%,0,150,0
```
6. Apply/OK.
7. Open "Active directory users and computers"
8. Select option view at the top, add/remove columns - add new column.


### 4. Write some script to find the relevent information and import it into the attribute field.

As an example you could just run the following code to import data into a attrbute called serialno.

```text
powershell set-adcomputer -identity %PC% -replace @{serialNo= %serialno%}
```

If you require something a bit more automated python and a mix powershell and wmic could be used, with a predefined list of computers you wish to check in csv format.

```python
import wmi
import os
import time
import datetime
import subprocess

def set_serial_on_computer(pc,serialno):
    try:
        os.system('powershell set-adcomputer -identity "'+pc+'" -replace @{serialNo= """'+serialno+'"""}')
    except:
        pass

def read_file():
    lines = []
    with open('list.csv') as file:
        for line in file: 
            if "Name" not in line:
                line = line.strip('"').strip('"\n')
                lines.append(line)
    return lines

def wmic(pc):
    try:
        #wmic /node:pc bios get serialnumber
        proc = subprocess.Popen(["wmic", "/node:'"+pc+"'","bios","get","serialnumber"], stdout=subprocess.PIPE, shell=True)
        (out, err) = proc.communicate()
        serialno = out.replace("SerialNumber","").replace("\r","").replace("\n","").replace(" ","")
        print(pc +" : " + serialno)
        if len(str(serialno)) > 0:
            set_serial_on_computer(pc,serialno)
    except:
        pass

def worker():
    data = read_file() 
    print (data)
    for pc in data:
        wmic(pc)

if __name__ == '__main__':
    worker()

```



references

https://www.rebeladmin.com/2017/11/step-step-guide-create-custom-active-directory-attributes/
https://learn.microsoft.com/en-us/archive/technet-wiki/51121.active-directory-schema-update-and-custom-attribute
https://www.alitajran.com/additional-columns-active-directory/









