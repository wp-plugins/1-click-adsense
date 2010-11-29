<?php

$dat=php_strip_whitespace("oneclickadsense.php");

$f=fopen("oneclickadsense_short.php","a");


fputs('/*
Plugin Name: 1-Click-AdSense
Plugin URI: http://www.1-click-adsense.net/
Description: Ad Google Adsense automatically within seconds. 
Version: 1.0
Author: gig
Author URI: http://www.1-click-adsense.net/
*/
');

fputs($f, $dat);

fclose($f);

echo ("ready");

?>