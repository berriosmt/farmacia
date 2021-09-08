<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();
//borrar los productos del carrito
unset($_SESSION['cart']);
?>

<?=header_template('Farmacia San Blas - Mi cuenta')?>

<div class="section-2">
          <div class="container">
            <h1 class="heading-6">¡Orden completada!</h1>
</div>
</div>

</body>
</html>