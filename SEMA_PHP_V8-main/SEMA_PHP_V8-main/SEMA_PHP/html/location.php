<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEMA</title>
    <link rel="stylesheet" href="styles/mobile.css">
    <link rel="stylesheet" href="../css/location.css">
    <link rel="icon" href="../images/icon-site.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  </head>
  <body>

  <?php
    session_start();
    include '../server.php'; // Inclua o server.php

    // Variáveis default
    $profile_photo_url = null; 
    $img_style_header = "style='width: 38px; height: 38px; border-radius: 50%; object-fit: cover; vertical-align: middle;'";

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
      $username = $_SESSION['username'];
      $sql = mysql_query("SELECT profile_picture_url FROM tb_register WHERE username = '$username'"); 
      
      if ($sql && $linha = mysql_fetch_assoc($sql)) {
        $db_photo_url = $linha['profile_picture_url'];
        
        // Verifica se há um caminho válido
        if (isset($db_photo_url) && !empty($db_photo_url) && strpos($db_photo_url, 'uploads/') !== false) {
          // AQUI O CAMINHO DEVE SER AJUSTADO PARA SAIR DA PASTA 'html/'
          $profile_photo_url = '../' . $db_photo_url; 
        } 
      }
    }
  ?>
           
    <div class="header">
      <div class="left">
        <a href="../index.php">
          <img class="icon" src="../images/sema.png" alt="icon">
        </a>
      </div>

      <div class="right">
        <a class="areas-text" href="../index.php">                    
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
            <?php if ($profile_photo_url !== null): ?>
              <img 
                src="<?php echo htmlspecialchars($profile_photo_url); ?>" 
                alt="Foto de Perfil" 
                <?php echo $img_style_header; ?>
              >
            <?php else: ?>
              <i class="fas fa-user-circle"></i>
            <?php endif; ?>
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
  
    <!---------------------------------->

    <div class="main">
    
      <div class="center">
        <div class="container">
          <header>
            <h1><i class="fas fa-location-dot"></i> API de Localização</h1>
            <p class="subtitle">Obtenha suas coordenadas e encontre endereços</p>
          </header>
            
          <div class="content">
          <div class="section">
              <h2><i class="fas fa-location-crosshairs"></i> Sua Localização Atual</h2>
              <button id="getLocation" class="btn">
                  <i class="fas fa-map-marker-alt"></i> Obter Localização
              </button>
              
              <div id="locationStatus" class="status">Clique no botão para obter sua localização</div>
              
              <div id="locationData" class="location-data" style="display: none;">
                  <div class="data-row">
                      <div class="data-label">Latitude:</div>
                      <div id="latitude" class="data-value">-</div>
                  </div>
                  <div class="data-row">
                      <div class="data-label">Longitude:</div>
                      <div id="longitude" class="data-value">-</div>
                  </div>
                  <div class="data-row">
                      <div class="data-label">Precisão:</div>
                      <div id="accuracy" class="data-value">-</div>
                  </div>
              </div>
          </div>
          
          <div class="section">
              <h2><i class="fas fa-map"></i> Visualização do Mapa</h2>
              <div id="map" class="map-container">
                  <p>O mapa será exibido aqui após obter a localização</p>
              </div>
          </div>
            
          <div class="section">
              <h2><i class="fas fa-search"></i> Buscar Endereço</h2>
              <div class="search-box">
                  <input type="text" id="addressInput" placeholder="Digite um endereço...">
                  <button id="searchAddress" class="btn">
                      <i class="fas fa-search"></i> Buscar
                  </button>
              </div>
              
              <div id="addressData" class="location-data" style="display: none;">
                  <div class="data-row">
                      <div class="data-label">Endereço:</div>
                      <div id="formattedAddress" class="data-value">-</div>
                  </div>
                  <div class="data-row">
                      <div class="data-label">Coordenadas:</div>
                      <div id="searchedCoords" class="data-value">-</div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

   <!---------------------------------->
  
    <div class="footer">
           
      <div class="staff-information">
        <p>Ainda não nos conhece?</p>
        <a class="central-link" href="sub_links/about_us.php">sobre nós</a>
      </div>

      <div class="social_midias">
        <p class="staff-information">Nossas redes sociais</p>

        <div class="icons">
          
          <a href="https://www.instagram.com/elobos.acolhe?igsh=ZDE5N2F5ODVoY2pj">
            <img id="images" src="../images/icons/INSTA.webp" alt="">
          </a>

          <a href="https://x.com/ellobos675443">
            <img id="images" src="../images/icons/xTWT.avif" alt="">
          </a>

          <a href="https://www.youtube.com/@ellobos-n8n">
            <img id="images2" src="../images/icons/YOUYOU2.png" alt="">      
          </a>

        </div>
      </div>

    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="../js/location.js"></script>
  </body>
</html>

