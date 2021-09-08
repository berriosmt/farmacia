<?php
session_start();
//incluir funciones.php para conectarse a la base de datos
include 'functions.php';
$pdo = conexion();
//verificar que el usuario es administrador
if (isset($_SESSION['account_loggedin']) && $_SESSION['account_admin']) {
    //obtener los usuarios de la base de datos
    $stmt = $pdo->prepare('SELECT * FROM usuarios');
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $this->SetFont('Arial','B',14);
    // Movernos a la derecha
    $this->Cell(60);
    // Título
    $this->Cell(70,10,'Reporte de usuarios',0,0,'C');
    // Salto de línea
    $this->Ln(20);


    $this->Cell(100,10, utf8_decode('Correo electrónico') , 1, 1, 'C', 0);
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

$stmt = $pdo->prepare('SELECT * FROM usuarios');
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$pdf-> AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',14);
//mostrar usuarios
foreach ($usuarios as $usuario):
$pdf->Cell(100,10, $usuario['email'], 1, 1, 'C', 0);

endforeach;
$pdf->Output();
?>