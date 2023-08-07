Requirements
- webserver with php and php curl module
- guest network vlan needs to have access to wherever this captive portal is installed.  



Directions
- Fill out missing parts of config.default.php file and rename to config.php


Unifi Controller config
- On your guest wifi:
	- enable "Hotspot Portal"
	- OPTIONAL: Set your security protocol to WPA2
- Click on the HOTSPOT MANAGER on the left column (it will only show up after you enable the Hotspot Portal)
	- On the authentication tab, enable "External Portal Server" and click on Edit
		- Enter in the IP address of the captive portal server.  Not sure why it needs the IP address.  Also, no ports allowed.  Still dont know why.
	- Go to the settings tab
		- Enable "Show Landing Page"
		- Disable HTTPS
		- Disable Encrypted URL
		- Disable Secure Portal
		- OPTIONAL: Enable Domain if you want to use a hostname/fqdn for the url
		- Authorization Access: Add the IP address of the portal and maybe the controller too. 


		