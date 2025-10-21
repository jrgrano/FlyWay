<?php
$serverName = "mssql-express"; // instância padrão
$connectionOptions = [
    "Database" => "Db_FlyWay",
    "Uid" => "sa",
    "PWD" => "Welcome@2025",
    "TrustServerCertificate" => "True" // <--- ADICIONE ESTA LINHA
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false)
    {
    die(print_r(sqlsrv_errors(), true));
    }
?>