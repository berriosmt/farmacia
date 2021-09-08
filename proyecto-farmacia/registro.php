<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();

$register_error = '';
//si se da click en le botón de registrarse
if (isset($_POST['register'], $_POST['email'], $_POST['password'], $_POST['dpassword']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    //verificar si la cuenta ya existe
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account) {
        //si la cuenta existe se muestra mensaje de error
        $register_error = 'Ya existe una cuenta con este email.';
        //verificar que las contraseñas ingresadas son iguales
    } else if ($_POST['dpassword'] != $_POST['password']) {
        $register_error = 'Las contraseñas no son iguales!';
        //verificar que la contraseña sea de 5 a 20 caracteres
    } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        $register_error = 'La contraseña debe ser entre 5 a 20 caracteres.';
    } else {
        //si la cuenta no existe, se crea
        $stmt = $pdo->prepare('INSERT INTO usuarios (email, password) VALUES (?,?)');
        //ocultar la contraseña en la base de datos
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->execute([ $_POST['email'], $password ]);
        $account_id = $pdo->lastInsertId();
        //iniciar sección automaticamente
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account_id;
        $_SESSION['account_admin'] = 0;
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        if ($products_in_cart) {
            //si tiene productos en el carrito
            header('location: carrito.php');
        } else {
            //ver la cuenta
            header('location: micuenta.php');
        }
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/elementos.css" rel="stylesheet" type="text/css">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <title>Farmacia san Blas - Registrarse</title>
</head>
<body class="body">
    <div class="login-card">
        <div class="container-3 container"><img src="images/logoConTitulo.svg" loading="lazy" alt="logo farmacia" class="image-6"></div>
        <h2 class="heading-7">Registrarse</h2>
        <div class="form">
          <div class="alert"></div>
          <form id="email-form" name="email-form" data-name="Email Form" method="POST" action="registro.php">
            <input type="email" class="textfield input" maxlength="256" name="email" data-name="Name" placeholder="Correo electrónico" id="name">
            <input type="password" class="textfield input" maxlength="256" name="password" data-name="Email" placeholder="Contraseña" id="email" required="">
            <input type="password" class="textfield input" maxlength="256" name="dpassword" data-name="Field" placeholder="Confirmar contraseña" id="field" required="">
            <input type="submit" name="register" value="Registrarse" class="btn btn-login button">
            <a href="login.php" class="link">¿Ya tienes una cuenta?</a>
          </form>

          <?php 
          //si hay errores, se muestran
          if ($register_error): ?>
            <p class="error"><?=$register_error?></p>
            <?php endif; ?>

        </div>
      </div>

    
</body>
</html>







