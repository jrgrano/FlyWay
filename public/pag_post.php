<?php
session_start();
include 'config.php';

// --------------------
// PROCESSAR LIKES ANTES DE QUALQUER HTML
// --------------------
for($i=1; $i<=1; $i++) {
    if(isset($_POST["Like$i"])) {
        $LikeV = ($_POST["Like$i"] == "Up$i") ? 1 : 0;
        $DisLikeV = ($_POST["Like$i"] == "Donw$i") ? 1 : 0;
        $UsuarioLikeV = $_SESSION['ID'] ?? null;

        // Pega o ID do post armazenado na sessão
        $PostLikeV = $_SESSION['Post_' . $i . '_ID'] ?? null;

        if($UsuarioLikeV && $PostLikeV) {
            // Verifica se o usuário já votou neste post
            $check = sqlsrv_query($conn, 
                "SELECT * FROM Likes WHERE Like_Post_KEY=? AND Like_Usuario_KEY=?", 
                [$PostLikeV, $UsuarioLikeV]
            );
            $existing = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);

            if(!$existing) {
                // Se não existe, insere
                $SqlLike = "INSERT INTO Likes (Like_UP, Like_Donw, Like_Usuario_KEY, Like_Post_KEY)
                            VALUES (?, ?, ?, ?)";
                sqlsrv_query($conn, $SqlLike, [$LikeV, $DisLikeV, $UsuarioLikeV, $PostLikeV]);
            } else {
                // Se já existe, atualiza
                $SqlUpdate = "UPDATE Likes SET Like_UP=?, Like_Donw=? 
                              WHERE Like_Post_KEY=? AND Like_Usuario_KEY=?";
                sqlsrv_query($conn, $SqlUpdate, [$LikeV, $DisLikeV, $PostLikeV, $UsuarioLikeV]);
            }
        }

        // Redireciona para evitar reenvio do formulário
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// --------------------
// PAGINAÇÃO DE POSTS
// --------------------
$paginaAtual = $_SESSION['pagina'] ?? 0;

if(isset($_POST['mudarPg'])) {
    if($_POST['mudarPg'] == 'proximo') $_SESSION['pagina'] += 3;
    if($_POST['mudarPg'] == 'voltar' && $_SESSION['pagina'] > 0) $_SESSION['pagina'] -= 3;
    header("Location: " . $_SERVER['REQUEST_URI']); // Redireciona após mudar página
    exit;
}

$paginaAtual = $_SESSION['pagina'] ?? 0;

$post1_offset = $paginaAtual;
$post2_offset = $paginaAtual + 1;
$post3_offset = $paginaAtual + 2;

// --------------------
// BUSCAR POSTS
// --------------------
for($i=1; $i<=1; $i++) {
    $offset = ${"post{$i}_offset"};
    $post_id = $_GET['id'] ?? null; // ou qualquer variável que tenha o ID do post

if ($post_id) {
    $sql = "SELECT 
                p.Post_ID, p.Post_Titulo, p.Post_Foto, p.Post_Texto, p.Post_Data,
                u.Usuario_ID, u.Usuario_Nome, u.Usuario_img_Perfil,
                ISNULL(SUM(CAST(l.Like_UP AS INT)), 0) AS TotalLikes,
                ISNULL(SUM(CAST(l.Like_Donw AS INT)), 0) AS TotalDislikes
            FROM Posts p
            INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
            LEFT JOIN Likes l ON p.Post_ID = l.Like_Post_KEY
            WHERE p.Post_ID = ?
            GROUP BY p.Post_ID, p.Post_Titulo, p.Post_Foto, p.Post_Texto, p.Post_Data,
                     u.Usuario_ID, u.Usuario_Nome, u.Usuario_img_Perfil";

    $stmt = sqlsrv_query($conn, $sql, [$post_id]);
    if ($stmt === false) die(print_r(sqlsrv_errors(), true));
}

    $Dados = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($Dados) {
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
    } else {
        ${"mostrarPost_$i"} = false;
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>FlyWay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; scroll-behavior: auto !important; }
.post-card { margin:20px auto; max-width:1000px; border:2px solid #adb5bd; border-radius:10px; box-shadow:0 6px 12px rgba(0,0,0,0.2);}
.flex-fill.d-flex.align-items-center.justify-content-center { flex:1 1 auto; max-width:60%; height:400px; background:black; border-radius:8px; overflow:hidden;}
.post-img { width:100%; height:400px; object-fit:fill; border-radius:8px;}
.comment-box { flex:1 1 auto; max-width:40%; background:#343a40; color:white; padding:10px; height:400px; overflow-y:auto; border-radius:8px;}
@media (max-width:767px){ .flex-fill.d-flex.align-items-center.justify-content-center, .comment-box { max-width:100%; height:auto;} .post-img{height:250px;} .comment-box{min-height:200px;} }
.comment-item { display:flex; gap:10px; padding:5px 0; border-bottom:1px solid #495057; }
.comment-item img { width:50px; height:50px; border-radius:50%; object-fit:cover; flex-shrink:0;}
.comment-item > div { flex-grow:1; overflow-wrap:break-word; word-wrap:break-word; min-width:0; }
.fixed-btn { position:fixed; bottom:20px; padding:10px 20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.3); color:white; text-decoration:none; }
.btn-post { right:20px; background-color:#0d6efd;}
.btn-login { right:20px; background-color:#0d6efd;}
.btn-prev {
    right: 150px; /* distância da direita */
    bottom: 20px;  /* distância de baixo */
    background-color: #dc3545;
}

.btn-next {
    right: 20px;   /* distância da direita */
    bottom: 20px;
    background-color: #198754;
}

.my-3 {
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.card-clickable {
    cursor: pointer;
    transition: background 0.2s;
}

.card-clickable:hover {
    background-color: rgba(0,0,0,0.03);
}
html { scroll-behavior:auto !important; }
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
            <?php if(!isset($_SESSION['ID']) || $_SESSION['ID'] === null): ?><li><a class="dropdown-item" href="index.php">Fazer login</a></li><?php endif; ?>

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
<br>

<!-- LOOP DE POSTS -->
<?php for($i=1; $i<=1; $i++): ?>
    <?php if(${"mostrarPost_$i"}): ?>
    <div class="card post-card shadow card-clickable" 
         onclick="window.location='pag_post.php?id=<?= ${"post_id$i"} ?>';">
        
        <!-- Cabeçalho do usuário (mantém link para perfil) -->
        <a href="pag_perfil.php?id=<?= ${"user_id$i"} ?>" style="text-decoration:none;color:inherit;display:block;">
            <div class="card-header d-flex align-items-center gap-2 bg-primary text-white">
                <img src="data:image/png;base64,<?= base64_encode(${"img_perfil$i"}) ?>" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                <strong><?= ${"nome_Perfil$i"} ?></strong>
            </div>
        </a>

        <!-- Corpo do post -->
        <div class="card-body d-flex flex-column flex-md-row gap-3">
            <!-- Imagem do post -->
            <div class="flex-fill d-flex align-items-center justify-content-center" style="background:black; border-radius:8px;">
                <img src="data:image/png;base64,<?= base64_encode(${"img_post$i"}) ?>" class="post-img">
            </div>

            <!-- Box de comentários -->
            <div class="flex-fill comment-box d-flex flex-column">
                <?php
                $valorcomen = $_POST["valorcomen$i"] ?? 0;
                $selc = $_POST["selc$i"] ?? 1;
                if(isset($_POST["mudarComent$i"])) {
                    if($_POST["mudarComent$i"] == "Passar$i") { $selc++; $valorcomen +=3; }
                    elseif($_POST["mudarComent$i"] == "Voltar$i") { if($selc>1){$selc--; $valorcomen-=3;} }
                }
                $sqlComent = "SELECT c.Comentario_ID, c.Comentario_Texto, u.Usuario_Nome, u.Usuario_img_Perfil
                              FROM Comentarios c
                              INNER JOIN Usuarios u ON c.Comentario_Usuario_KEY = u.Usuario_ID
                              WHERE c.Comentario_post_KEY = ${"post_id$i"}
                              ORDER BY c.Comentario_ID DESC OFFSET $valorcomen ROWS FETCH NEXT 3 ROWS ONLY;";
                $stmt_Comen = sqlsrv_query($conn, $sqlComent);
                $comentarios = [];
                if($stmt_Comen){ while($row = sqlsrv_fetch_array($stmt_Comen, SQLSRV_FETCH_ASSOC)){$comentarios[]=$row;} }
                ?>
                <div class="flex-grow-1 overflow-auto mb-2">
                    <?php if(empty($comentarios)){ echo "<p class='text-center text-muted'>Nenhum comentário encontrado.</p>"; }
                    else{ foreach($comentarios as $comentario){ ?>
                        <div class="comment-item">
                            <img src="data:image/jpeg;base64,<?= base64_encode($comentario['Usuario_img_Perfil']) ?>" alt="perfil">
                            <div><strong><?= $comentario['Usuario_Nome'] ?></strong>: <?= $comentario['Comentario_Texto'] ?></div>
                        </div>
                    <?php }} ?>
                </div>
                <form method="post" class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <button type="submit" name="mudarComent<?= $i ?>" value="Voltar<?= $i ?>" class="btn btn-sm btn-danger"> <- </button>
                    <input type="hidden" name="selc<?= $i ?>" value="<?= $selc ?>">
                    <input type="hidden" name="valorcomen<?= $i ?>" value="<?= $valorcomen ?>">
                    <?php for($j=1;$j<=10;$j++){ echo ($j==$selc)?"<span class='text-primary fw-bold'>$j</span> ":"$j "; } ?>
                    <button type="submit" name="mudarComent<?= $i ?>" value="Passar<?= $i ?>" class="btn btn-sm btn-success"> -> </button>
                </form>
                <?php if($_SESSION['ID']!==null): ?>
                    <a href="pag_comentar<?= $i ?>.php" class="btn btn-sm btn-primary mt-auto">Comentar</a>
                <?php else: ?>
                    <a href="index.php" class="btn btn-sm btn-secondary mt-auto">Fazer login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rodapé do post (não clicável) -->
        <div class="card-footer">
            <h5><?= ${"titulo$i"} ?></h5>
            <div class="p-2 mb-2" style="background:#343a40;color:white;max-height:150px;overflow:auto;border-radius:8px;">
                <?= ${"texto$i"} ?>
            </div>
            <!-- Likes fora do link -->
            <form method="post" class="d-flex gap-2 align-items-center mt-2">
                <button type="submit" name="Like<?= $i ?>" value="Up<?= $i ?>" class="btn btn-sm btn-success">👍</button>
                <span><?= ${"TotaldeLikes_$i"} ?></span>
                <button type="submit" name="Like<?= $i ?>" value="Donw<?= $i ?>" class="btn btn-sm btn-danger">👎</button>
                <span><?= ${"TotaldeDislikes_$i"} ?></span>
            </form>
        </div>
    </div>
    <?php endif; ?>
<?php endfor; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Salvar/restaurar scroll
window.addEventListener('beforeunload',()=>{ sessionStorage.setItem('scrollPos',window.scrollY); });
window.addEventListener('load',()=>{ const pos=sessionStorage.getItem('scrollPos'); if(pos) window.scrollTo({top:parseInt(pos),left:0,behavior:"auto"}); });
</script>

</body>
</html>