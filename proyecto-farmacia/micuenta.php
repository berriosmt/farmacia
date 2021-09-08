<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();
//si el usuario no ha iniciado sección, se envía al login
if (!isset($_SESSION['account_loggedin'])) {
    header('location: login.php');
}
//si ha iniciado sección
if (isset($_SESSION['account_loggedin'])) {
    //obtener las compras que ha hecho el usuario usando su id
    $stmt = $pdo->prepare('SELECT * FROM transactions WHERE id_usuario = ?');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<?=header_template('Farmacia San Blas - Mi cuenta')?>
    
<div class="section-2">
          <div class="container">
            <h1 class="heading-6">Mi cuenta</h1>

        
    <!-- tabla para mostrar las órdenes -->
    <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Cantidad</td>
                <td>Fecha</td>
                <td>Producto</td>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
            <tr>
                <td colspan="4" style="text-align:center;">No tienes órdenes recientes</td>
            </tr>
            <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
            
                <td><?=$transaction['txn_id']?></td>
                <td>&dollar;<?=$transaction['payment_amount']?></td>
                <td><?=$transaction['created']?></td>
                <td><?=$transaction['item_name']?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
     <!-- cerrar sección -->
    <a href="cerrarSeccion.php" class="btn btn-logout button">Cerrar sección</a>
        
    </div>
    </div>
</div>
    
<?=header_template('Farmacia San Blas - Mi cuenta')?>