<?php

namespace App\Controllers;

use App\Models\Ticket_Model;
use CodeIgniter\Controller;

// Asegurate de configurar correctamente esta ruta o usar Composer autoload si lo preferís
require_once APPPATH . 'ThirdParty/fpdf/pdf_js.php';

class Ticket extends Controller
{
    public function generar($nroticket = null)
    {
        if (!$nroticket) {
            return 'Ticket no especificado';
        }

        $modelo = new Ticket_Model();
        $data = $modelo->get_datos($nroticket);

        if (!$data) {
            return 'Datos del ticket no encontrados';
        }

        $pdf = new \PDF_AutoPrint('P', 'cm', [8, 8]);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Text(0.8, 1, 'Nucleoelectrica Argentina S.A');
        $pdf->Text(2.5, 2, 'TICKET NRO:');

        $pdf->SetFont('Arial', 'B', 40);
        $pdf->Text(2.1, 3.5, str_pad($nroticket, 5, "0", STR_PAD_LEFT));

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Text(0.5, 4.4, 'Empresa: ' . $data['empresa']);
        $pdf->Text(0.5, 5.1, 'Nombre: ' . $data['nombre']);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Text(1.3, 6.3, 'Ticket válido solo para día');

        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Text(1.8, 7.4, date('d/m/Y'));

        $pdf->AutoPrint();
        $pdf->Output(); // Mostrar en navegador
    }
}