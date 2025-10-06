<?php
session_start();
include 'config.php';
$paginaAtual = $_SESSION['pagina'] ?? 0;

if(isset($_POST['mudarPg'])) {
    if($_POST['mudarPg'] == 'proximo') $_SESSION['pagina'] += 3;
    if($_POST['mudarPg'] == 'voltar' && $_SESSION['pagina'] > 0) $_SESSION['pagina'] -= 3;
}

$paginaAtual = $_SESSION['pagina'] ?? 0;

$post1_offset = $paginaAtual;
$post2_offset = $paginaAtual + 1;
$post3_offset = $paginaAtual + 2;
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

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FlyWay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .post-img {
      width: 100%;
      height: 100%;
      object-fit: contain; /* mostra toda a imagem sem cortar */
      border-radius: 8px;
      background: #000; /* cor de fundo caso haja espaço sobrando */
    }
    html {
    scroll-behavior: auto !important;
    }
  </style>
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

            <!-- Não entrar nas configs se não estiver logado -->
            <?php if (!isset($_SESSION['ID']) || $_SESSION['ID'] === null): ?>
            <li><a class="dropdown-item" href="pag_login_cadastro.php">Fazer login</a></li>
            <?php endif; ?>
            <?php if($_SESSION['ID'] !== null): ?>
            <li><a class="dropdown-item" href="pag_configUsuario.php">Configurações</a></li>
            <?php endif; ?>
            
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<br>

<!-- Sistema de não logado -->
<?php if($_SESSION['ID'] !== null): ?>
<a href="pag_postagem.php" style="position: fixed; right: 20px; bottom: 20px; padding: 10px 20px; background: #0d6efd; color: white; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
  Fazer uma nova postagem +
</a>
<?php endif; ?>

<?php if($_SESSION['ID'] == null): ?>
<a href="pag_login_cadastro.php" style="position: fixed; right: 20px; bottom: 20px; padding: 10px 20px; background: #0d6efd; color: white; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
  Fazer uma login para fazer uma postagem 
</a>
<?php endif; ?>

<!-- Botões fixos -->
<form method="post">
  <button type="submit" name="mudarPg" value="voltar" style="position: fixed; right: 590px; bottom: 20px; padding: 10px 20px; background: #cf5959ff; color: white; border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">Voltar</button>

  <button type="submit" name="mudarPg" value="proximo" style="position: fixed; right: 480px; bottom: 20px; padding: 10px 20px; background: #279b44ff; color: white; border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">Próximo</button>
</form>

<!-- Posts PHP -->
<?php
for($i=1; $i<=3; $i++)
{
    $offset = ${"post{$i}_offset"};
    $sql = "SELECT 
    p.Post_ID,
    p.Post_Titulo,
    p.Post_Foto,
    p.Post_Texto,
    p.Post_Data,
    u.Usuario_Nome,
    u.Usuario_img_Perfil,
    ISNULL(SUM(CAST(l.Like_UP AS INT)), 0) AS TotalLikes,
    ISNULL(SUM(CAST(l.Like_Donw AS INT)), 0) AS TotalDislikes
    FROM Posts p
    INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
    LEFT JOIN Likes l ON p.Post_ID = l.Like_Post_KEY
    GROUP BY 
    p.Post_ID,
    p.Post_Titulo,
    p.Post_Foto,
    p.Post_Texto,
    p.Post_Data,
    u.Usuario_Nome,
    u.Usuario_img_Perfil
    ORDER BY p.Post_Data DESC
    OFFSET $offset ROWS
    FETCH NEXT 1 ROW ONLY;";

    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) die(print_r(sqlsrv_errors(), true));

    $Dados = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($Dados)
    {
        ${"post_id$i"} = $Dados["Post_ID"];
        ${"img_perfil$i"} = $Dados["Usuario_img_Perfil"];
        ${"nome_Perfil$i"} = $Dados["Usuario_Nome"];
        ${"titulo$i"} = $Dados["Post_Titulo"];
        ${"img_post$i"} = $Dados["Post_Foto"];
        ${"texto$i"} = $Dados["Post_Texto"];
        ${"mostrarPost_$i"} = true;

        ${"TotaldeLikes_$i"} = $Dados["TotalLikes"];
        ${"TotaldeDislikes_$i"} = $Dados["TotalDislikes"];

        $_SESSION['Post_' . $i . '_ID'] = $Dados["Post_ID"];
    }
    else
    {
        ${"mostrarPost_$i"} = false;
    }
}
?>

