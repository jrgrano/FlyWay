<?PHP
include 'config.php';
session_start();
$mostrarPopup_arquivoBigdemais = false;
$mostrarPopup_imgUplodada = false;

if(isset($_FILES['Arquivo_Img']) && !empty($_FILES['Arquivo_Img']))
  {
    $Arquivo = $_FILES['Arquivo_Img'];
    if($Arquivo['size'] > 20971520)
      {
        $mostrarPopup_arquivoBigdemais = true;
      }

     else
      {

        $conteudoBinario = file_get_contents($Arquivo['tmp_name']);
        $conteudoHex = bin2hex($conteudoBinario);

        $sql = "UPDATE Usuarios SET Usuario_img_Perfil = 0x$conteudoHex WHERE Usuario_ID = '$_SESSION[ID]'";
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false)
          {
            die(print_r(sqlsrv_errors(), true));
          }
        else
          {
            $mostrarPopup_imgUplodada = true;
            $sql2 = "SELECT Usuario_ID, Usuario_Email, Usuario_Nome, Usuario_Senha, Usuario_img_Perfil FROM Usuarios WHERE Usuario_Email = '$_SESSION[Email]'";
            $stmt2 = sqlsrv_query($conn, $sql2);

      if ($stmt2 === false)
      {
        die(print_r(sqlsrv_errors(), true));
      } 
      else
      {
        $Dados = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
        $_SESSION['Img_Perfil'] = $Dados['Usuario_img_Perfil'];
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Configuração da conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
  <a href="pag_principal.php" class="btn btn-secondary mb-4">Voltar</a>

  <div class="card shadow-sm">
    <div class="card-body text-center">
      <h2 class="card-title mb-4">Conta logada</h2>

      <!-- Imagem de perfil -->
      <?php $img = $_SESSION['Img_Perfil']; $imgBase64 = base64_encode($img); ?>
      <img style="width: 256px; height: auto;" 
           src="data:image/png;base64,<?= $imgBase64 ?>" 
           alt="Imagem do usuário" 
           class="rounded-circle mb-3">

      <p><strong>ID:</strong> <?php echo $_SESSION['ID']; ?></p>
      <p><strong>Conta:</strong> <?php echo $_SESSION['Nome']; ?></p>

      <form method="post" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3 text-start">
          <label for="Arquivo_Img" class="form-label">Selecione o arquivo (Máximo: 20MB)</label>
          <input name="Arquivo_Img" type="file" class="form-control" id="Arquivo_Img">
        </div>
        <button name="Upload" type="submit" class="btn btn-primary">Enviar arquivo</button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($mostrarPopup_arquivoBigdemais): ?>
<script>
    alert("Erro 03: Arquivo maior que 20MB");
</script>
<?php endif; ?>

<?php if ($mostrarPopup_imgUplodada): ?>
<script>
    alert("Upload da imagem com sucesso");
</script>
<?php endif; ?>

</body>
</html>