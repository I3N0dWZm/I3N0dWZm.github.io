### WSUS TbXml Table
### 28-03-24

When looking to improve the speed and stability of the WSUS service on a server I realized that the tbXml table with SUSDB database was taking up a huge amount of space and a phenomenal number of rows for a fresh installation of WSUS.

Even without selecting the category upon first sync WSUS appears to download a lot of extra details to the tbXml table (upon first sync the database file was about 700mb and the tbXml table was 580mb of that).

The tbXml table contains both xml and a blob file which is a microsoft cab contianing even more xml.

The code below removes old updates unrequired updates and non english rows from various tables including tbXml, as all machines on the network are relatively up to date anyway, this reduce my tbXml tbale to about 100mb, obviously backup before testing and change as required, your setup may be different from ours.


#### cleanup.sql
```sql
USE SUSDB
SET QUOTED_IDENTIFIER ON;
/****** strip un-needed - non english rows  ******/

DELETE FROM tbXml WHERE LanguageID NOT IN (1033, 0);
DELETE FROM tbPreComputedLocalizedProperty WHERE ShortLanguage NOT LIKE 'en%';
DELETE FROM tbLocalizedPropertyForRevision WHERE LanguageID NOT LIKE '1033%';
DELETE FROM tbLocalizedProperty WHERE NOT EXISTS (SELECT 1 FROM tbPreComputedLocalizedProperty WHERE tbPreComputedLocalizedProperty.PreComputedLocalizedPropertyID = tbLocalizedProperty.LocalizedPropertyID);
DELETE FROM tbMoreInfoURLForRevision WHERE ShortLanguage NOT LIKE 'en%';
DELETE FROM tbRevisionLanguage WHERE LanguageID NOT IN (1033, 0);

/****** set old updates to OLD ******/

WITH CTE AS ( SELECT RevisionId FROM [SUSDB].[dbo].[tbXml]
    WHERE 
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Windows XP%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Vista%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Windows 7%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Windows 8%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Office 2007%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Office 2010%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Office 2013%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%ISA 2004%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%ISA 2006%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Windows Server 2003%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Exchange Server 2013%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Exchange Server 2016%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%System Center 201%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Dynamics CRM%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%SQL 200%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Exchange 200%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Exchange Server 200%'		
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Works 8.5%'		
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Works 9%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Defender%'
        AND CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%1.[0-3]%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Defender%'
        AND CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%1.40[0-6]%'
		OR
		CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%Defender%'
        AND CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') LIKE '%1.407.[0-6]%'
		OR
		RootElementXml LIKE '*arm64%'
		OR
		RootElementXml LIKE '%CreationDate="20[0-2][0-2]-%'
		OR
		RootElementXml LIKE '%Filename="AM_Slim_Delta_Patch_1.[0-3]%'
		OR
		RootElementXml LIKE '%Filename="AM_Slim_Delta_Patch_1.40[0-6]%'	
		OR
		RootElementXml LIKE '%Readiness Level="RTW" Date="202[0-3]%'
)
UPDATE tbXml 
SET RootElementXml = 'OLD'
WHERE RevisionId IN (SELECT RevisionID FROM CTE);

/****** windows 10 old 1607 AND 1809 updates with a massive amount of xml data (about 5mb or more each!) ******/

WITH CTE AS ( 
SELECT RevisionID
FROM [SUSDB].[dbo].[tbXml]
WHERE TRY_CAST(CAST(REPLACE(REPLACE(CAST(RootElementXml AS NVARCHAR(MAX)), '&lt;', '<'), '&gt;', '>') AS XML).value('(/LocalizedProperties/Title)[1]', 'nvarchar(max)') AS NVARCHAR(MAX)) IN 
(

	'windows10.0-kb5022838',
	'windows10.0-kb5022289',
	'windows10.0-kb5035855',	
	'windows10.0-kb5034767',
    'Windows10.0-KB5035849-x64',
    'Windows10.0-KB5034768-x64',
    'Windows10.0-KB5034127-x64',
    'windows10.0-kb5034119-x64.cab',
    'windows10.0-kb5033373-x64.cab',
    'Windows10.0-KB5033371-x64',
    'windows10.0-kb5032197-x64.cab',
    'windows10.0-kb5031362-x64.cab',
    'windows10.0-kb5031361-x64',
    'windows10.0-kb5030214-x64.cab',
    'windows10.0-kb5030213-x64.cab',
    'windows10.0-kb5029247-x64.cab',
    'windows10.0-kb5029242-x64.cab',
    'windows10.0-kb5028169-x64.cab',
    'windows10.0-kb5028168-x64.cab',
    'windows10.0-kb5027222-x64.cab',
    'windows10.0-kb5027219-x64.cab',
    'windows10.0-kb5026363-x64.cab',
    'windows10.0-kb5026362-x64.cab',
    'windows10.0-kb5025229-x64.cab',
    'windows10.0-kb5025228-x64.cab',
    'windows10.0-kb5023702-x64.cab',
    'windows10.0-kb5023697-x64.cab',
    'windows10.0-kb5022840-x64.cab',
    'windows10.0-kb5022286-x64.cab'
)
)
UPDATE tbXml 
SET RootElementXml = 'OLD'
WHERE RevisionId IN (SELECT RevisionID FROM CTE);

/****** Actually delete old updates! ******/

DELETE FROM [SUSDB].[dbo].[tbXml] WHERE RootElementXml LIKE 'OLD%'

/****** rebuild and shrink database ******/

ALTER INDEX ALL ON dbo.tbXml REBUILD
ALTER INDEX ALL ON dbo.tbPreComputedLocalizedProperty REBUILD
ALTER INDEX ALL ON dbo.tbLocalizedPropertyForRevision REBUILD
ALTER INDEX ALL ON dbo.tbLocalizedProperty REBUILD
ALTER INDEX ALL ON dbo.tbMoreInfoURLForRevision REBUILD

DBCC SHRINKDATABASE(SUSDB)
```

To run this use the following command
```
sqlcmd -S np:\\.\pipe\MICROSOFT##WID\tsql\query -i "<PATH>\clean-up.sql"
```

Other handy WSUS Guides:

https://www.vmadmin.co.uk/microsoft/43-winserver2008/139-wsuswidmemory

limit the amount of memory WID susdb can use.

```sql
sp_configure ’show advanced options’, 1;
reconfigure;
go
sp_configure ‘max server memory’, 1024;
reconfigure;
go
exit
```












