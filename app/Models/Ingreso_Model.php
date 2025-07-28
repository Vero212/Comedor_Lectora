<?php

namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class Ingreso_Model extends Model
{
    protected $db;

    public function __construct(ConnectionInterface &$db = null)
    {
        parent::__construct($db);
        $this->db = \Config\Database::connect();
    }

    public function obtener_ip(): string
{
        // Detectar sistema operativo
        $os = strtoupper(PHP_OS);

        if (str_starts_with($os, 'WIN')) {
            // Windows: usar ipconfig y parsear la IP IPv4
            $output = shell_exec('ipconfig');
            preg_match('/IPv4.*?:\s*([0-9\.]+)/', $output, $matches);
            $ip = $matches[1] ?? '127.0.0.1';
        } else {
            // Linux: usar hostname -I y tomar la primera IP
            $output = shell_exec('hostname -I');
            $ips = explode(' ', trim($output));
            $ip = $ips[0] ?? '127.0.0.1';
        }

        // Para debug: registrar IP detectada
        log_message('debug', 'IP detectada: ' . $ip);

        // Retornar solo los dos primeros bloques (ej: 192.168)
        $partes = explode('.', $ip);
        return (count($partes) >= 2) ? "{$partes[0]}.{$partes[1]}" : '127.0';
    }

    public function validar($nroTarjeta): bool
    {
        $iplectora = $this->obtener_ip();
        $dia = $this->get_dia_spanish(date('w'));

        $sql = "SELECT leg.apnombre AS nombre, est.descripcion AS estado 
                FROM legajos AS leg 
                INNER JOIN calendario AS cal ON leg.id_calendario = cal.id_calendario 
                INNER JOIN estados AS est ON leg.id_estado = est.id_estado 
                INNER JOIN comedores AS com ON leg.id_comedor = com.id_comedor 
                WHERE leg.id_estado = 1 
                AND leg.nro_tarjeta = ? 
                AND cal.{$dia} = true 
                AND com.ip_comedor = ? 
                AND CURTIME() BETWEEN h_inicio AND h_fin";

        $query = $this->db->query($sql, [$nroTarjeta, $iplectora]);

        if ($query->getNumRows()) {
            return true;
        }

        $sqlEx = "SELECT exc.* 
                  FROM excepciones AS exc 
                  INNER JOIN comedores AS com ON exc.e_comedor = com.id_comedor 
                  INNER JOIN legajos AS leg ON leg.id_legajo = exc.e_idusuario 
                  INNER JOIN estados AS est ON leg.id_estado = est.id_estado 
                  WHERE leg.nro_tarjeta = ? 
                  AND leg.id_estado = 1 
                  AND exc.e_dia = ? 
                  AND (
                      (exc.e_inicio <= exc.e_fin AND CURTIME() BETWEEN exc.e_inicio AND exc.e_fin)
                      OR
                      (exc.e_inicio > exc.e_fin AND (CURTIME() >= exc.e_inicio OR CURTIME() <= exc.e_fin))
                  )
                  AND com.ip_comedor = ?";

        $queryEx = $this->db->query($sqlEx, [$nroTarjeta, $dia, $iplectora]);

        return $queryEx->getNumRows() > 0;
    }

    public function get_legajo($nroTarjeta)
    {
        return $this->db->table('legajos')->where('nro_tarjeta', $nroTarjeta)->get()->getRowArray();
    }

    public function get_nombre($nroTarjeta): ?string
    {
        return $this->db->table('legajos')->select('apnombre')->where('nro_tarjeta', $nroTarjeta)->get()->getRow('apnombre');
    }

    public function get_estado($nroTarjeta)
    {
        $sql = "SELECT leg.id_estado as id, est.descripcion as estado 
                FROM legajos AS leg 
                INNER JOIN estados AS est ON leg.id_estado = est.id_estado 
                WHERE leg.nro_tarjeta = ?";
        return $this->db->query($sql, [$nroTarjeta])->getRowArray();
    }

    public function check_tarjeta($nroTarjeta): bool
    {
        return $this->db->table('legajos')->where('nro_tarjeta', $nroTarjeta)->countAllResults() > 0;
    }

    private function get_dia_spanish($nrodia): string
    {
        $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        return $dias[intval($nrodia)] ?? '';
    }

    public function add_historial($nro_tarjeta)
    {
        $ip = $this->obtener_ip();
        $resultComedor = $this->db->query("SELECT id_comedor FROM comedores WHERE ip_comedor = ?", [$ip]);

        if (!$resultComedor->getNumRows()) {
            return false;
        }

        $comedor = $resultComedor->getRowArray()['id_comedor'];

        $array = $this->get_datoshistorial($nro_tarjeta);
        $array['fecha'] = date('Y-m-d');
        $array['hora'] = date('H:i:s');
        $array['nro_tarjeta'] = $nro_tarjeta;
        $array['comedor'] = $comedor;

        $this->db->table('historial')->insert($array);
        return $this->db->insertID();
    }

    private function get_datoshistorial($nro_tarjeta)
    {
        return $this->db->table('legajos')->select('id_legajo, id_empresa')->where('nro_tarjeta', $nro_tarjeta)->get()->getRowArray();
    }

    public function check_repetido($nrotarjeta): bool
    {
        $temp = $this->get_legajo($nrotarjeta);
        $filtro = [
            'fecha' => date('Y-m-d'),
            'id_legajo' => $temp['id_legajo'],
            'id_empresa' => $temp['id_empresa']
        ];

        $cantidad = $this->db->table('historial')->where($filtro)->countAllResults();
        return $cantidad > ($temp['ingresos_diario'] - 1);
    }

    public function get_datos_ticket($nroTicket)
    {
        if (!$nroTicket) return null;

        $sql = "SELECT his.id_historial AS ticket, leg.apnombre AS nombre, fecha, hora, emp.razonsocial AS empresa 
                FROM historial AS his 
                INNER JOIN empresas AS emp ON his.id_empresa = emp.id_empresa 
                INNER JOIN legajos AS leg ON his.id_legajo = leg.id_legajo 
                INNER JOIN departamentos AS dep ON dep.id_departamento = leg.id_departamento 
                WHERE id_historial = ?";
        return $this->db->query($sql, [$nroTicket])->getRowArray();
    }

    public function existeFoto($matricula): string
    {
        $path_foto = FCPATH . 'comedor/assets/fotos/' . $matricula . '.jpg';
        return file_exists($path_foto)
            ? base_url('comedor/assets/fotos/' . $matricula . '.jpg')
            : base_url('comedor/assets/fotos/noimage.jpg');
    }
}