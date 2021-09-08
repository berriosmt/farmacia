<?php

session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();


$error = '';

//colocar lo que el usuario ingresó en una variable
$search = $_GET['buscar'] ;
//obtener los productos que tengan el nombre que el usuario ingresó
$stmt = $pdo->prepare('SELECT * FROM productos WHERE nombre LIKE ?');
$stmt->execute(["%" .$search . "%"]);
//colocar resultado en una variable
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
//si no hay productos con ese nombre, se muestra mensaje
if ($products==0){
   $error = "No hay productos con su busqueda.";
}



?>

<?=header_template('Farmacia San Blas - Productos')?>

<div class="section-2">
          <div class="container">
            <h1 class="heading-6">Productos</h1>

           

                <?php if ($error): 
                  //si no ha productos  ?>
              <h3 style="text-align: center;"><?=$error?></h3>

              <?php else: ?>


            <div class="layout-grid grid-7">
            <?php 
            //mostrar productos
            foreach ($products as $product): ?>
            
             <div class="producto-card">
             <form action="productos.php&id=<?=$product['id']?>" method="post">
               <div class="producto-img"><img src="<?=$product['imagen']?>" loading="lazy" width="184" alt="" class="image-3"></div>
               <h4 class="heading-3"><?=$product['nombre']?></h4>
               <h4 class="heading-4">&dollar;<?=$product['precio']?></h4>
               <input type="hidden" name="quantity" value="1" min="1" max="<?=$product['cantidad']?>" placeholder="" required>
               <input type="hidden" name="product_id" value="<?=$product['id']?>">
               <input class="btn btn-carrito button" type="submit" value="Añadir">
               </form>
             </div>
           <?php endforeach; ?>
           <?php endif; ?>
            </div>
          </div>
        </div>

<?=footer_template('Farmacia San Blas - Productos')?>