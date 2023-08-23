## Downloading From Terminal


### From the remote Terminal 

sudo ufw status
sudo ufw allow 8080

Change directory to location of cap file

python3 -m http.server 8080


### On a local Terminal

curl http://<REMOTE IP>/<cap_file_name> -o <cap_file_name>



