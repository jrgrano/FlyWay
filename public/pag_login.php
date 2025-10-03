<?php
include 'config.php';
$mostrarPopup_emailnaoencontrado = false;
$mostrarPopup_senhaerrada = false;

if (isset($_POST['submit']))
  {

    if (!isset($_SESSION))
    {
      session_start();
      $_SESSION['Email'] = $_POST['usuario'];
      $_SESSION['senha'] = $_POST['senha'];
      $_SESSION['pagina'] = 0;

      $sql = "SELECT Usuario_ID, Usuario_Email, Usuario_Nome, Usuario_Senha, Usuario_img_Perfil FROM Usuarios WHERE Usuario_Email = '$_SESSION[Email]'";
      $stmt = sqlsrv_query($conn, $sql);

      if ($stmt === false)
      {
        die(print_r(sqlsrv_errors(), true));
      } 
      else 
      {
        $Dados = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      
        if ($Dados == 0)
        {
          $mostrarPopup_emailnaoencontrado = true;
        }

        else   
        {
          if ($Dados['Usuario_Senha'] == $_SESSION['senha'])
            {
              $_SESSION['ID'] = $Dados['Usuario_ID'];
              $_SESSION['Nome'] = $Dados['Usuario_Nome'];
              $_SESSION['Img_Perfil'] = $Dados['Usuario_img_Perfil'];
              echo "<script>alert('Login efetuado com sucesso');location.href='pag_principal.php'; </script>";
            }
          else
            {
              $mostrarPopup_senhaerrada = true;
            }
        }

        
      }
    }
  }
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Logar na Conta 0.1</title>

  <!-- Bootstrap 5 -->
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
  <div class="container">
    <!-- Logo logo acima do "Bem-vindo!" -->
    <div class="logo-container">
      <img src="imagens/2.lpg" alt="Logo" class="logo">
    </div>

    <div class="row align-items-center min-vh-75">
      <div style="color: #A1C6F7;" class="col-12 col-md-6">
        <!-- Título "Bem-vindo!" logo após a logo -->
        <h1 class="fw-bold mb-3">Bem-vindo de volta!</h1>
        <p class="lead">Faça login para acessar nossos serviços</p>
      </div>
      
      <div class="col-12 col-md-6">
        <div class="card-form">
          <!-- FORM corrigido com POST -->
          <form method="post">
            <div class="mb-3 text-center">
              <label class="form-label d-block fw-bold fs-5">ENTRAR NA CONTA:</label>
            </div>

            <div class="mb-3">
              <label for="InputUsuarioEmail" class="form-label">Usuário ou E-mail:</label>
              <input type="text" class="form-control" id="InputUsuarioEmail" name="usuario" required>
            </div>

            <div class="mb-3">
              <label for="InputSenha" class="form-label">Senha:</label>
              <input type="password" class="form-control" id="InputSenha" name="senha" minlength="8" required>
            </div>

            <div class="mb-3 text-end">
               <button type="submit" name="submit" class="btn btn-primary">Entrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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