<?php
$serverName = "localhost"; // instância padrão
$connectionOptions = [
    "Database" => "Db_FlyWay",
    "Uid" => "sa",
    "PWD" => "" 
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false)
    {
    die(print_r(sqlsrv_errors(), true));
    } 
?>

