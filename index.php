<?php
/**
 * Main captive portal page
 * 
 */

//Load the config file
require_once 'config.php';

/*
 * Debugging functions to extract the URL.  Not used unless debug is turned on
 */
function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_
        HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

// create the full url
function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

/*
 * Build out the html 
 */
echo '<html>';
echo '<head>';
echo '<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0 maximum-scale=1.0, user-scalable=no">';

echo '<script src="' . $captivePortalSite . '/internetCheck.js"></script>';
echo '<link rel="stylesheet" href="' . $captivePortalSite . '/captive.css"></script>';

echo '</head>';
echo '<body>';

echo '<div class="container">';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo '<div class="form-container">';
        echo '<p class="message-container">' . $introMessage . '</p>';
        echo '<form action="" method="POST">';
        echo '<input type="text" name="name" class="form-field" placeholder="Enter your name" />';
        echo '<input type="hidden" name="apmac" value="' . htmlspecialchars($_GET["ap"]) . '" />';
        echo '<input type="hidden" name="guestmac" value="' . htmlspecialchars($_GET["id"]) . '" />';
        echo '<input type="submit" class="submit-button" value="Submit" /></form>';
        
        // Used to provide the mac address of the guest and AP if needed
        // URL provides some additional data if needed
        if ($debug) {
            echo '<p><b>Your Access Point is:</b> ' . htmlspecialchars($_GET["ap"]) . '</p>';
            //echo '<br>';
            echo '<p><b>Your MAC Address is:</b> ' . htmlspecialchars($_GET["id"]) . '</p>';
            //echo '<br><br>';

            $absolute_url = full_url( $_SERVER );
            echo '<p><b>Your original URL was:</b> ' . $absolute_url . '</p>';
        }
        echo '</div>';

        break;


    case 'POST':
        $guestmac = htmlspecialchars($_POST["guestmac"]);
        $apmac = htmlspecialchars($_POST["apmac"]);
        $guestname = htmlspecialchars($_POST["name"]);
        $auth_link_base = $captivePortalSite . "/unifi.php?guestmac=" . $guestmac . "&apmac=" . $apmac . "&guestname=" . $guestname;

        //Create multiple links
        $auth_link_10m = $auth_link_base . "&duration=10"; //10 min
        $auth_link_8h = $auth_link_base . "&duration=480"; //8hrs
        $auth_link_24h = $auth_link_base . "&duration=1440";
        $auth_link_72h = $auth_link_base . "&duration=4320";
        $auth_link_perm = $auth_link_base . "&duration=9999999";


        //Build out the message for Telegram
        $message= $guestname . " is requesting access. ";
        if ($debug) $message= $message . "\nAuthorize for <a href=\"" . $auth_link_10m . "\">10 min</a>";
        $message= $message . "\nAuthorize for <a href=\"" . $auth_link_8h . "\">8 hours</a>";
        $message= $message . "\nAuthorize for <a href=\"" . $auth_link_24h . "\">24 hours</a>";
        $message= $message . "\nAuthorize for <a href=\"" . $auth_link_72h . "\">72 hours</a>";
        $message= $message . "\nAuthorize <a href=\"" . $auth_link_perm . "\">permanently</a>";

        //Build out the URL and execute cURL
        $params=[
            'chat_id'=>$telegramChatId,
            'parse_mode'=>"HTML",
            'text'=>$message,
        ];
        $ch = curl_init($telegramApi . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        // Show a waiting message
        echo '<div class="form-container">';
        echo '<p class="message-container">' . $waitingMessage . '</p>';
        echo '</div>';
        echo '<script>checkAndRedirect(); setInterval(checkAndRedirect, 10000);</script>';

        break;
}
echo '</div>';  //container

echo '</body>';
echo '</html>';
?>