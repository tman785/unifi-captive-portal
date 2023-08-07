<?php
/**
 * Authorizes the guest
 * 
 * Used example from Art of WiFi
 * description:    example PHP script to perform a basic auth of a guest device
 */

/**
 * Load the Unifi PHP Client
 */
require_once 'Client.php';

/**
 * include the config file (place your credentials etc. there if not already present)
 */
require_once 'config.php';

/**
 * the MAC address of the device to authorize
 */
$mac = htmlspecialchars($_GET["guestmac"]);

/**
 * the MAC address of the Access Point the guest is currently connected to, enter null (without quotes)
 * if not known or unavailable
 *
 * NOTE:
 * although the AP MAC address is not a required parameter for the authorize_guest() function,
 * adding this parameter will speed up the initial authorization process
 */
$ap_mac = htmlspecialchars($_GET["apmac"]);


/*
 * The name that the guest provided.
 */
$guestname = htmlspecialchars($_GET["guestname"]);


/**
 * the duration to authorize the device for in minutes
 */
$duration = htmlspecialchars($_GET["duration"]);;

/**
 * initialize the UniFi API connection class and log in to the controller
 */
$unifi_connection = new UniFi_API\Client($controlleruser, $controllerpassword, $controllerurl, $site_id, $controllerversion);
$set_debug_mode   = $unifi_connection->set_debug(false); //set to true if you need to see additional data
$loginresults     = $unifi_connection->login();

/**
 * then we authorize the device for the requested duration
 */
$auth_result = $unifi_connection->authorize_guest($mac, $duration, null, null, null, $ap_mac);


$startTime = $auth_result[0]->start;
$endTime = $auth_result[0]->end;


date_default_timezone_set('EST');

$message= $guestname . "'s access will be valid until " . date('F j, Y, g:i a', $endTime);

echo '<html>';
echo '<head>';
echo '<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0 maximum-scale=1.0, user-scalable=no">';
echo '<link rel="stylesheet" href="' . $captivePortalSite . '/captive.css"></link>';


echo '</head>';
echo '<body>';

echo '<div class="container">';
echo '<div class="form-container">';
echo '<p class="message-container">' . $message . '</p>';
if ($debug) echo '<p class="message-container">' . json_encode($auth_result, JSON_PRETTY_PRINT) . '</p>';
echo '</div>'; //form-container
echo '</div>'; //container

echo '</body>';
echo '</html>';


?>



