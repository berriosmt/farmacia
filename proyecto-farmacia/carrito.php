<?php

session_start();
//incluir para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();

//verificar que el usuario haya iniciado sección
if (!isset($_SESSION['account_loggedin'])) {
  //si no, se dirige a la página de login  
  header('location: login.php');
}

if (isset($_SESSION['account_loggedin'])) {
  //seleccionar el id del usuario de la tabla usuarios
  $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
  $stmt->execute([ $_SESSION['account_id'] ]);
  //colocar el resultado en una variable
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
}
//colocar en una varible el id del usuario
$account_id = $_SESSION['account_id'];



//eliminar el producto del carrito si se da click en el botón
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    
    unset($_SESSION['cart'][$_GET['remove']]);
}


//variables 
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
$totalIvu = 0.00;
$ivu = 0.00;

//verificar que hay productos en en carrito
if ($products_in_cart) {
    //permitir que se use ? para el query 
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    //seleccionar los productos que están en el carrito de la base de datos
    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id IN (' . $array_to_question_marks . ')');
    //seleccionar el id del producto
    $stmt->execute(array_keys($products_in_cart));
    //colocar el resultado en una variable
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //calcular el total
    foreach ($products as $product) {
        $subtotal += (float)$product['precio'] * (int)$products_in_cart[$product['id']];
    }
    //calcular el total con ivu
    $totalIvu = round(($subtotal * .115)+ $subtotal, 2);
    $ivu = round($subtotal * .115, 2); 
}
//Paypal sandbox (modo prueba)
$testmode = true;
$paypalurl = $testmode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
//si se da click en el botón para pagar con Paypal
if (isset($_POST['paypal']) && $products_in_cart && !empty($products_in_cart)) {
   //variables que se pasan a Paypal
    $data = array(
        'cmd'			=> '_cart',
        'upload'        => '1',
        'custom'        => $account_id,
        'lc'			=> 'EN',
        'business' 		=> 'berrios.mt@gmail.com',
        'cancel_return'	=> 'https://farmacia-proyecto.herokuapp.com/carrito.php',
        'notify_url'	=> 'https://farmacia-proyecto.herokuapp.com/carrito.php?ipn_listener=paypal',
        'currency_code'	=> 'USD',
        'return'        => 'https://farmacia-proyecto.herokuapp.com/orden.php'
    );
    //añadir los productos que están en el carrito de compras
    for ($i = 0; $i < count($products); $i++) {
        $data['item_number_' . ($i+1)] = $products[$i]['id'];
        $data['item_name_' . ($i+1)] = $products[$i]['nombre'];
        $data['quantity_' . ($i+1)] = $products_in_cart[$products[$i]['id']];
        $data['amount_' . ($i+1)] = $products[$i]['precio'];
        $data['tax_' . ($i+1)] = $ivu;
    }
    //enviar al usuario a la página de paypal para pagar
    header('location:' . $paypalurl . '?' . http_build_query($data));
    exit;
}
//el listener de paypal
    if (isset($_GET['ipn_listener']) && $_GET['ipn_listener'] == 'paypal') {
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
      
        $req = 'cmd=_notify-validate';

            foreach ($myPost as $key => $value) {
                $value = urlencode($value);
                $req .= "&$key=$value";
            }
        //verificar que el input es correcto para hacer transacción
        $ch = curl_init($paypalurl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $res = curl_exec($ch);
        curl_close($ch);
        //si se verifica la transacción
        if (strcmp($res, 'VERIFIED') == 0) {
            $item_id = array();
            $item_quantity = array();
            $item_mc_gross = array();
            $item_name = array();
            //añadir información de los productos
            for ($i = 1; $i < ($_POST['num_cart_items']+1); $i++) {
                array_push($item_id, $_POST['item_number' . $i]);
                array_push($item_name, $_POST['item_name' . $i]);
                array_push($item_quantity, $_POST['quantity' . $i]);
                array_push($item_mc_gross, $_POST['mc_gross_' . $i]);
            }
            //insertar información trasacción a la tabla transactions
            $stmt = $pdo->prepare('INSERT INTO transactions VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE payment_status = VALUES(payment_status)');
            $stmt->execute([
                NULL,
                $_POST['txn_id'],
                $_POST['mc_gross'],
                $_POST['payment_status'],
                implode(',', $item_id),
                implode(',', $item_name),
                implode(',', $item_quantity),
                implode(',', $item_mc_gross),
                date('Y-m-d H:i:s'),
                $_POST['payer_email'],
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['address_street'],
                $_POST['address_city'],
                $_POST['address_state'],
                $_POST['address_zip'],
                $_POST['address_country'],
                $_POST['custom'] 
            ]);
            
        }
        exit;
        
    }
?>
<?=header_template('Farmacia San Blas - Carrito')?>

<div class="carrito-section">
  <?php $totalIvu = round(($subtotal * .115)+ $subtotal, 2);?>
    <h2 class="heading-2">Carrito de compras</h2>
    <div class="container-5 container">
      <div class="productos-compra">
      <?php if (empty($products)): ?>
                
        <h3 style="text-align: center;">Todavía no hay productos en el carrito.</h3>
        
        <?php else: ?>
                <?php 
                  //mostrar productos en el carrito
                  foreach ($products as $product): ?>

        <div class="producto-orden">
          <img src="<?=$product['imagen']?>" loading="lazy" alt="" class="img-product-cart">
          <div class="cantidad-label"><?=$products_in_cart[$product['id']]?></div>
          <div class="precio-label">&dollar;<?=$product['precio']?></div>
          <a href="carrito.php?remove=<?=$product['id']?>" class="x-btn inline-block">
          <img src="images/x-icon.svg" loading="lazy" alt="" class="image-10"></a>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>


      </div>
      <div id="carrito-grid-1" class="pagar-orden">
        <h3 class="heading-8">Total</h3>
        <div class="layout-grid calculo-orden">
          <div id="carrito-grid-2" class="labels">
            <div class="label-text">Orden</div>
            <div class="label-text">IVU</div>
            <div class="label-text">Total</div>
          </div>
          <div class="orden-num">
            <div class="orden-num label-text">&dollar;<?=$subtotal?></div>
            <div class="ivu-num label-text">11.5%</div>
            <div class="total-num label-text">&dollar;<?=$totalIvu?></div>
          </div>
          <div class="info-section">
          <div class="form-block form">
          <form method="post" action="">
          <input type="hidden" name="custom" value="<?php echo $account_id; ?>">
          <input type="submit" value="Pagar con PayPal" name="paypal" class="btn-pagar button">
          </form>
                </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
