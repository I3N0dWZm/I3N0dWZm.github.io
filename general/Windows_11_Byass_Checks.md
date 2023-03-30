### Windows 11 Bypass Checks
### 30-03-23

Run as reg as admin rights to bypass windows 11 hardware checks, 

For the most part if the PC can run windows 10 fine it can run windows 11. 

#### bypass.reg

```text
Windows Registry Editor Version 5.00
[HKEY_LOCAL_MACHINE\SYSTEM\Setup\LabConfig]
"BypassCPUCheck"=dword:00000001
"BypassTPMCheck"=dword:00000001
"BypassRAMCheck"=dword:00000001
"BypassSecureBootCheck"=dword:00000001
"BypassStorageCheck"=dword:00000001

[HKEY_LOCAL_MACHINE\SYSTEM\Setup\MoSetup]
"AllowUpgradesWithUnsupportedTPMOrCPU"=dword:00000001
```

#### Run in cmd prompt

```text
regedit bypass.reg
```
