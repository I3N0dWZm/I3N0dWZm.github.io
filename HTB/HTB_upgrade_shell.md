#### HTB Upgrade Shell with python
#### 30-03-23

I forget this every time i get basic terminal connection to a linux system.

### Check the enviroment

which python
which bash
which sh

tailor the command from the details above to upgrade the shell to be more interactive.


### Examples

python -c 'import pty; pty.spawn("/bin/bash")'
python3 -c 'import pty; pty.spawn("/bin/bash")'
python3 -c 'import pty; pty.spawn("/bin/sh")'
python3 -c 'import pty; pty.spawn("/usr/bin/bash")'
python3 -c 'import pty; pty.spawn("/usr/bin/sh")'



