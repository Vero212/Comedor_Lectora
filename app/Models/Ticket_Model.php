<?php

namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class Ticket_Model extends Model
{
    protected $db;

    public function __construct(ConnectionInterface &$db = null)
    {
        parent::__construct($db);
        $this->db = \Config\Database::connect();
    }

    public function get_datos($nroTicket)
    {
        if (!$nroTicket) return null;

        $sql = "SELECT his.id_historial AS ticket, leg.apnombre AS nombre, fecha, hora, dep.vianda, emp.razonsocial AS empresa
                FROM historial AS his
                INNER JOIN empresas AS emp ON his.id_empresa = emp.id_empresa
                INNER JOIN legajos AS leg ON his.id_legajo = leg.id_legajo
                INNER JOIN departamentos AS dep ON leg.id_departamento = dep.id_departamento
                WHERE his.id_historial = ?";
        
        return $this->db->query($sql, [$nroTicket])->getRowArray();
    }

    public function getTotalTickets(): int
    {
        return $this->db->table('historial')->countAll();
    }

    public function getTicketsHoy(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM historial WHERE fecha = CURRENT_DATE()";
        return (int) $this->db->query($sql)->getRow('total');
    }

    public function getTicketsDiarios($mes = false): array
    {
        if (!$mes) {
            $sql = "SELECT COUNT(*) AS cantidad, fecha 
                    FROM historial 
                    WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) 
                    AND YEAR(fecha) = YEAR(CURRENT_DATE()) 
                    GROUP BY fecha";
            return $this->db->query($sql)->getResultArray();
        }

        return [];
    }

    public function getTicketsMes($mes = false): array
    {
        if (!$mes) {
            $sql = "SELECT COUNT(*) AS cantidad, MONTH(fecha) AS mes 
                    FROM historial 
                    WHERE YEAR(fecha) = YEAR(CURRENT_DATE()) 
                    GROUP BY mes";
            return $this->db->query($sql)->getResultArray();
        }

        return [];
    }
}