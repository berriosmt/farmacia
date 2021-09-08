<?php

session_start();
//incluir para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();

//obtener los productos de la base de datos
$stmt = $pdo->prepare('SELECT * FROM productos');
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

//cuando se da click en el botón del carrito se valida la información
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    //colocar en varibles lo que se recibe
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    //seleccionar el producto con el id que se obtuvo de la base de datos
    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
    $stmt->execute([$_POST['product_id']]);
    //se coloca en una variable el resultado del query
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    //verificar que la varaible no está vacía
    if ($product && $quantity > 0) {
        //crear la sección para el carrito si el producto existe
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                //si el producto ya se añadió al carrito se actualiza la cantidad
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                //si el producto no está en el carrito se añade
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            //añadir el primero producto si no hay ya en el carrito
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    //redirecciona a la misma página
    header('location: productos.php');
    exit;
}
?>

<?=header_template('Farmacia San Blas - Productos')?>

<div class="section-2">
          <div class="container">
            <h1 class="heading-6">Productos</h1>

            



            <div class="layout-grid grid-7">
            <?php foreach ($products as $product): ?>
             

             <div class="producto-card">
             <form action="productos.php?id=<?=$product['id']?>" method="post">
               <div class="producto-img"><img src="<?=$product['imagen']?>" loading="lazy" width="184" alt="" class="image-3"></div>
               <h4 class="heading-3"><?=$product['nombre']?></h4>
               <h4 class="heading-4">&dollar;<?=$product['precio']?></h4>
               <input type="hidden" name="quantity" value="1" min="1" max="<?=$product['cantidad']?>" placeholder="" required>
               <input type="hidden" name="product_id" value="<?=$product['id']?>">
               <input class="btn btn-carrito button" type="submit" value="Añadir">
               </form>
             </div>
           <?php endforeach; ?>
              
            </div>
          </div>
        </div>

<?=footer_template('Farmacia San Blas - Productos')?>