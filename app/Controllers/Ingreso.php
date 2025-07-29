<?php

namespace App\Controllers;
use App\Models\Ingreso_Model;
use CodeIgniter\Controller;

class Ingreso extends Controller
{
    public function index()
{
    $raw_ip = shell_exec("hostname -I");

    if (!empty($raw_ip)) {
        $server_ip = trim(explode(' ', $raw_ip)[0]);
    } else {
        $server_ip = 'IP no detectada';
    }

    $data['server_ip'] = $server_ip;

    helper('form');
    return view('frontend/ingreso', $data);
}

    public function validar()
{
    log_message('debug', '>>> Entrando al m�todo validar()');

    $request = service('request');
    $nro_tarjeta = $request->getPost('tarjeta');
    
        log_message('debug', ">>> Entrando al m�todo validar()");
        log_message('debug', "N�mero de tarjeta recibido: '$nro_tarjeta'");

    if (empty($nro_tarjeta)) {
            log_message('debug', 'Par�metro tarjeta ausente');
            return $this->response->setJSON([
                'result' => mb_convert_encoding('Par�metro tarjeta ausente', 'UTF-8', 'auto'),
                'error' => 1
            ]);
        }

    if ($nro_tarjeta) {
        $modelo = new Ingreso_Model();
        log_message('debug', 'Modelo Ingreso_Model instanciado');

        if (!$modelo->check_tarjeta($nro_tarjeta) || $nro_tarjeta < 1000) {
            log_message('debug', 'Tarjeta inv�lida o fuera de rango');
            return $this->response->setJSON([
                'result' => 'La tarjeta no se encuentra dada de alta',
                'error' => 1
            ]);
        }

        $legajo = $modelo->get_legajo($nro_tarjeta);
        log_message('debug', 'Legajo obtenido: ' . var_export($legajo, true));

        if (isset($legajo['id_estado']) && $legajo['id_estado'] == 1) {
            log_message('debug', 'Legajo habilitado');

            if ($modelo->validar($nro_tarjeta)) {
                log_message('debug', 'Validaci�n horaria OK');

                if (!$modelo->check_repetido($nro_tarjeta)) {
                    log_message('debug', 'No se detect� ingreso duplicado');

                    $query = $modelo->add_historial($nro_tarjeta);
                    log_message('debug', 'Historial agregado con ID: ' . $query);

                    if ($query) {
                        return $this->response->setJSON([
                            'matricula' => $legajo['matricula'],
                            'nombre' => $legajo['apnombre'],
                            'error' => 0,
                            'id_ticket' => $query
                        ]);
                    }
                } else {
                    log_message('debug', 'Ingreso duplicado detectado');
                    return $this->response->setJSON([
                        'result' => 'Ha excedido la cantidad de ingresos diarios',
                        'error' => 1
                    ]);
                }
            } else {
                log_message('debug', 'Fuera de horario');
                return $this->response->setJSON([
                    'result' => 'Usted se encuentra Fuera de Horario',
                    'error' => 1
                ]);
            }
        } else {
            log_message('debug', 'Legajo no habilitado o fuera de horario');
            return $this->response->setJSON([
                'result' => 'Usuario no Habilitado o Fuera de Horario',
                'error' => 1
            ]);
        }
    }

    log_message('debug', 'Parámetro tarjeta ausente');
    return $this->response->setJSON([
        'result' => 'Parámetro tarjeta ausente',
        'error' => 1
    ]);
}

    public function generarTicket($nroticket = null)
{
log_message('debug', '?? Voy a escribir probe.log ahora');
file_put_contents(ROOTPATH . 'writable/logs/probe.log', "?? Entr� a generarTicket\n", FILE_APPEND);

    helper('filesystem'); // Por si el proyecto lo necesita

    if ($nroticket) {
        $logPath = ROOTPATH . 'writable/logs/imprimir_debug.log';

        // Log: ingreso al m�todo
        file_put_contents($logPath, "?? [generarTicket] Ticket recibido: {$nroticket}\n", FILE_APPEND);

        // Armado del comando
        $scriptPath = '/var/www/html/comedor/print.sh';
        $comando = escapeshellcmd("bash $scriptPath $nroticket");

        // Log: comando armado
        file_put_contents($logPath, "?? [generarTicket] Ejecutando: $comando\n", FILE_APPEND);

        // Ejecutar el comando
        $salida = shell_exec($comando);

        // Log: resultado del comando
        file_put_contents($logPath, "?? [generarTicket] Resultado:\n$salida\n", FILE_APPEND);

        return $salida;
    } else {
        file_put_contents(ROOTPATH . 'writable/logs/imprimir_debug.log', "?? [generarTicket] No se recibi� nroticket\n", FILE_APPEND);
        return "Ticket inválido";
    }
}
}