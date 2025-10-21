<?php
session_start();
include '../../server.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

// Buscar informações apenas do usuário logado
$username = $_SESSION['username'];
$sql = mysql_query("SELECT * FROM tb_register WHERE username = '$username'");

// Verificar se a query foi bem-sucedida
if ($sql === false) {
  die('Erro na consulta SQL: ' . mysql_error());
}

// Obter os dados do usuário
$linha = mysql_fetch_assoc($sql);

// Verificar se encontrou o usuário
if (!$linha) {
  die('Usuário não encontrado.');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mudar informações</title>
  <link rel="stylesheet" href="../../css/changeInfo1.css">
  <link rel="icon" href="../../images/icon-site.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
  <div class="header">
    <div class="left">
      <a href="../index.php">
        <img class="icon" src="../../images/sema.png" alt="icon">
      </a>
    </div>

    <div class="right">
        <a class="areas-text" href="../../index.php">                    
          <i class="fas fa-house-user"></i>                   
          HOME                     
        </a>

        <a class="areas-text" href="location.php">
          <i class="fas fa-map-marker-alt"></i>
          LOCALIZAÇÃO
        </a>

        <a class="areas-text" href="orientations.php">
          <i class="fas fa-book-open"></i>
          ORIENTAÇÕES
        </a>

        <a class="areas-text" href="contacts.php">
          <i class="fas fa-phone-alt"></i>
          CONTATOS
        </a>

      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
          <a class="areas-text" href="profile.php">
            <i class="fas fa-user-circle"></i>
            PERFIL
          </a>                                
        </div>
        <?php else: ?>
        <a class="areas-text" href="login.php">
          <i class='fas fa-sign-in-alt' id="login-size"></i>
        </a>
      <?php endif; ?>
    </div>
  </div> 

  <div class="main">

    <div class="container-grid">
    
      <h1 class="title_alternate">Alterar dados </h1>

      <form name="form2" method="POST" action="../../update.php">

        <div style="display: flex; flex-direction: row; align-items: center;">
          <div>
            <div class="info-item">
            <span class="info-label">Usuário:</span>
            <input class="info-input" type="text" value="<?php echo htmlspecialchars($linha['username']); ?>" 
            name="username">
            </div>
        
            <div class="info-item">
            <span class="info-label">Email:</span>
            <input class="info-input" type="text" value="<?php echo htmlspecialchars($linha['email']); ?>" 
            name="email">
            </div>
        
            <div class="info-item">
                <span class="info-label">Nome:</span>
                <input class="info-input" type="text" value="<?php echo htmlspecialchars($linha['nome']); ?>" 
                name="nome">
            </div>

            <div class="info-item">
                <span class="info-label">Sobrenome:</span>
                <input class="info-input" type="text" value="<?php echo htmlspecialchars($linha['sobrenome']); ?>" 
                name="sobrenome">
            </div>      
          </div>         
          
        <div class="image_profile">
          <div class="image_side">
            <div class="upload-instructions">
              <img class="icon_upload" src="../../images/icons/update_image.png" alt="">
            </div>
          </div>
        </div>     
        </div>    
        
        <div class="password-button">

          <div class="info-item">   
            <div>
              <span class="info-label">
              Senha:
              </span>             
              <input class="info-input" type="password" id="senha-input" value="<?php echo htmlspecialchars($linha['senha']); ?>" 
              name="senha">
            </div>
          </div>

          <div class="center-vision">
            <div>
              <div>
                <a href="#" id="mostrar-senha" style="margin-top: 10px; color: #007bff; text-decoration: none; cursor: pointer;">
                  <i class="fas fa-eye"></i> Mostrar senha
                </a>
              </div>
            </div>
          </div>
        </div>

        
                  
        <div class="buttons-container">
          
            <button class="delete-account-button" onclick="history.back()" type="button">
              Voltar
            </button>
          
            
          <button class="logout-button"
          type="submit" name="bt_incluir" 
          value="UPDATE" 
          onClick="document.form2.action='../../update.php'
          ">
            Alterar informações
          </button>

          </div>
        </div>    

      </form>
    </div>
  </div>

  <div class="footer">
              
      <div class="staff-information">
        <p class="staff-information2">Ainda não nos conhece?</p>
        <a class="central-link" href="sub_links/about_us.php">sobre nós</a>
      </div>

      <div class="social_midias">
        <p class="staff-information">Nossas redes sociais</p>

        <div class="icons">
          
          <a href="https://www.instagram.com/elobos.acolhe?igsh=ZDE5N2F5ODVoY2pj">
            <img id="images" src="../../images/icons/INSTA.webp" alt="">
          </a>

          <a href="https://x.com/ellobos675443">
            <img id="images" src="../../images/icons/xTWT.avif" alt="">
          </a>

          <a href="https://www.youtube.com/@ellobos-n8n">
            <img id="images2" src="../../images/icons/YOUYOU2.png" alt="">      
          </a>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script>
      const senhaInput = document.getElementById('senha-input');
      const mostrarSenha = document.getElementById('mostrar-senha');
      let senhaVisivel = true; // Começa como texto, pois seu input está como type="text"

      mostrarSenha.addEventListener('mousedown', function(e) {
        e.preventDefault();
        senhaInput.type = 'text';
        senhaVisivel = true;
        mostrarSenha.innerHTML = '<i class="fas fa-eye-slash"></i> Ocultar senha';
      });

      mostrarSenha.addEventListener('mouseup', function(e) {
        e.preventDefault();
        senhaInput.type = 'password';
        senhaVisivel = false;
        mostrarSenha.innerHTML = '<i class="fas fa-eye"></i> Mostrar senha';
      });

      mostrarSenha.addEventListener('mouseleave', function(e) {
        if (senhaVisivel) {
          senhaInput.type = 'password';
          senhaVisivel = false;
          mostrarSenha.innerHTML = '<i class="fas fa-eye"></i> Mostrar senha';
        }
      });
    </script>
</body>
</html>