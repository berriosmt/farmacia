<?php
session_start();
//incluir para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();

?>

<?php
//query para obtener los productos
$stmt = $pdo->prepare('SELECT * FROM productos LIMIT 3');
$stmt->execute();
$productos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
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
    header('location: index.php');
    exit;
}
?>

<?=header_template('Farmacia San Blas')?>

<section class="presentacion">
          <div class="layout-grid presentacion-grid">
            <div class="info">
              <h1 class="heading">Tu farmacia favorita<br>ahora en línea</h1>
              <p class="paragraph">Ahorra tiempo comprando los artículos que necesitas <br>desde tu dispositivo y recogelos en la farmacia.</p>
              <a href="productos.php" class="btn button">Empezar a comprar</a>
            </div><img src="images/home-img-comp.svg" loading="lazy" alt="" class="image-9">
          </div>
        </section>
        <section class="explicacion">
          <h2 class="heading-2">¿Cómo funciona?</h2>
          <div class="container-2 container">
            <div class="layout-grid grid-2">
              <div class="card-explicacion"><img src="images/shopping-cart-plus.svg" loading="lazy" alt="" class="icon-explicacion">
                <p class="p-explicacion"> Elige los productos que necesitas.</p>
              </div>
              <div class="card-explicacion"><img src="images/online-shopping-pay.svg" loading="lazy" alt="" class="icon-explicacion">
                <p class="p-explicacion">Realiza el pago <br>con tu tarjeta.</p>
              </div>
              <div class="card-explicacion"><img src="images/store.svg" loading="lazy" alt="" class="icon-explicacion">
                <p class="p-explicacion">Recoge tu orden<br>en la farmacia.</p>
              </div>
            </div>
          </div>
        </section>
        <section class="productos-destacados">
          <h2 class="heading-2">Productos destacados</h2>
          <div class="productos-con container">
            <div class="layout-grid grid-4">

            <?php 
            //mostrar los productos en la página
            foreach ($productos_recientes as $product): ?>
             

              <div class="producto-card">
              <form action="index.php?&id=<?=$product['id']?>" method="post">
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
        </section>

        

<?=footer_template('Home')?>