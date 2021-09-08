<?php
//conexión con la base de datos
function conexion() {
  //base de datos remota
  // $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
  // $cleardb_server = $cleardb_url["host"];
  // $cleardb_username = $cleardb_url["user"];
  // $cleardb_password = $cleardb_url["pass"];
  // $cleardb_db = substr($cleardb_url["path"],1);
  // $active_group = 'default';
  // $query_builder = TRUE;
// Connect to DB
//  try {
//     	return new PDO('mysql:host=' . $cleardb_server . ';dbname=' . $cleardb_db . ';charset=utf8', $cleardb_username, $cleardb_password);
//     } catch (PDOException $exception) {
//     	//mensaje de error si no hay conexión con la base de datos
//     	die ('No se pudo conectar a la base de datos.');
//     }
//base de datos local
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'root';
  $DATABASE_PASS = '';
  $DATABASE_NAME = 'farmacia_sanblas';
  try {
    return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
  } catch (PDOException $exception) {
   //mensaje de error si no hay conexión con la base de datos
    die ('No se pudo conectar a la base de datos.');
  }
}
  




//header de todas las páginas
function header_template($title) {
    //número de productos en el carrito
    $num_carrito = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
   
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        
        <link href="css/elementos.css" rel="stylesheet" type="text/css">
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <link href="css/tablas.css" rel="stylesheet" type="text/css">
        
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
        <title>$title</title>
        </head>
        <body class="body">
        <div class="navbar navbar-mobile nav">
        <div class="nav-container container">
          <a href="index.php" class="brand nav-brand"><img src="images/logoSintitulo.svg" loading="lazy" width="64" alt="" class="logo-sintitulo">
          <img src="images/logoConTitulo.svg" loading="lazy" width="290" alt="logo farmacia" class="image"></a>
          
          <div class="searchbar">
          <form action="busqueda.php" class="clearfix form" method="get">
          <input type="search" class="search-input input" maxlength="256" id= "buscar" name="buscar" placeholder="Buscar producto" required="">
          <input type="submit" value="search" class="search-button button">
          </form>
        </div>
          <div class="menu-mobile">
            <a href="productos.php" class="menu-link nav-link">Productos</a>
          </div>
          <nav role="navigation" class="nav-menu">
            <a href="productos.php" class="menu-link nav-link">Productos</a>
          </nav>
          <div class="nav-icons">
            <a href="carrito.php" class="nav-link">
            <img src="images/shopping-cart.svg" loading="lazy" width="29" alt="carrito nav" class="icons-nav">
            <span>$num_carrito</span>
            </a>
            <a href="micuenta.php" class="nav-link"><img src="images/account-icon.svg" loading="lazy" width="29" alt="cuenta icon" class="icons-nav account"></a>
          </div>
        </div>
        <div class="searchbar searchbar-mobile">
          <form action="busqueda.php" class="clearfix form" method="get">
          <input type="search" class="search-input input" maxlength="256" id="buscar "name="buscar" placeholder="Buscar producto" id="search" required="">
          <input type="submit" value="search" class="search-button button" name="submit">
          </form>
        </div>
      </div>
    EOT;
    }

// Template footer
function footer_template() {
    $year = date('Y');
    echo <<<EOT
            
            <footer class="footer">
          <div class="layout-grid grid-5">
          <p id="footer-grid-1" class="footer-p"><strong>Dirección</strong><br>Calle Bobby Capó <br>Esquina Baldorioty, <br>Coamo, Puerto Rico</p>
          <p id="footer-grid-2" class="footer-p"><strong>Teléfono</strong><br>787-825-2228 <br> 787-825-1285</p>
          <p id="footer-grid-3" class="footer-p"><strong>Horario</strong><br>Lunes a Sábado<br> 8:00 a.m - 8:00 p.m</p><img src="images/logoConTitulo.svg" loading="lazy" width="262" id="area" alt="logo farmacia" class="image-5">
          <h6 id="footer-grid-4" class="heading-5">© $year Farmacia San Blas</h6>
        </div>
            </footer>
            
        </body>
    </html>
    EOT;
    }

  //template admin
  function admin_header($title){
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/elementos.css" rel="stylesheet" type="text/css">
        <link href="css/admin.css" rel="stylesheet" type="text/css">
        <link href="css/tablas.css" rel="stylesheet" type="text/css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
        <title>$title</title>
        </head>
        <body class="body">
        <div data-collapse="medium" data-animation="default" data-duration="400" role="banner" class="navbar nav">
        <div class="admin-container container">
        <a href="#" class="nav-brand"><img src="images/logoSintitulo.svg" loading="lazy" width="66" alt="" class="image"></a>
        <nav role="navigation" class="nav-link nav-menu">
        <a href="admin.php" class="admin-navlinks nav-link">Órdenes</a>
        <a href="reportes.php" class="admin-navlinks nav-link">Reportes</a>
        <a href="cerrarSeccion.php" class="admin-navlinks btn-nav nav-link">Cerrar Sección</a>
        </nav>
        </div>
        </div>
           
    EOT;
  }
    ?>

