<?php
session_start();
include 'config.php';
$paginaAtual = $_SESSION['pagina'] ?? 0;

if(isset($_POST['mudarPg'])) {
    if($_POST['mudarPg'] == 'proximo') $_SESSION['pagina'] += 3;
    if($_POST['mudarPg'] == 'voltar' && $_SESSION['pagina'] > 0) $_SESSION['pagina'] -= 3;

    echo "<script>saveScrollAndReload();</script>";
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

<script>
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
</script>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FlyWay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
body {
    background-color: #f8f9fa;
    scroll-behavior: auto !important;
}

.post-card {
    margin: 20px auto;
    max-width: 1000px;
    
    /* MODIFICADO: Borda mais visível e sombra mais forte */
    border: 2px solid #adb5bd; /* Borda cinza um pouco mais escura e espessa (2px) */
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.2); /* Sombra mais intensa */
}

/* Container da Imagem */
.flex-fill.d-flex.align-items-center.justify-content-center {
    flex: 1 1 auto;
    max-width: 60%;
    height: 400px;
    background: black;
    border-radius: 8px;
    overflow: hidden;
}

.post-img {
    width: 100%;
    height: 400px;
    object-fit: fill;
    border-radius: 8px;
}

/* Box de Comentários */
.comment-box {
    flex: 1 1 auto;
    max-width: 40%;
    background: #343a40;
    color: white;
    padding: 10px;
    height: 400px;
    overflow-y: auto;
    border-radius: 8px;
}

/* Para dispositivos menores (empilha e usa largura total) */
@media (max-width: 767px) {
    .flex-fill.d-flex.align-items-center.justify-content-center,
    .comment-box {
        max-width: 100%;
        height: auto;
    }
    .post-img {
        height: 250px;
    }
    .comment-box {
        min-height: 200px;
    }
}

/* Item Individual de Comentário (Layout Flex) */
.comment-item {
    display: flex;
    gap: 10px;
    padding: 5px 0;
    border-bottom: 1px solid #495057;
}

/* Imagem de Perfil no Comentário (Tamanho Fixo) */
.comment-item img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

/* Bloco de Texto do Comentário (Quebra de Linha e Expansão) */
.comment-item > div {
    flex-grow: 1;
    overflow-wrap: break-word;
    word-wrap: break-word;
    min-width: 0; 
}

/* Estilos de Botões Fixos */
.fixed-btn {
    position: fixed;
    bottom: 20px;
    padding: 10px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    color: white;
    text-decoration: none;
}
/* Estilos individuais de botões fixos... */
.btn-post {
    right: 20px;
    background-color: #0d6efd;
}

.btn-login {
    right: 20px;
    background-color: #0d6efd;
}

.btn-prev {
    right: 480px;
    background-color: #dc3545;
}

