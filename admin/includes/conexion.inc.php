<?php

$link = mysql_connect($config_host, $config_user, $config_password);
mysql_select_db($config_db);
if (mysql_errno() > 0) {
  echo "<h1>DB Error number : " . mysql_errno() . "</h1><br>";
  echo "<b>" . mysql_error() . "</b>";
}