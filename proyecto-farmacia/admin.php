<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();
//verificar que el usuario es administrador
if (isset($_SESSION['account_loggedin']) && $_SESSION['account_admin']) {
    //obtener las órdenes que se han hecho
    $stmt = $pdo->prepare('SELECT * FROM transactions');
    $stmt->execute();
    $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 else{
     header('location: adminLogin.php');
 }


?>
<?=admin_header('Farmacia San Blas - Admin')?>

<div class="section">
    <div class="container">
      <h2 class="heading">Órdenes</h2>
    </div>
    <!-- mostrar tabla con las órdenes -->
    <table>
        <thead>
            <tr>
                <td>ID transacción</td>
                <td>Total</td>
                <td>Fecha</td>
                <td>Producto</td>
                <td>Cantidad</td>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ordenes)): ?>
            <tr>
                <td colspan="5" style="text-align:center;">No hay órdenes recientes</td>
            </tr>
            <?php else: ?>
            <?php foreach ($ordenes as $orden): ?>
            <tr>
            
                <td><?=$orden['txn_id']?></td>
                <td>&dollar;<?=$orden['payment_amount']?></td>
                <td><?=$orden['created']?></td>
                <td><?=$orden['item_name']?></td>
                <td><?=$orden['item_quantity']?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>