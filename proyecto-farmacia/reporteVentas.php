<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();
//verificar que el usuario es administrador
if (isset($_SESSION['account_loggedin']) && $_SESSION['account_admin']) {
    
    $stmt = $pdo->prepare('SELECT * FROM transactions');
    $stmt->execute();
    $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 else{
     header('location: adminLogin.php');
 }
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('images/logoConTitulo.png',10,10,50);
    // Arial bold 15
    $this->SetFont('Arial','B',12);
    // Movernos a la derecha
    $this->Cell(60);
    // Título
    $this->Cell(70,10,'Reporte de ventas',0,0,'C');
    // Salto de línea
    $this->Ln(20);

    //nombre de las columnas
    $this->Cell(55,10, utf8_decode('ID Transacción') , 1, 0, 'C', 0);
    $this->Cell(25,10, 'Total' , 1, 0, 'C', 0);
    $this->Cell(45,10, 'Fecha' , 1, 0, 'C', 0);
    $this->Cell(45,10, 'Producto' , 1, 0, 'C', 0);
    $this->Cell(20,10, 'Cantidad' , 1, 1, 'C', 0);

}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
}

$stmt = $pdo->prepare('SELECT * FROM transactions');
$stmt->execute();
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$pdf-> AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
//mostrar las órdenes
foreach ($ordenes as $orden):
$pdf->Cell(55,10, $orden['txn_id'], 1, 0, 'C', 0);
$pdf->Cell(25,10, $orden['payment_amount'], 1, 0, 'C', 0);
$pdf->Cell(45,10, $orden['created'], 1, 0, 'C', 0);
$pdf->Cell(45,10, $orden['item_name'], 1, 0, 'C', 0);
$pdf->Cell(20,10, $orden['item_quantity'], 1, 1, 'C', 0);
endforeach;
$pdf->Output();
?>