<?php
$serverName = "192.168.201.115";
$connectionInfo = array(
    "Database" => "HANADEV",
    "UID" => "sa",
    "PWD" => "gs]HdmiyrpN2523",
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>
