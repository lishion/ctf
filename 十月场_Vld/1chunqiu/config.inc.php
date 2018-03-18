<?php

header("Content-type: text/html; charset=utf-8");

define('IS_MASTER', true);

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc() ? true : false);

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','lishion');
define('DB_NAME','ctf');
define("table_name", 'test');

?>