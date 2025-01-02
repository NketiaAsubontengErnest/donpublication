<?php
session_start();
require("private/core/autoload.php");

$app = new App();

$_SESSION['login'] = 1;
