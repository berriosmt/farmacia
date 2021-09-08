<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
//si inició sección
if (isset($_SESSION['account_loggedin'])) {
    //borrar la secciones
    unset($_SESSION['account_loggedin']);
    unset($_SESSION['account_id']);
    unset($_SESSION['account_admin']);
}
unset($_SESSION['cart']);
//enviar a la página de inicio
header('location: index.php');
?>