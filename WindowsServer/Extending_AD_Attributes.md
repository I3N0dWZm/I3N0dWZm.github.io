### Extending Active Directory Attributes
### 14-05-24

I was asked if its poosible to add addtional computer information to active directory computer objects, such as bios, cpu, processor, memoery amount, etc.

This takes 4 stages:
1. Create the new item to the schemas attributes.
2. Add the attribute to the computer class.
3. (optional) - Add the new attribute as a veiable colum in active directory users and computers.
4. Write some script to find the relevent information and import it into the attribute field.


### 1. Create the new item to the schemas attributes.

1. Register the schema.msc if you havnt alrady (regsvr32 schmmgmt.dll)
2. load "active directory schema" in mmc.
3. go to attrbutes folder and right click, then select "create attribute ..."
4. Create a common name for the new attribute 
5. Generate x500 Object ID with code below.

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
7. click "OK" to save.





 
