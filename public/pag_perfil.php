<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Página de Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .profile-card {
      max-width: 700px;
      margin: 30px auto;
    }
    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #0d6efd;
    }
    .profile-header {
      background-color: #0d6efd;
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
      text-align: center;
    }
    .profile-body {
      padding: 20px;
      background-color: white;
      border-radius: 0 0 10px 10px;
    }
    .post-section {
      margin-top: 20px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="pag_principal.php">✈ FlyWay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="pag_principal.php">Início</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Mais</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Opção 1</a></li>
            <li><a class="dropdown-item" href="#">Opção 2</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Outro</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex me-3" role="search">
        <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Buscar</button>
      </form>
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-flex align-items-center">
          <?php $img = $_SESSION['Img_Perfil'] ?? null; if($img) $imgBase64 = base64_encode($img); ?>
          <?php if($img): ?><img src="data:image/png;base64,<?= $imgBase64 ?>" class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;"><?php endif; ?>
          <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown"><?= $_SESSION['Nome'] ?? 'Convidado'; ?></a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li class="dropdown-item-text"><small><strong>ID:</strong> <?= $_SESSION['ID'] ?? '-'; ?></small></li>
            <?php if(!isset($_SESSION['ID']) || $_SESSION['ID'] === null): ?><li><a class="dropdown-item" href="pag_login_cadastro.php">Fazer login</a></li><?php endif; ?>

            <?php if($_SESSION['ID'] !== null): ?><li><a class="dropdown-item" href="pag_configUsuario.php">Configurações</a></li><?php endif; ?>

            <?php if($_SESSION['ID'] !== null): ?><li><a class="dropdown-item" href="pag_perfil.php?id=<?= $_SESSION['ID'] ?>">Meu perfil</a></li><?php endif; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="pag_logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php
$usuario_id = $_GET['id'] ?? null;

$sql_User = "SELECT Usuario_Nome, Usuario_img_Perfil FROM Usuarios WHERE Usuario_ID = $usuario_id;";
$smt_User = sqlsrv_query($conn, $sql_User);
if ($smt_User === false) die(print_r(sqlsrv_errors(), true));

$Dados = sqlsrv_fetch_array($smt_User, SQLSRV_FETCH_ASSOC);

if ($Dados) {
    $nome = $Dados['Usuario_Nome'];
    $imgPerfil = $Dados['Usuario_img_Perfil'];
}
?>

<div class="profile-card shadow">
  <div class="profile-header">
    <img src="data:image/png;base64,<?= base64_encode($imgPerfil ?? '') ?>" alt="Foto de Perfil" class="profile-img mb-3">
    <h2 class="fw-bold"><?= $nome ?? 'Usuário' ?></h2>
    <p>ID: <?= $usuario_id ?></p>

      <?php if($_SESSION['ID'] != $usuario_id): ?>
      <!-- Botão de Chat -->
      <a href="pag_chat.php?id=<?= $usuario_id ?>" class="btn btn-success mt-2">Chat</a>
        </div>
        <div class="profile-body">
        <h5>Posts já feitos:</h5>
      <div class="post-section">
      <?php endif; ?>

      <?php if($_SESSION['ID'] == $usuario_id): ?>
      <!-- Botão de Chat -->
      <a href="pag_configUsuario.php" class="btn btn-success mt-2">Esse é seu perfil</a>
        </div>
        <div class="profile-body">
        <h5>Posts já feitos:</h5>
      <div class="post-section">
      <?php endif; ?>


      <?php
      
        $SqlPostagens = "SELECT Post_ID, Post_Titulo FROM Posts WHERE Post_Usuario_KEY = $usuario_id";

        $SmtPostagens = sqlsrv_query($conn, $SqlPostagens);

        $postagens = [];

        while ($row = sqlsrv_fetch_array($SmtPostagens, SQLSRV_FETCH_ASSOC))
          {
          $postagens[] = $row;
          }

      ?>

      <?php foreach($postagens as $post): ?>
      <div class="card mb-3">
        <h5><?= htmlspecialchars($post['Post_Titulo']) ?></h5>
        <a href="pag_post.php?id=<?= $post['Post_ID'] ?>">Ver postagem</a>
      </div>
      <?php endforeach; ?>

      <?php if (empty($postagens)): ?>
        Nenhum post encontrado.
      <?php endif; ?>
      
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>