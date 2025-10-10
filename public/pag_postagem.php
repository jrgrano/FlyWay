<?php
// ... seu bloco PHP original (início) ...
session_start();
include 'config.php';

$mostrarPopup_Postado = false;

if (isset($_POST['submit']))
{
    // --- VERIFICAÇÃO DE ARQUIVO AQUI ---
    // A CORREÇÃO CRÍTICA PARA EVITAR O FATAL ERROR ANTERIOR
    if (!isset($_FILES['Arquivo_Img']) || $_FILES['Arquivo_Img']['error'] !== UPLOAD_ERR_OK) {
        // Se o arquivo não foi enviado ou houve um erro, pare o processamento
        // O "required" do HTML já deve pegar isso, mas a validação PHP é essencial
        echo '<script>alert("Erro: O arquivo de imagem é obrigatório ou houve um erro no upload.");</script>';
    } else {
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
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fazer uma Postagem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .post-form-card {
            max-width: 700px;
            margin: 50px auto; /* Centraliza e adiciona margem */
        }
        .form-control-file {
            /* Classe customizada para input[type=file] se precisar de ajustes */
            margin-top: 5px;
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
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="pag_principal.php">Início</a>
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

                <form class="d-flex me-3" role="search">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Buscar</button>
                </form>

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
                            <li><a class="dropdown-item text-danger" href="pag_logout.php">Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="card post-form-card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 mb-0">✈ Criar Nova Postagem</h1>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="inptitulo" class="form-label">Título do Post</label>
                        <input type="text" class="form-control" id="inptitulo" name="titulo" placeholder="Digite um título cativante" required>
                    </div>

                    <div class="mb-3">
                        <label for="opcoes" class="form-label">Escolha uma Tag:</label>
                        <select class="form-select" id="opcoes" name="opcoes" required>
                            <option value="" selected disabled>Selecione uma categoria</option>
                            <option value="Restaurante">Restaurante</option>
                            <option value="Café">Café</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Parque">Parque</option>
                            <option value="Museu">Museu / Centro Cultural</option>
                            <option value="Balada">Balada / Bar</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Teatro">Teatro / Cinema</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Arquivo_Img" class="form-label">Selecione o Arquivo de Imagem (Máximo: 20MB)</label>
                        <input name="Arquivo_Img" type="file" class="form-control form-control-file" id="Arquivo_Img" required>
                    </div>

                    <div class="mb-4">
                        <label for="inpTexto" class="form-label">Texto do Post (Máx. 800 caracteres)</label>
                        <textarea class="form-control" id="inpTexto" name="Texto" placeholder="Escreva sobre sua experiência..."
                            style="height: 150px; resize: vertical;"
                            maxlength="800" required></textarea>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send-fill"></i> Postar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($mostrarPopup_Postado): ?>
        <script>
            alert("Postagem feita com sucesso! Você será redirecionado para a página principal.");
            window.location.href = 'pag_principal.php'; 
        </script>
    <?php endif; ?>
</body>
</html>