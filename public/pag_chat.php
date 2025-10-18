<?php
session_start();
include 'config.php';

$usuario_id = $_GET['id'] ?? null;

//Buscar um chat já existente
$sqlBuscar = "SELECT Chat_ID FROM Chats WHERE 
(Chat_User_1_KEY = $usuario_id
OR
Chat_User_1_KEY = '$_SESSION[ID]')
AND
(Chat_User_2_KEY = $usuario_id
OR
Chat_User_2_KEY = '$_SESSION[ID]')
 ;";

$stmtBuscar = sqlsrv_query($conn, $sqlBuscar);
if ($stmtBuscar === false) die(print_r(sqlsrv_errors(), true));

$dados = sqlsrv_fetch_array($stmtBuscar, SQLSRV_FETCH_ASSOC);
if ($dados)
    {
    $id_do_Chat = $dados['Chat_ID'];
    }

else 
    {
    $sqlCriarChat = "INSERT INTO Chats (Chat_User_1_KEY, Chat_User_2_KEY) VALUES ('$usuario_id', '$_SESSION[ID]')";
    $stmtCriarChat = sqlsrv_query($conn, $sqlCriarChat);
    if ($stmtCriarChat === false) die(print_r(sqlsrv_errors(), true));
    }

    


if(isset($_POST['mensagem']))
    {
        $mensagem = $_POST['mensagem'];
        $sqlmessagem ="INSERT INTO Mensagens(mensagem_Texto, mensagem_User_KEY, mensagem_Chat_KEY) VALUES('$mensagem', '$_SESSION[ID]', '$id_do_Chat')";
        $stmtmessagem = sqlsrv_query($conn, $sqlmessagem);
    if ($stmtmessagem === false) die(print_r(sqlsrv_errors(), true));
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Página de Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>

    .caixaUser {
    background-color: #0d6efd; /* Azul Bootstrap */
    color: white;              /* Texto branco */
    padding: 20px;
    max-width: 800px;
    margin: 20px auto;
    border-radius: 10px;       /* Cantos arredondados */
    box-shadow: 0 4px 6px rgba(0,0,0,0.2); /* Sombra leve */
    font-size: 1.2em;          /* Fonte um pouco maior */
    font-weight: 500;          /* Fonte semi-bold */
}


    /* Container geral do chat */
.caixaTexto {
    background-color: #f1f1f1;
    padding: 20px;
    max-width: 800px;
    margin: 20px auto;
    height: 700px;        /* altura fixa */
    overflow-y: auto;     /* rolagem quando passa da altura */
    border-radius: 10px;
}

/* Mensagens do usuário (direita) */
.comm_D {
    background-color: #dc3545; /* vermelho */
    color: white;
    text-align: right;
    font-size: 1.2em;
    max-width: 60%;          /* largura máxima do balão */
    padding: 10px;
    margin: 5px 0;
    border-radius: 15px 15px 0 15px; /* bordas arredondadas estilo chat */
    display: inline-block;
    float: right;            /* flutua à direita */
    clear: both;             /* evita sobreposição */
    word-wrap: break-word;   /* quebra texto automaticamente */
}

/* Mensagens de outros (esquerda) */
.comm_E {
    background-color: #28a745; /* verde */
    color: white;
    text-align: left;
    font-size: 1.2em;
    max-width: 60%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 15px 15px 15px 0;
    display: inline-block;
    float: left;            /* flutua à esquerda */
    clear: both;
    word-wrap: break-word;
}

.comm_D, .comm_E {
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
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

<div class="caixaUser">
FlwZap
</div>

<div class="caixaTexto">
Texto do chat
<br>
<br>

<?php

$sqlChat ="SELECT
m.mensagem_User_KEY,
m.mensagem_Texto,
u.Usuario_ID,
u.Usuario_Nome
FROM Mensagens m
INNER JOIN Usuarios u
ON m.mensagem_User_KEY = u.Usuario_ID
WHERE m.mensagem_Chat_KEY = $id_do_Chat
ORDER BY m.mensagem_ID ASC;";

$stmtChat = sqlsrv_query($conn, $sqlChat);
if($stmtChat === false) die(print_r(sqlsrv_errors(), true));

while($row = sqlsrv_fetch_array($stmtChat, SQLSRV_FETCH_ASSOC)) {
    // Checa se a mensagem é do usuário logado
    if($row['mensagem_User_KEY'] == $_SESSION['ID']) {
        echo '<div class="comm_D">' . htmlspecialchars($row['mensagem_Texto']) . '</div>';
    } else {
        echo '<div class="comm_E">' . htmlspecialchars($row['mensagem_Texto']) . '</div>';
    }
}



?>

</div>
<!-- Área de input para enviar mensagens -->
<form method="post" style="max-width:800px; margin:20px auto;" class="d-flex">
    <input type="text" name="mensagem" class="form-control me-2" placeholder="Digite sua mensagem..." required>
    <button type="submit" class="btn btn-success">Enviar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>function atualizarChat() {
    fetch('get_mensagens.php?id=<?= $id_do_Chat ?>')
        .then(response => response.text())
        .then(data => {
            const caixa = document.querySelector('.caixaTexto');
            caixa.innerHTML = data;
            caixa.scrollTop = caixa.scrollHeight; // rola até a última mensagem
        });
}

setInterval(atualizarChat, 2000);
</script>

</body>
</html>