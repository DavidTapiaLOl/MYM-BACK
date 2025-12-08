<?php

header('Content-Type: application/json');

require_once("../config/conexion.php");
require_once("../models/Registro.php"); 

class Notificador extends Conectar {
    
    public function procesarNotificaciones() {
        $conectar = parent::conexion();
        parent::set_names();

     
        
        $sql = "SELECT 
                    e.descripcion, 
                    e.monto, 
                    e.fecha_limite, 
                    u.nombre, 
                    u.apellido_paterno, 
                    u.correo 
                FROM tbl_egreso e
                INNER JOIN tbl_registro u ON e.tbl_registro_id = u.id
                WHERE e.es_fijo = 1 
                AND e.fecha_limite IS NOT NULL
                AND e.fecha_pago IS NULL
                AND DATEDIFF(e.fecha_limite, CURDATE()) BETWEEN 0 AND 3";

        try {
            $stmt = $conectar->prepare($sql);
            $stmt->execute();
            $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $registroModel = new Registro();
            $contador = 0;

            foreach ($pendientes as $p) {
                $nombre_completo = $p['nombre'] . ' ' . $p['apellido_paterno'];
                $fecha_formateada = date("d-m-Y", strtotime($p['fecha_limite']));
                
                $asunto = "⏰ Recordatorio: Pago de " . $p['descripcion'];
                
                $mensaje = "Hola $nombre_completo, este es un recordatorio automático. \n\n" .
                           "Tu pago recurrente de '{$p['descripcion']}' por $" . number_format($p['monto'], 2) . 
                           " tiene como fecha límite el día $fecha_formateada. \n\n" .
                           "¡Evita recargos!";

                $registroModel->Correo($nombre_completo, $mensaje, $p['correo'], $asunto);
                $contador++;
            }

            return ["estatus" => true, "mensaje" => "Se enviaron $contador notificaciones de cobro."];

        } catch (PDOException $e) {
  
            return ["estatus" => false, "error" => $e->getMessage()];
        }
    }
}


$notificador = new Notificador();
echo json_encode($notificador->procesarNotificaciones());
?>