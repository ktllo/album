<?php

define('MASTER_HOST','ktllo');
define('MASTER_FORCE_ACCESS','ktllo');

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','rootpass');
define('DB_NAME','album');

$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS);
