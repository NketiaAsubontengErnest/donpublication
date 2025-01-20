<?php
$server = $_SERVER['SERVER_NAME'];
$scheme = $_SERVER['REQUEST_SCHEME'];

define('ROOT', "$scheme://$server/donpublication/public");
define('ASSETS', "$scheme://$server/donpublication/public/assets");
define('HOME', "$scheme://$server/donpublication");
define('HOMEASSET', "$scheme://$server/donpublication/public/homeasset");

/*

define('ROOT', "$scheme://$server/public");
define('ASSETS', "$scheme://$server/public/assets");
define('HOME', "$scheme://$server");
define('HOMEASSET', "$scheme://$server/public/homeasset");

//database variables
 */

define('DBHOST','localhost');
define('DBNAME','donpublication');
define('DBUSER','root');
define('DBPASS','0554013980A@');
define('DBDRIVER','mysql');
define('COMPANY','DON PUB. LTD');
