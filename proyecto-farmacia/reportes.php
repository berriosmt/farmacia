<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();
//verificar que el usuario es administrador
if (isset($_SESSION['account_loggedin']) && $_SESSION['account_admin']) {
    //obtener los órdenes que se han hecho
  $stmt = $pdo->prepare('SELECT * FROM transactions');
  $stmt->execute();
  $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else{
  //enviar al login de administrador
   header('location: adminLogin.php');
}

?>

<?=admin_header('Farmacia San Blas - Admin')?>
<div class="section">
    <div class="container">
      <h2 class="heading">Reportes</h2>
      <a href="reporteVentas.php" class="btn button">Reporte de ventas</a>
      <a href="reporteUsuarios.php" class="btn button">Reporte de usuarios</a>
    </div>
