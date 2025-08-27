<?php
$serverName = "localhost";

$connectionOptions = [
    "Database" => "Db_Usuarios_FlyWay",
    "Uid" => "sa",
    "PWD" => "" 
];

// Conectando
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    echo "Conexão feita com sucesso!";
}
?>