<!-- Loop de renderização dos posts -->
<?php for($i=1; $i<=3; $i++): ?>
    <?php if(${"mostrarPost_$i"}): ?>

    <a id="post<?= ${"post{$i}_offset"} ?>"></a>

    <div style="background: gray; width: 1000px; max-width: 100%; margin: 20px; padding: 20px;">
        <?php $imgBase64_perfil = base64_encode(${"img_perfil$i"}); ?>
        <img src="data:image/png;base64,<?= $imgBase64_perfil ?>" alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%; ">
        <?= ${"nome_Perfil$i"} ?>
        <br>

        <h1 style="display: flex; align-items: center; gap: 10px;">
            <?= ${"titulo$i"} ?>
        </h1>

        <div style="display: flex; gap: 20px;">
            <?php $imgBase64_post = base64_encode(${"img_post$i"}); ?>
            <div style="background: black; width: 65%; height: 500px; display: flex; align-items: center; justify-content: center;">
                <img src="data:image/png;base64,<?= $imgBase64_post ?>" class="post-img">
            </div>

            <!-- Comentários -->
            <div style="background: black; width: 35%; color: white; padding: 10px; display: flex; flex-direction: column; height: 500px;">

                <?php
                $valorcomen = isset($_POST["valorcomen$i"]) ? (int)$_POST["valorcomen$i"] : 0;
                $selc = isset($_POST["selc$i"]) ? (int)$_POST["selc$i"] : 1;

                if(isset($_POST["mudarComent$i"]))
                {
                    if($_POST["mudarComent$i"] == "Passar$i")
                    {
                        $selc++;
                        $valorcomen += 3;
                    }
                    elseif($_POST["mudarComent$i"] == "Voltar$i")
                    {
                        if($selc > 1)
                        {
                            $selc--;
                            $valorcomen -= 3;
                        }
                    }
                }

                $sqlComent = "SELECT 
                    c.Comentario_ID,
                    c.Comentario_Texto,
                    u.Usuario_Nome,
                    u.Usuario_img_Perfil
                    FROM Comentarios c
                    INNER JOIN Usuarios u 
                    ON c.Comentario_Usuario_KEY = u.Usuario_ID
                    WHERE c.Comentario_post_KEY = ${"post_id$i"}
                    ORDER BY c.Comentario_ID DESC
                    OFFSET $valorcomen ROWS
                    FETCH NEXT 3 ROWS ONLY;";

                $stmt_Comen = sqlsrv_query($conn, $sqlComent);
                $comentarios = [];
                if($stmt_Comen) {
                    while($row = sqlsrv_fetch_array($stmt_Comen, SQLSRV_FETCH_ASSOC))
                    {
                        $comentarios[] = $row;
                    }
                }
                ?>

                <div style="flex: 1; display: flex; flex-direction: column; max-height:500px; overflow-y:auto;">
                    <?php
                    if(empty($comentarios)) {
                        echo "<p>Nenhum comentário encontrado.</p>";
                    } else {
                        foreach($comentarios as $comentario) {
                            echo '<div style="display:flex; border-bottom:1px solid gray; padding:5px 0; gap:10px; min-height:60px; max-height:120px;">
                                <div style="width:60px; height:60px; border-radius:50%; overflow:hidden; flex-shrink:0; background:gray;">
                                    <img src="data:image/jpeg;base64,' . base64_encode($comentario['Usuario_img_Perfil']) . '" style="width:100%; height:100%; object-fit:cover;" />
                                </div>
                                <div style="flex:1; max-height:120px; overflow-y:auto; word-wrap:break-word; white-space:normal;">
                                    <strong>' . $comentario['Usuario_Nome'] . '</strong>: ' . $comentario['Comentario_Texto'] . '
                                </div>
                              </div>';
                        }
                    }
                    ?>
                </div>

                <!-- Paginação Comentários -->
                <div style="margin-top: auto; text-align: center; padding-top: 10px;">
                    <form method="post">

                        <button type="submit" name="mudarComent<?= $i ?>" value="Voltar<?= $i ?>"> <- </button>

                        <input type="hidden" name="selc<?= $i ?>" value="<?= $selc ?>">
                        <input type="hidden" name="valorcomen<?= $i ?>" value="<?= $valorcomen ?>">
                        <?php
                        for($j=1; $j<=10; $j++) {
                            if($j == $selc) echo "<span style='color: blue;'>$j</span> ";
                            else echo $j.' ';
                        }
                        ?>

                        <button type="submit" name="mudarComent<?= $i ?>" value="Passar<?= $i ?>"> -> </button>

                    </form>
                </div>

                <!-- Link comentar -->
                <form style="margin-top: auto; text-align: center; padding-top: 10px;" method="post">
                    <a href="pag_comentar<?php echo $i; ?>.php">Comentar</a>
                </form>

                <?php
                if(isset($_POST["Like$i"]))
                {
                  if($_POST["Like$i"] == "Up$i")
                    {

                    $LikeV = 1;
                    $DisLikeV = 0;
                    $UsuarioLikeV = $_SESSION['ID'];
                    $PostLikeV = ${"post_id".$i};

                    $SqlLike = "INSERT INTO Likes (Like_UP, Like_Donw, Like_Usuario_KEY, Like_Post_KEY)
                                VALUES ('$LikeV', '$DisLikeV', '$UsuarioLikeV', $PostLikeV)";   
                            
                    $SmtLike = sqlsrv_query($conn, $SqlLike);


                    if ($stmt === false)
                      {
                      die(print_r(sqlsrv_errors(), true));
                      } 

                      else
                         {
                         echo "<script>location.href='pag_principal.php'; </script>";
                         exit();
                         }
                    };
                  
                  if($_POST["Like$i"] == "Donw$i")
                    {

                    $LikeV = 0;
                    $DisLikeV = 1;
                    $UsuarioLikeV = $_SESSION['ID'];
                    $PostLikeV = ${"post_id".$i};

                    $SqlLike = "INSERT INTO Likes (Like_UP, Like_Donw, Like_Usuario_KEY, Like_Post_KEY)
                                VALUES ('$LikeV', '$DisLikeV', '$UsuarioLikeV', $PostLikeV)";   
                            
                    $SmtLike = sqlsrv_query($conn, $SqlLike);


                    if ($stmt === false)
                      {
                      die(print_r(sqlsrv_errors(), true));
                      } 

                      else
                         {
                         echo "<script>location.href='pag_principal.php'; </script>";
                         exit();
                         }
                    };
                }
                ?>

                <form method="post">
                <div style="margin-top: auto; text-align: center; padding-top: 10px;">
                  Likes
                  <button type="submit" name="Like<?= $i ?>" value="Up<?= $i ?>"> 👍 </button>
                  : <?php echo ${"TotaldeLikes_$i"}; ?>

                  Dislike
                  <button type="submit" name="Like<?= $i ?>" value="Donw<?= $i ?>"> 👎 </button>
                   : <?php echo ${"TotaldeDislikes_$i"}; ?>
                </div>
                </form>
                

            </div>
        </div>

        <!-- Texto do post -->
        <div style="background: black; width: 100%; color: white; padding: 10px; margin-top: 10px;">
            <div style="width: 917px; height: 190px; overflow: auto; border: 1px solid black; padding: 10px;">
                <?= ${"texto$i"} ?>
            </div>
        </div>

    </div>
    <?php endif; ?>
<?php endfor; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript ;-; -->
<script>
// Salvar posição do scroll antes de sair ou recarregar a página
window.addEventListener('beforeunload', () => {
    sessionStorage.setItem('scrollPos', window.scrollY);
});

// Restaurar posição do scroll ao carregar a página
window.addEventListener('load', () => {
    const scrollPos = sessionStorage.getItem('scrollPos');
    if (scrollPos) {
        window.scrollTo({
    top: parseInt(scrollPos),
    left: 0,
    behavior: "auto" // <- sem efeito de descida
});
    }
});
</script>
</body>
</html>