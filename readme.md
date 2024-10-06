# INSTALL
Just run this on a webserver.

You can use this or ignore it, I do not mind. I made this for me

Add the following cron to your servers to make it pulse:
* * * * * curl "http://domain/heartbeat_data.php?server=(SERVER)" > /dev/null 2>&1
