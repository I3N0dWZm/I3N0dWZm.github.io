### 25-06-30
### Strange Log File Datestamp

When presented with a logfile with a very odd datetime stamp.

The first part of this logfile was a datestamp but like nothing i had seen before, i was sure it was some kind of epoch encoded datestamp but through much trial and error eventually wrote the following script.

#### log.txt extract

#### goes here

The python script below, opens the file as CP850 to avoid encoding issues, splits the data into a list, then reads each line

I had to convert the datetime string to hex, split it into segments reverse the order then convert it to decimal to get this to display the correct epoch time.

#### log_reader.py

```python
from datetime import datetime

def get_file():
	data = ""
	try:
		file = open('log.txt', mode='rb')	
		data = file.read()
		file.close()
		data = data.decode('CP850', errors="ignore")
		datalist = data.split("\n")
	except:
		pass
	return datalist

def convert_to_datestamp(string):
	#print (string)
	try:
		hexdata = (string.encode('CP850').hex())	
		print(hexdata)
		#reverse!
		i1 = str(hexdata[0:2]) #first item
		i2 = str(hexdata[2:4]) #second item	
		i3 = str(hexdata[4:6]) #third item
		i4 = str(hexdata[6:8]) #forth item
		hexcode = (str(i4)+str(i3)+str(i2)+str(i1))
		print(hexcode)
		deccode = int(hexcode,16)
		print(deccode)
		dt_object = datetime.fromtimestamp(deccode)
		print (str(dt_object))
	except:
		dt_object = "0000-00-00 00:00:00"
	return str(dt_object)

def build_info_dict(data):
	for item in data:
			activity_datetime = convert_to_datestamp(item[0:4])

if __name__ == "__main__":
	datalist = get_file()
	data_dict = build_info_dict(datalist)
```


#### Output

```text
ca4fbd62
62bd4fca
1656573898
2022-06-30 08:24:58
5950bd62
62bd5059
1656574041
2022-06-30 08:27:21
```

