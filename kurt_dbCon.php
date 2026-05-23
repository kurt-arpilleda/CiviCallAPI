<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "civicalldb";

$db = new mysqli($host, $username, $password, $database);
$db->set_charset("utf8");
// check connection
if ($db->connect_error)
{
  trigger_error('Database connection failed: '  . $db->connect_error, E_USER_ERROR);
}
?> 
