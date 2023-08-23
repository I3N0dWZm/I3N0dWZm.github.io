## Downloading From Terminal


### From the remote Terminal 
```text
sudo ufw status
sudo ufw allow 8080
```

Change directory to location of cap file, and create a basic web server.

```text
python3 -m http.server 8080
```

### On a local Terminal

```text
curl http://<REMOTE IP>/<cap_file_name> -o <cap_file_name>
```


