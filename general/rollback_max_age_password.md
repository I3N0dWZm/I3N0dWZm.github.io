### Rollback the Maximum Password Age Within Group Policy (gradually) 
### 06-04-22

How to slowly roll back the 'MaximumPasswordAge' in the windows password policy, so that users are not affected?

After being forced to extend the 'MaximumPasswordAge' during lockdown we are in a situation where we need to roll it back again but without effecting day to day users.

The following solution is written in python and will be set in "task scheduler" to run once or twice a week to reduce the 'MaximumPasswordAge' by 2 days each time, until it has hit the preferred max age (set in the example as 128).

The path variable needs to be the location of the password policy (group policy), probably in C:\Windows\SYSVOL somewhere.

```python
import codecs

path 		= "//path/Policies/{99999999-9999-9999-9999-99999999}/MACHINE/Microsoft/Windows NT/SecEdit/"
filename 	= "GptTmpl.inf"

def get_file_data():#readfile as unicode
	data = []
	with codecs.open(path+filename, encoding='utf_16_le') as f:
		for line in f:
			data.append(line)
	return data			
				
def write_file_data(data):#write file
	try:
		print ("writing file ...")
		file = codecs.open(path+filename, "w", "utf_16_le")
		for line in data:
			file.write(line)
		file.close()
	except:
		data = ""

def worker(data):
	counter = 0
	for line in data:
		if  "MaximumPasswordAge" in line:
			maxage = line.replace("\r\n","").split (" = ")
			print (maxage)
			print("Old Max Age : " + line)
			int_maxage 		= int(maxage[1])
			new_max_age 		= int_maxage -2
			new_max_age_line 	= "MaximumPasswordAge = " + str(new_max_age) + "\r\n"
			print("New Max Age : " + new_max_age_line)
			data[counter]= new_max_age_line
		counter = counter + 1
	return data,new_max_age

if __name__ == "__main__":
  data = get_file_data()
  if data:
	  new_data,new_max_age = worker(data)
	  if new_data:
		  if new_max_age > 128:
			  print (new_data)
			  write_file_data(new_data)
		  else:
			  print("all done!")
```
