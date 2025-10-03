<?php
session_start();
include 'config.php';

$mostrarPopup_Postado = false;

if (isset($_POST['submit']))
    {
        $titulo = $_POST['titulo'];
        $data = date('Y-m-d H:i:s');
        $tag= $_POST['opcoes'];
        $texto = $_POST['Texto'];
        $autor = $_SESSION['ID'];

        $conteudoBinario = file_get_contents($_FILES['Arquivo_Img']['tmp_name']);
        $img = bin2hex($conteudoBinario);

        $sql = "INSERT INTO Posts (Post_Titulo, Post_Data, Post_Tag, Post_Foto, Post_Texto, Post_Usuario_KEY)
        VALUES (?, CONVERT(DATETIME, ?, 120), ?, 0x$img, ?, ?)";

        $Parametros = array($titulo, $data, $tag, $texto, $autor);

        $stmt = sqlsrv_query($conn, $sql, $Parametros);

        if ($stmt === false)
        {
        die(print_r(sqlsrv_errors(), true));
        }
        else
        {
        $mostrarPopup_Postado = true;
        }
    }
?>

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
    <a class="navbar-brand fw-bold" href="#">✈ FlyWay</a>
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

  <h1>
    postar:
  </h1>


  <form method="post" enctype="multipart/form-data" class="mt-4">
    <label>Titulo do post</label>
    <br>
    <input type="text" id="inptitulo" name="titulo" placeholder="Titulo" required>
    <br>
    <br>

    <label for="opcoes">Escolha uma Tag:</label>
        <select id="opcoes" name="opcoes" required>
            <option value="Restaurante">Restaurante</option>
            <option value="Café">Café</option>
            <option value="Hotel">Hotel</option>
            <option value="Parque">Parque</option>
            <option value="Museu">Museu / Centro Cultural</option>
            <option value="Balada">Balada / Bar</option>
            <option value="Shopping">Shopping</option>
            <option value="Teatro">Teatro / Cinema</option>
        </select>
    <br>
    <br>

    <label>Selecione o arquivo (Máximo: 20MB)</label>
    <br>
    <input name="Arquivo_Img" type="file" id="Arquivo_Img">
    <br>
    <br>

    <label>Texto do post (800max)</label>
    <br>
    <textarea id="inpTexto" name="Texto" placeholder="Escreva seu texto aqui..."
              style="width: 600px; height: 150px; resize: vertical;"
              maxlength="800" required></textarea>
    <br>
    <br>

    <button name="submit" type="submit">Postar</button>
  </form>

  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($mostrarPopup_Postado): ?>
  <script>
      alert("Poste feito com sucesso");
  </script>
<?php endif; ?>
</body>
</html>