.btn-next {
    right: 380px;
    background-color: #198754;
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
            <li><a class="dropdown-item text-danger" href="pag_logout.php">Sair</a></li>
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
  <button type="submit" name="mudarPg" value="voltar" style="position: fixed; right: 1480px; bottom: 20px; padding: 10px 20px; background: #cf5959ff; color: white; border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">Voltar</button>

  <button type="submit" name="mudarPg" value="proximo" style="position: fixed; right: 330px; bottom: 20px; padding: 10px 20px; background: #279b44ff; color: white; border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">Próximo</button>
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
    u.Usuario_ID,
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
    u.Usuario_ID,
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
        ${"user_id$i"} = $Dados["Usuario_ID"];
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

<!-- Loop de renderização dos posts com Bootstrap 5 -->
<?php for($i=1; $i<=3; $i++): ?>
    <?php if(${"mostrarPost_$i"}): ?>

    <div class="card post-card shadow">
        <!-- Cabeçalho do post: perfil e nome -->
        <a href="pag_perfil.php?id=<?= ${"user_id$i"} ?>" style="text-decoration: none; color: inherit; display: block;">
            <div class="card-header d-flex align-items-center gap-2 bg-primary text-white">
                <img src="data:image/png;base64,<?= base64_encode(${"img_perfil$i"}) ?>" class="rounded-circle" style="width:40px; height:40px; object-fit:cover;">
                <strong><?= ${"nome_Perfil$i"} ?></strong>
            </div>
        </a>

        <!-- Corpo do post: imagem e comentários -->
        <div class="card-body d-flex flex-column flex-md-row gap-3">
            <!-- Imagem do post -->
            <div class="flex-fill d-flex align-items-center justify-content-center" style="background:black; border-radius:8px;">
                <img src="data:image/png;base64,<?= base64_encode(${"img_post$i"}) ?>" class="post-img">
            </div>

            <!-- Box de comentários -->
            <div class="flex-fill comment-box d-flex flex-column">
                <?php
                $valorcomen = isset($_POST["valorcomen$i"]) ? (int)$_POST["valorcomen$i"] : 0;
                $selc = isset($_POST["selc$i"]) ? (int)$_POST["selc$i"] : 1;

                if(isset($_POST["mudarComent$i"])) {
                    if($_POST["mudarComent$i"] == "Passar$i") {
                        $selc++;
                        $valorcomen += 3;
                    } elseif($_POST["mudarComent$i"] == "Voltar$i") {
                        if($selc > 1) {
                            $selc--;
                            $valorcomen -= 3;
                        }
                    }
                    echo "<script>saveScrollAndReload();</script>";
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
                    while($row = sqlsrv_fetch_array($stmt_Comen, SQLSRV_FETCH_ASSOC)) {
                        $comentarios[] = $row;
                    }
                }
                ?>

                <div class="flex-grow-1 overflow-auto mb-2">
                    <?php
                    if(empty($comentarios)) {
                        echo "<p class='text-center text-muted'>Nenhum comentário encontrado.</p>";
                    } else {
                        foreach($comentarios as $comentario) {
                            echo '<div class="comment-item">
                                <img src="data:image/jpeg;base64,' . base64_encode($comentario['Usuario_img_Perfil']) . '" alt="perfil">
                                <div>
                                <strong>' . $comentario['Usuario_Nome'] . '</strong>: ' . $comentario['Comentario_Texto'] . '
                                </div>
                              </div>';
                        }
                    }
                    ?>
                </div>

                <!-- Paginação de comentários -->
                <form method="post" class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <button type="submit" name="mudarComent<?= $i ?>" value="Voltar<?= $i ?>" class="btn btn-sm btn-danger"> <- </button>
                    <input type="hidden" name="selc<?= $i ?>" value="<?= $selc ?>">
                    <input type="hidden" name="valorcomen<?= $i ?>" value="<?= $valorcomen ?>">
                    <?php
                    for($j=1; $j<=10; $j++) {
                        if($j == $selc) echo "<span class='text-primary fw-bold'>$j</span> ";
                        else echo "$j ";
                    }
                    ?>
                    <button type="submit" name="mudarComent<?= $i ?>" value="Passar<?= $i ?>" class="btn btn-sm btn-success"> -> </button>
                </form>

                <!-- Link comentar -->
                <?php if($_SESSION['ID'] !== null): ?>
                    <a href="pag_comentar<?= $i ?>.php" class="btn btn-sm btn-primary mt-auto">Comentar</a>
                <?php else: ?>
                    <a href="pag_login_cadastro.php" class="btn btn-sm btn-secondary mt-auto">Fazer login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rodapé do post: título, texto, likes -->
        <div class="card-footer">
            <h5><?= ${"titulo$i"} ?></h5>
            <div class="p-2 mb-2" style="background: #343a40; color:white; max-height:150px; overflow:auto; border-radius:8px;">
                <?= ${"texto$i"} ?>
            </div>

            <!-- Likes / Dislikes -->
            <?php
            if(isset($_POST["Like$i"])) {
                $LikeV = ($_POST["Like$i"] == "Up$i") ? 1 : 0;
                $DisLikeV = ($_POST["Like$i"] == "Donw$i") ? 1 : 0;
                $UsuarioLikeV = $_SESSION['ID'];
                $PostLikeV = ${"post_id".$i};

                $SqlLike = "INSERT INTO Likes (Like_UP, Like_Donw, Like_Usuario_KEY, Like_Post_KEY)
                            VALUES ('$LikeV', '$DisLikeV', '$UsuarioLikeV', $PostLikeV)";
                $SmtLike = sqlsrv_query($conn, $SqlLike);

                if ($SmtLike === false) die(print_r(sqlsrv_errors(), true));
                else echo "<script>saveScrollAndReload();</script>";
            }
            ?>

            <form method="post" class="d-flex gap-2 align-items-center">
                <button type="submit" name="Like<?= $i ?>" value="Up<?= $i ?>" class="btn btn-sm btn-success">👍</button>
                <span><?= ${"TotaldeLikes_$i"} ?></span>
                <button type="submit" name="Like<?= $i ?>" value="Donw<?= $i ?>" class="btn btn-sm btn-danger">👎</button>
                <span><?= ${"TotaldeDislikes_$i"} ?></span>
            </form>
        </div>
    </div>

    <?php endif; ?>
<?php endfor; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript ;-; -->
<!-- Controle completo do scroll -->
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