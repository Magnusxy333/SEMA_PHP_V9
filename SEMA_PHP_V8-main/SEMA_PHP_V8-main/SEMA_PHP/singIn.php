<?php
	
	session_start();

	include 'server.php';
	
	$username = $_POST["txt_username"];
	$password = $_POST["txt_password"];	

    // A query agora PRECISA buscar a coluna 'profile_picture_url'
	$sql = mysql_query("SELECT id, username, profile_picture_url FROM tb_register WHERE username = '$username' and senha = '$password' ");

	if ($sql && mysql_num_rows($sql) > 0 ) {
	// Login bem-sucedido - definir variáveis de sessão
    $row = mysql_fetch_assoc($sql); 
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $row['id']; 
    
    // -----------------------------------------------------------------
    // ✅ CORREÇÃO DE COMPATIBILIDADE E LOGICA DA FOTO (SEM OPERADOR '?')
    // -----------------------------------------------------------------
    $photo_db_path = '';
    // Tenta obter o caminho do banco de dados
    if (isset($row['profile_picture_url'])) {
        $photo_db_path = $row['profile_picture_url'];
    }
    
    // Se houver um caminho válido no BD, usa ele. Se não, usa o padrão.
    if (!empty($photo_db_path)) {
        $_SESSION['profile_picture_url'] = $photo_db_path; // Caminho do usuário (ex: uploads/...)
    } else {
        $_SESSION['profile_picture_url'] = 'images/default-profile.png'; // Caminho padrão (relativo à raiz)
    }
    // -----------------------------------------------------------------
    
    // Redirecionar para a página principal
    header('Location: index.php');
    exit;	
	} else {
	echo "<center>";
	echo "<hr>";
	echo "Acesso negado";
	echo "<hr>";
	echo "<br>"; }
?>