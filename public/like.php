<?php
session_start();
include 'config.php';

$postID = $_POST['postID'] ?? null;
$tipo = $_POST['tipo'] ?? null;
$userID = $_SESSION['ID'] ?? null;

if (!$postID || !$tipo || !$userID) {
    echo json_encode(['error' => 'Faltam dados']);
    exit;
}

// Verifica se já votou
$check = sqlsrv_query($conn, "SELECT * FROM Likes WHERE Like_Post_KEY=? AND Like_Usuario_KEY=?", [$postID, $userID]);
if (sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
    echo json_encode(['error' => 'Já votou']);
    exit;
}

$likeUp = ($tipo == 'up') ? 1 : 0;
$likeDown = ($tipo == 'down') ? 1 : 0;

sqlsrv_query($conn, "INSERT INTO Likes (Like_UP, Like_Donw, Like_Usuario_KEY, Like_Post_KEY)
                     VALUES (?, ?, ?, ?)", [$likeUp, $likeDown, $userID, $postID]);

// Recalcula totais
$sqlCount = "SELECT 
    ISNULL(SUM(CAST(Like_UP AS INT)), 0) AS likes,
    ISNULL(SUM(CAST(Like_Donw AS INT)), 0) AS dislikes
    FROM Likes WHERE Like_Post_KEY=?";
$stmt = sqlsrv_query($conn, $sqlCount, [$postID]);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

echo json_encode($data);
?>