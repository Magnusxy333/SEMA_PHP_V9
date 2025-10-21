<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área Administrativa - Usuários</title>
  <link rel="icon" href="../images/icon-site.png">
  <link rel="stylesheet" href="styles/mobile-styles/mobile2.css">
  <link rel="stylesheet" href="../css/listagem5.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="fonts/Genova.otf" rel="stylesheet" type="text/css">
  <style>
    .profile-icon {
      width: 48px; 
      height: 48px; 
      border-radius: 50%; 
      object-fit: cover; 
      vertical-align: middle;
      margin-right: 5px; /* Espaço entre a foto e o texto "PERFIL" */
    }
  </style>
</head>
<body>
  <?php
  session_start();
  include '../server.php'; // Inclua o server.php

  // --- Lógica do Header (Foto de Perfil) ---
  $profile_photo_url = null; 
  $img_style_header = "style='width: 38px; height: 38px; border-radius: 50%; object-fit: cover; vertical-align: middle;'";

  // Rejeitar acesso se não for ADM
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['username'] !== 'soyADM') {
    // Redireciona se não estiver logado como ADM
    header('Location: ../index.php');
    exit;
  }

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = $_SESSION['username'];
    $sql_header = mysql_query("SELECT profile_picture_url FROM tb_register WHERE username = '$username'"); 
    
    if ($sql_header && $linha_header = mysql_fetch_assoc($sql_header)) {
      $db_photo_url = $linha_header['profile_picture_url'];
      
      // Ajuste do caminho da foto de perfil (sair da pasta html/)
      if (isset($db_photo_url) && !empty($db_photo_url) && strpos($db_photo_url, 'uploads/') !== false) {
        $profile_photo_url = '../' . $db_photo_url; 
      } 
    }
  }
  
  // --- Lógica da Listagem e Paginação ---
  date_default_timezone_set('America/Sao_Paulo'); 

  // 1. DEFINIÇÃO DA PAGINAÇÃO
  $limit = 10; // Registros por página
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
  $page_interval = 2; // Intervalo de páginas para mostrar na paginação
  $offset = ($page - 1) * $limit; // Offset

  // Sanitiza e obtém o termo de busca
  $termo_busca = isset($_POST['busca_nome']) ? mysql_real_escape_string($_POST['busca_nome']) : '';

  // 2. CONTAGEM TOTAL DE REGISTROS (Para o cálculo das páginas)
  $sql_count = "SELECT COUNT(*) AS c FROM tb_register";

  // Se houver filtro de busca, o COUNT deve usar o mesmo filtro!
  if (!empty($termo_busca)) {
    // Busca por username OU nome
    $sql_count .= " WHERE username LIKE '{$termo_busca}%' OR nome LIKE '{$termo_busca}%'";
  }

  $sql_count_exec = mysql_query($sql_count) or die(mysql_error());

  // Pega o resultado do COUNT
  $row_count = mysql_fetch_assoc($sql_count_exec);
  $registers_total = $row_count['c'];

  // 3. CÁLCULO DO NÚMERO DE PÁGINAS
  $page_numbers = ceil($registers_total / $limit); // Use ceil para arredondar para cima

  // 4. CONSULTA DOS DADOS (Com LIMIT e OFFSET)
  $sql_query = "SELECT id, username, nome, sobrenome, email, profile_picture_url FROM tb_register";

  if (!empty($termo_busca)) {
    $sql_query .= " WHERE username LIKE '{$termo_busca}%' OR nome LIKE '{$termo_busca}%'";
  }

  $sql_query .= " ORDER BY id ASC LIMIT $limit OFFSET $offset";
  $sql = mysql_query($sql_query);
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
              src="<?php echo htmlspecialchars($$profile_photo_url); ?>" 
              alt="Foto de Perfil" 
              class="profile-icon"
            >
          <?php else: ?>
            <i class="fas fa-user-circle"></i>
          <?php endif; ?>
          PERFIL
        </a> 
      <?php else: ?>
        <a class="areas-text" href="login.php">
          <i class='fas fa-sign-in-alt' id="login-size"></i>
        </a>
      <?php endif; ?>
    </div>
  </div> 
  
  <div class="main">

    <h1>Área Administrativa: Listagem de Usuários</h1>

    <center>
      <form name="form1" method="POST" action="listagemAdm.php">
        <label>Busca por usuário (Username ou Nome):</label>
        <input type="text" name="busca_nome" value="<?php echo htmlspecialchars($termo_busca); ?>">
        <input class="search-button" type="submit" value="FILTRAR">
      </form>
    </center>
    
    <table class="table-listagem" border="1" align="center">
      <tr>
        <th colspan="8">LISTAGEM DE USUÁRIOS CADASTRADOS</th>
      </tr>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Nome</th>
        <th>Sobrenome</th>
        <th>Email</th>
        <th>Foto do Perfil</th>
        <th>EDITAR</th>
        <th>APAGAR</th>
      </tr>
      <?php 
      // Verifica se a consulta retornou resultados
      if ($sql && mysql_num_rows($sql) > 0) {
        while($linha = mysql_fetch_assoc($sql)){
          // Caminho para a foto de perfil na tabela
          $user_photo_url = (isset($linha['profile_picture_url']) && !empty($linha['profile_picture_url'])) 
              ? '../' . $linha['profile_picture_url'] 
              : '../images/icons/default_user.png'; // Caminho para uma imagem padrão
      ?>
        <tr>
          <td><center><?php echo $linha['id']; ?></center></td>
          <td><center><?php echo htmlspecialchars($linha['username']); ?></center></td>
          <td><center><?php echo htmlspecialchars($linha['nome']); ?></center></td>
          <td><center><?php echo htmlspecialchars($linha['sobrenome']); ?></center></td>
          <td><center><?php echo htmlspecialchars($linha['email']); ?></center></td>
          <td>
            <?php 
              $profile_photo_url = isset($linha['profile_picture_url']) ? trim($linha['profile_picture_url']) : '';

              // Só considera válido se não estiver vazio e o arquivo existir
              $has_photo = ($profile_photo_url !== '' && file_exists('../' . $profile_photo_url));
            ?>

            <center>
              <?php if ($has_photo): ?>
                <img 
                  class="profile-icon" 
                  id="main-profile-display"
                  src="<?php echo htmlspecialchars('../' . $profile_photo_url); ?>" 
                  alt="Foto de Perfil"
                >
              <?php else: ?>
                <img 
                  class="icon_option" 
                  id="main-profile-display"
                  src="../images/icons/icon_button.png" 
                  alt="Ícone Padrão"
                > 
              <?php endif; ?>
            </center>
          </td>
          <td>
            <center>
              <form method="GET" action="editarUsuario.php" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $linha['id']; ?>">
                <button class="edit-button" type="submit" title="Editar">
                  <i class='fa-solid fa-pen-to-square'></i>
                </button>
              </form>
            </center>
          </td>
          <td>
            <form method="POST" action="excluirUsuario.php" style="display:inline;">
              <center>
                <input type="hidden" name="id" value="<?php echo $linha['id']; ?>">
                <input class="delete-button" type="submit" value="X" 
                  onclick="return confirm('Tem certeza que deseja apagar o usuário <?php echo $linha['username']; ?>?');">
              </center>        
            </form>
          </td>
        </tr>
      <?php
        }
      } else {
        echo "<tr><td colspan='8'><center>Nenhum usuário encontrado com o termo '{$termo_busca}'.</center></td></tr>";
      }
      ?>
    </table>

    <center style="margin-top: 20px;">
      <p>
        <?php 
          echo "Página atual: {$page} de {$page_numbers}";
        ?>
      </p>
    </center>

    <center style="margin-top: 20px;">
      <p>
        <a class="link" href="?page=1">
          <button class="first-button" style="cursor: pointer;"><<</button>
        </a>
        
        <a class="link" href="?page=<?php 
        echo max(1, $page - 1); 
        ?>">
          <button class="left-button" style="cursor: pointer;">
            <
          </button>
        </a>
        
        <?php 
        $first_page = max($page - $page_interval, 1);
        $last_page = min($page_numbers, $page + $page_interval);
        
        // Exibe os números das páginas
        for ($i = $first_page; $i <= $last_page; $i++) {
          // Mantém o filtro de busca ao mudar de página
          $link_params = http_build_query(['page' => $i, 'busca_nome' => $termo_busca]);
          
          if ($i == $page) {
            echo "<button style=\"cursor: pointer;\" class='current-page'><strong>{$i}</strong></button>";
          } else {
            echo "<a href='?{$link_params}'><button style=\"cursor: pointer;\">{$i}</button></a> ";
          }
        }
        ?>
        
        <a class="link" href="?page=<?php 
        echo min($page_numbers, $page + 1);
        ?>">
          <button class="right-button" style="cursor: pointer;">
            >
          </button>
        </a>
        
        <a class="link" href="?page=<?php echo $page_numbers; ?>">
          <button class="last-button" style="cursor: pointer;">
            >>
          </button>
        </a>
      </p>
    </center>

    <br>
    <center>
      <a href="../index.php">RETORNAR À PÁGINA INICIAL</a>
    </center>
  </div>
  
</body>
</html>