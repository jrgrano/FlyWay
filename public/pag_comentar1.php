<?php
session_start();
include 'config.php';

$mostrarPopup_Comentado = false;

if (isset($_POST['submit']))
    {
        $texto = $_POST['Texto'];
        $autor = $_SESSION['ID'];
        $Post = $_SESSION['Post_1_ID'];

        $sql = "INSERT INTO Comentarios (Comentario_Texto, Comentario_Usuario_KEY, Comentario_post_KEY)
        VALUES ('$texto', '$autor', '$Post')";

        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false)
        {
        die(print_r(sqlsrv_errors(), true));
        }
        else
        {
        $mostrarPopup_Comentado = true;
        }
    }
?>

<!-- só para não dar erro-->
<?php if (!isset($_SESSION['ID']) || $_SESSION['ID'] === null): ?>
<?php
$_SESSION['Email'] = null;
$_SESSION['senha'] = null;
$_SESSION['pagina'] = 0;
$_SESSION['ID'] = null;
$_SESSION['Nome'] = null;
$_SESSION['Img_Perfil'] = null;   
?>    
<?php endif; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Fazer uma Postagem</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="pag_principal.php"">✈ FlyWay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <!-- Links principais -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="pag_principal.php">Início</a>
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

            <!-- Não entrar nas configs se não estiver logado -->
            <?php if (!isset($_SESSION['ID']) || $_SESSION['ID'] === null): ?>
            <li><a class="dropdown-item" href="pag_login_cadastro.php">Fazer login</a></li>
            <?php endif; ?>
            <?php if($_SESSION['ID'] !== null): ?>
            <li><a class="dropdown-item" href="pag_configUsuario.php">Configurações</a></li>
            <?php endif; ?>
            
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="pag_logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Fim da nav bar -->

  <h1>
    Comentar:
  </h1>


  <form method="post" enctype="multipart/form-data" class="mt-4">

    <label>Texto do post (200max)</label>
    <br>
    <textarea id="inpTexto" name="Texto" placeholder="Escreva seu texto aqui..."
              style="width: 300px; height: 150px; resize: vertical;"
              maxlength="200" required></textarea>
    <br>
    <br>

    <button name="submit" type="submit">Postar</button>
  </form>

  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($mostrarPopup_Comentado): ?>
  <script>
      alert("comentario feito com sucesso");
      location.href='pag_principal.php';
  </script>
<?php endif; ?>
</body>
</html>