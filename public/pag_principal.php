<?php
session_start();
include 'config.php';
?>



<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FlyWay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">✈ FlyWay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <!-- Links principais -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Início</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Mais
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Opção 1</a></li>
            <li><a class="dropdown-item" href="#">Opção 2</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Outro</a></li>
          </ul>
        </li>
      </ul>

      <!-- Pesquisa -->
      <form class="d-flex me-3" role="search">
        <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Buscar</button>
      </form>

      <!-- Usuário -->
<ul class="navbar-nav">
  <li class="nav-item dropdown d-flex align-items-center">
    <?php
    $img = $_SESSION['Img_Perfil'] ?? null;
    if ($img) {
        $imgBase64 = base64_encode($img);
    }
    ?>
    <?php if ($img): ?>
      <img src="data:image/png;base64,<?= $imgBase64 ?>" 
           alt="Imagem do usuário" 
           class="rounded-circle me-2" 
           style="width:40px; height:40px; object-fit:cover;">
    <?php endif; ?>
    <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
      <?= $_SESSION['Nome'] ?? 'Convidado'; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li class="dropdown-item-text">
        <small><strong>ID:</strong> <?= $_SESSION['ID'] ?? '-'; ?></small>
      </li>
            <li><a class="dropdown-item" href="pag_configUsuario.php">Configurações</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Fim da nav bar -->

<br>

<!-- Botão lateral -->
<a href="pag_postagem.php" style="
  position: fixed;
  right: 20px;
  bottom: 20px;
  padding: 10px 20px;
  background: #0d6efd;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  text-decoration: none;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
">
  Fazer uma nova postagem +
</a>





<?php 
  $sql = "SELECT TOP 1
    p.Post_ID,
    p.Post_Titulo,
    p.Post_Foto,
    p.Post_Texto,
    p.Post_Tag,
    p.Post_Data,
    u.Usuario_Nome,
    u.Usuario_img_Perfil
    FROM Posts p
    INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
    ORDER BY p.Post_Data DESC";

  $stmt = sqlsrv_query($conn, $sql);

  if ($stmt === false)
      {
        die(print_r(sqlsrv_errors(), true));
      } 
      else 
      {
        $Dados = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $img_perfil = $Dados["Usuario_img_Perfil"];
        $nome_Perfil = $Dados["Usuario_Nome"];

        $titulo = $Dados["Post_Titulo"];
        $img_post = $Dados["Post_Foto"];
        $texto = $Dados["Post_Texto"];

      }
?>






<div style="background: gray; width: 1000px; max-width: 100%; margin: 20px; padding: 20px;">
  
  <?php $imgBase64_perfil = base64_encode($img_perfil);?>
  <img src="data:image/png;base64,<?= $imgBase64_perfil ?>" alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%; ">
  <?php echo $nome_Perfil ?>
  <br>

  <h1 style="display: flex; align-items: center; gap: 10px;">
  <?php echo $titulo ?>
  </h1>
  
  <div style="display: flex; gap: 20px;">
    <?php $imgBase64_post = base64_encode($img_post);?>
    <div style="background: black; width: 65%; color: white; padding: 10px; text-align: center;">
      <img src="data:image/png;base64,<?= $imgBase64_post ?>" style="max-width: 90%; height: auto;">
    </div>

    <!--Comentarios -->
    <div style="background: black; width: 35%; color: white; padding: 10px; display: flex; flex-direction: column; height: 500px;">
      
  <!-- Comentários -->
  <div style="flex: 1;">
    <!-- Comentário 1 -->
    <div style="display: flex; align-items: center; height: 90px; border-bottom: 1px solid gray; padding: 5px 0; gap: 10px;">
      <div style="width: 60px; height: 60px; background: gray; border-radius: 50%; flex-shrink: 0;"></div>
      <div>
        <strong>Usuário1:</strong> Esse é o primeiro comentário.
      </div>
    </div>

    <!-- Comentário 2 -->
    <div style="display: flex; align-items: center; height: 90px; border-bottom: 1px solid gray; padding: 5px 0; gap: 10px;">
      <div style="width: 60px; height: 60px; background: gray; border-radius: 50%; flex-shrink: 0;"></div>
      <div>
        <strong>Usuário2:</strong> Segundo comentário aqui, mostrando como ficaria.
      </div>
    </div>

    <!-- Comentário 3 -->
    <div style="display: flex; align-items: center; height: 90px; border-bottom: 1px solid gray; padding: 5px 0; gap: 10px;">
      <div style="width: 60px; height: 60px; background: gray; border-radius: 50%; flex-shrink: 0;"></div>
      <div>
        <strong>Usuário3:</strong> Mais comentários podem ser adicionados da mesma forma.
      </div>
    </div>

    <!-- Comentário 4 -->
    <div style="display: flex; align-items: center; height: 90px; border-bottom: 1px solid gray; padding: 5px 0; gap: 10px;">
      <div style="width: 60px; height: 60px; background: gray; border-radius: 50%; flex-shrink: 0;"></div>
      <div>
        <strong>Usuário4:</strong> Mais comentários podem ser adicionados da mesma forma.
      </div>
    </div>
  </div>


  <div style="margin-top: auto; text-align: center; padding-top: 10px;">
    Likes 👍: 0   Dislike 👎: 0
  </div>

  <br>

  <div style="margin-top: auto; text-align: center; padding-top: 10px;">
    <- 1 2 3 4 5 6 7 8 9 ->
  </div>
</div>
</div>
  
  <div style="background: black; width: 100%; color: white; padding: 10px; margin-top: 10px;">
   <div style="width: 917px; height: 190px; overflow: auto; border: 1px solid black; padding: 10px;">
    <?php echo $texto ?>
    </div>
  </div>

</div>






<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
