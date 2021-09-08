<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();

$error = '';
//si se da click en el botón de iniciar sección
if (isset($_POST['login'], $_POST['email'], $_POST['password']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    //verificar que la cuenta existe
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    //verificar la contraseña
    if ($account && password_verify($_POST['password'], $account['password'])) {
        //si todo está bien, se inicia sección y se crean las secciones
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account['id'];
        $_SESSION['account_admin'] = $account['admin'];
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if ($products_in_cart) {
            //si el usuario tiene productos en el carrito
            header('location: carrito.php');
        } else {
            //ver la cuenta
            header('location: micuenta.php');
        }
        exit;
    } else {
        //mensaje de error
        $error = 'Correo electrónico o contraseña incorrectos.';
    }
}

?>


<!DOCTYPE html>
    <html>
        <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/elementos.css" rel="stylesheet" type="text/css">
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
        <title>Login</title>
        </head>

    <body class="body">
    <div class="login-card">
        <div class="container-3 container"><img src="images/logoConTitulo.svg" loading="lazy" alt="logo farmacia" class="image-6"></div>
        <h2 class="heading-7">Login</h2>
        <div class="form">
          <div class="alert"></div>
          <form id="email-form" name="email-form" data-name="Email Form" method="POST" action="login.php">
            <input type="email" class="textfield input" maxlength="256" name="email" data-name="Name" placeholder="Correo electrónico" id="name">
            <input type="password" class="textfield input" maxlength="256" name="password" data-name="Email" placeholder="Contraseña" id="email" required="">
        
            <input type="submit" name="login" value="Iniciar Sección" class="btn btn-login button">
            <a href="registro.php" class="link">¿No tienes cuenta?</a>
          </form>

          <?php if ($error): ?>
            <p class="error"><?=$error?></p>
            <?php endif; ?>

        </div>
      </div>

    </body>
    </html>



