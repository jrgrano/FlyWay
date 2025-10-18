<?php
session_start();
include 'config.php';

$id_do_Chat = $_GET['id'] ?? null;
if(!$id_do_Chat) exit('Chat inválido');

$sqlChat ="SELECT
    m.mensagem_User_KEY,
    m.mensagem_Texto,
    u.Usuario_ID,
    u.Usuario_Nome
FROM Mensagens m
INNER JOIN Usuarios u
ON m.mensagem_User_KEY = u.Usuario_ID
WHERE m.mensagem_Chat_KEY = ?
ORDER BY m.mensagem_ID ASC";

$params = [$id_do_Chat];
$stmtChat = sqlsrv_query($conn, $sqlChat, $params);
if($stmtChat === false) die(print_r(sqlsrv_errors(), true));

while($row = sqlsrv_fetch_array($stmtChat, SQLSRV_FETCH_ASSOC)) {
    if($row['mensagem_User_KEY'] == $_SESSION['ID']) {
        echo '<div class="comm_D">' . htmlspecialchars($row['mensagem_Texto']) . '</div>';
    } else {
        echo '<div class="comm_E">' . htmlspecialchars($row['mensagem_Texto']) . '</div>';
    }
}
