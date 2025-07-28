<?php
/* ASCII constants */
const ESC = "\x1b";
const GS  = "\x1d";
const NUL = "\x00";

// Define ruta raíz manualmente
define('PROYECTO_PATH', '/var/www/html/comedor/');

if (defined('STDIN')) {
    $nroTicket = $argv[1];
} else {
    $nroTicket = $_GET['nroticket'];
}

// Log de ejecución
file_put_contents(PROYECTO_PATH . 'writable/logs/imprimir_debug.log', "Ejecutado imprimir.php con ticket: {$nroTicket}\n", FILE_APPEND);

// Conexión a MySQL
$servername = "mysqlpro";
$username = "sistemas";
$password = "Focus@18";
$dbname   = "comedor";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query  = "SELECT his.id_historial AS ticket, leg.apnombre AS nombre, fecha, hora, dep.vianda, emp.razonsocial AS empresa ";
$query .= "FROM historial AS his ";
$query .= "INNER JOIN empresas AS emp ON his.id_empresa = emp.id_empresa ";
$query .= "INNER JOIN legajos AS leg ON his.id_legajo = leg.id_legajo ";
$query .= "INNER JOIN departamentos AS dep ON leg.id_departamento = dep.id_departamento ";
$query .= "WHERE id_historial = '".$nroTicket."'";

$result = $conn->query($query);

// Generar contenido
ob_start();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo ESC."@";
        echo ESC."M".chr(2);
        echo ESC."a".chr(1);
        echo ESC."E".chr(1);
        echo ESC."!".chr(50);
        echo "NUCLEOELECTRICA\n";
        echo "ARGENTINA S.A.\n";
        echo ESC."!".chr(0);
        echo ESC."E".chr(0);
        echo ESC."d".chr(1);
        echo ESC."!".chr(20);
        echo "TICKET COMEDOR NRO:\n";
        echo ESC."d".chr(1);
        echo ESC."!".chr(50);
        echo sprintf("%05d", $row['ticket']) . "\n";
        echo ESC."d".chr(1);
        echo ESC."!".chr(0);
        echo "Empresa: ".$row['empresa']."\n";
        echo ESC."d".chr(1);
        echo "Persona: ".$row['nombre']."\n";
        echo ESC."d".chr(1);
        echo "Hora Ingreso: ".$row['hora']."\n";
        echo ESC."d".chr(1);
        echo ESC."!".chr(30);
        echo "Ticket Válido solo para día:\n";
        echo $row['fecha']."\n";
        echo GS."V\x41".chr(3);
    }
}

$conn->close();
unset($_GET['nroticket']);
unset($nroTicket);

// Enviar al puerto y guardar log del contenido generado
$output = ob_get_clean();
file_put_contents(PROYECTO_PATH . 'writable/logs/php_output_raw.log', $output);
echo $output;

file_put_contents(PROYECTO_PATH . 'writable/logs/php_trace.log', "? imprimir.php ejecutado con ticket {$nroTicket}\n", FILE_APPEND);
exit(0);
?>