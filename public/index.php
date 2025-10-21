<?php
session_start();
include 'config.php';

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$mostrarPopup_emailnaoencontrado = false;
$mostrarPopup_senhaerrada = false;
$mostrarPopup_emailcadastrado = false;

// -------- CADASTRO SEGURO --------
if (isset($_POST['btn_cadastrar'])) {
    $email = $_POST['email'];
    $nome  = $_POST['nome'];
    $tel   = $_POST['tel'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail já existe
    $sql_check = "SELECT Usuario_ID FROM Usuarios WHERE Usuario_Email = ?";
    $params_check = array($email);
    $stmt_check = sqlsrv_query($conn, $sql_check, $params_check);
    if (sqlsrv_fetch($stmt_check)) {
        $mostrarPopup_emailcadastrado = true;
    } else {
        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Imagem padrão
        $caminhoImagemPadrao = "imagens/Flyway.png";
        $imagemBinaria = file_get_contents($caminhoImagemPadrao);

        $sql = "INSERT INTO Usuarios (Usuario_Email, Usuario_Nome, Usuario_Telefone, Usuario_Senha, Usuario_img_Perfil)
                VALUES (?, ?, ?, ?, ?)";
        
        $params = array(
            $email, 
            $nome, 
            $tel, 
            $senhaHash, 
            array($imagemBinaria, SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY))
        );

        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            echo "<script>alert('Cadastro efetuado com sucesso! Faça o login.');location.href='index.php'; </script>";
            exit();
        }
    }
}

// -------- LOGIN SEGURO --------
if (isset($_POST['btn_login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT Usuario_ID, Usuario_Nome, Usuario_Senha, Usuario_img_Perfil FROM Usuarios WHERE Usuario_Email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } 
    
    $Dados = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
  
    if ($Dados) {
        // Verifica a senha usando password_verify
        if (password_verify($senha, $Dados['Usuario_Senha'])) {
            // Senha correta
            $_SESSION['ID'] = $Dados['Usuario_ID'];
            $_SESSION['Nome'] = $Dados['Usuario_Nome'];
            $_SESSION['Img_Perfil'] = $Dados['Usuario_img_Perfil'];
            $_SESSION['pagina'] = 0; // Reinicia a paginação
            echo "<script>alert('Login efetuado com sucesso');location.href='pag_principal.php'; </script>";
            exit();
        } else {
            // Senha incorreta
            $mostrarPopup_senhaerrada = true;
        }
    } else {
        // E-mail não encontrado
        $mostrarPopup_emailnaoencontrado = true;
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login / Cadastro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #284365;
    min-height: 100vh;
    color: #284365;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
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
    background-color: #0d6efd; /* azul semelhante ao primeiro */
    border: none;
    border-radius: 8px;
    width: 100%;
    padding: 10px;
    color: #fff;
    cursor: pointer;
    transition: background 0.3s;
}

.card-form button:hover {
    background-color: #0b5ed7; /* tom mais escuro ao passar o mouse */
}

.logo {
    max-width: 500px;
    height: auto;
    display: block;
    margin: 40px auto;
}

h4 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: bold;
    color: #284365;
}

#toggle-btn {
    display: block;
    margin: 10px auto 20px auto;
    background: none;
    border: none;
    color: #0d6efd;
    cursor: pointer;
    font-size: 0.9rem;
}

#toggle-btn:hover {
    text-decoration: underline;
}

.alert-custom {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="container">
    <img src="imagens/logo_cad.jpg" alt="Logo" class="logo">

    <div class="row align-items-center min-vh-75 justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card-form">

                <!-- Toggle de forms -->
                <div class="text-center mb-3">
                    <button id="toggle-btn" class="btn btn-link">Não tem conta? Cadastre-se</button>
                </div>

                <!-- Mensagem de erro -->
                <?php if(isset($loginError)): ?>
                    <div class="alert-custom"><?= $loginError ?></div>
                <?php endif; ?>

                <!-- LOGIN -->
                <form id="login-form" method="POST">
                    <h4 class="text-center mb-3">Login</h4>
                    <div class="mb-3">
                        <label>E-mail:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Senha:</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" name="btn_login">Entrar</button>
                </form>

                <!-- CADASTRO -->
                <form id="cadastro-form" method="POST" style="display:none;">
                    <h4 class="text-center mb-3">Cadastro</h4>
                    <div class="mb-3">
                        <label>Nome:</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>E-mail:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label for="exampleInputTelephone" class="form-label">Telefone:</label>
                    <input name="tel" type="tel" class="form-control" id="exampleInputTelephone"
                        pattern="\(\d{2}\) \d{4,5}-\d{4}"
                        placeholder="(XX) XXXXX-XXXX"
                        required>
                    </div>
                    <div class="mb-3">
                        <label>Senha:</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" name="btn_cadastrar">Cadastrar</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
const loginForm = document.getElementById('login-form');
const cadastroForm = document.getElementById('cadastro-form');
const toggleBtn = document.getElementById('toggle-btn');

toggleBtn.addEventListener('click', () => {
    const isLogin = loginForm.style.display !== 'none';
    if(isLogin){
        loginForm.style.display = 'none';
        cadastroForm.style.display = 'block';
        toggleBtn.textContent = 'Já tem conta? Faça login';
    } else {
        loginForm.style.display = 'block';
        cadastroForm.style.display = 'none';
        toggleBtn.textContent = 'Não tem conta? Cadastre-se';
    }
});
</script>

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

<?php if ($mostrarPopup_emailnaoencontrado): ?>
  <script>
      alert("Erro 01: Não existe nenhuma conta com esse Email");
  </script>
  <?php endif; ?>

  <?php if ($mostrarPopup_senhaerrada): ?>
  <script>
      alert("Erro 02: Senha incorreta ");
  </script>
  <?php endif; ?>

</body>
</html>
