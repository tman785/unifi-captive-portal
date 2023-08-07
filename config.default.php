<?php
/**
 * Main config file for the captive portal.  Used elements from Art of Wifi
 * 
 */

/**
 * Controller configuration
 */
$controlleruser     = ''; // the user name for access to the UniFi Controller
$controllerpassword = ''; // the password for access to the UniFi Controller
$controllerurl      = ''; // full url to the UniFi Controller, eg. 'https://22.22.11.11:8443', for UniFi OS-based
                          // controllers a port suffix isn't required, no trailing slashes should be added
$controllerversion  = ''; // the version of the Controller software, e.g. '4.6.6' (must be at least 4.0.0)

/**
 * set to true (without quotes) to enable debug output
 */
$debug = false;

/**
 * The site to authorize the device with
 * If a single site, try 'default'
 */
$site_id = '';



/**
  * Captive Portal Info
  * 
  * $captivePortalSite : specify the base url. If using hostname, make sure it's resolvable from the guest network.  
  *     No trailing slash.  ex: http://captiveportal.local or http://192.168.100.2
  * 
  * $introMessage : The static message to appear when a guest is prompted
  * $waitingMessage : The static message to appear while approval 
  * 
  */
$captivePortalSite  = '';
$introMessage = 'Enter your name and The Overlord will be notified to approve your access';
$waitingMessage = 'Waiting for approval';

/**
  * Telegram Info
  *
  */
$botToken="";
$telegramApi="https://api.telegram.org/bot".$botToken;
$telegramChatId=;  //** ===>>>NOTE: this chatId MUST be the chat_id of a person, NOT another bot chatId !!!**

?>
