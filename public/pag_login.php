<?php
// Inclui o arquivo de configuração
include 'config.php';

// Processa o envio do formulário
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $nome  = $_POST['nome'];
    $tel   = $_POST['tel'];
    $senha = $_POST['senha']; // Apenas para teste

    // Query de inserção
    $sql = "INSERT INTO Usuarios (Usuario_Email, Usuario_Nome, Usuario_Telefone, Usuario_Senha)
            VALUES (?, ?, ?, ?)";
    $params = [$email, $nome, $tel, $senha];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<p style='color: green; text-align: center;'>Usuário cadastrado com sucesso!</p>";
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Criar Conta</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #284365;
    min-height: 100vh;
    color: #284365; 
    margin: 0;
    padding: 0;
}
.card-form {
    border-radius: .75rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    padding: 2rem;
    background: white;
    color: #000;
}
.card-form input,
.card-form label {
    color: #000;
}
.card-form button {
    color: #fff;
}
.logo {
    max-width: 500px;
    height: auto;
    display: block;
    margin: 40px auto;
}
h1 {
    margin: 0;
    padding: 0;
    font-size: 2.5em;
    line-height: 1;
}
.col-12.col-md-6.text-left {
    color: #A1C6F7;
}
</style>
</head>
<body>


<form action="" method="POST">
<div class="container">
    <div class="logo-container">
        <img src="imagens/2.png" alt="Logo" class="logo">
    </div>

    <div class="row align-items-center min-vh-75">
        <div class="col-12 col-md-6 text-left">
            <h1 class="fw-bold mb-3">Bem-vindo!</h1>
            <p class="lead">Crie sua conta para acessar nossos serviços.</p>
        </div>

        <div class="col-12 col-md-6">
            <div class="card-form">
                <div class="mb-3 text-center">
                    <label class="form-label d-block fw-bold fs-5">CRIE SUA CONTA:</label>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">E-mail:</label>
                    <input name="email" type="email" class="form-control" id="exampleInputEmail1" required>
                </div>

                <div class="mb-3">
                    <label for="exampleInputUsername" class="form-label">Nome de usuário (máx 40 caracteres):</label>
                    <input name="nome" type="text" class="form-control" id="exampleInputUsername" maxlength="40" required>
                </div>

                <div class="mb-3">
                    <label for="exampleInputTelephone" class="form-label">Telefone:</label>
                    <input name="tel" type="tel" class="form-control" id="exampleInputTelephone"
                        pattern="\(\d{2}\) \d{4,5}-\d{4}"
                        placeholder="(XX) XXXXX-XXXX"
                        required>
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Crie sua senha (mínimo 8 caracteres):</label>
                    <input name="senha" type="password" class="form-control" id="exampleInputPassword1" minlength="8" maxlength="40" required>
                </div>

                <div class="mb-3 text-end">
                    <button type="submit" name="submit" class="btn btn-primary">Enviar</button>
                </div>

            </div>
        </div>
    </div>
</div>
</form>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Formatação do telefone -->
<script>
const telefoneInput = document.getElementById('exampleInputTelephone');

telefoneInput.addEventListener('input', function(e) {
    let cursorPosition = telefoneInput.selectionStart;
    let value = telefoneInput.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    let formatted = '';
    if (value.length > 10) {
        formatted = `(${value.slice(0,2)}) ${value.slice(2,7)}-${value.slice(7)}`;
    } else if (value.length > 6) {
        formatted = `(${value.slice(0,2)}) ${value.slice(2,6)}-${value.slice(6)}`;
    } else if (value.length > 2) {
        formatted = `(${value.slice(0,2)}) ${value.slice(2)}`;
    } else {
        formatted = value;
    }
    telefoneInput.value = formatted;
    telefoneInput.setSelectionRange(cursorPosition, cursorPosition);
});
</script>

</body>
</html>
