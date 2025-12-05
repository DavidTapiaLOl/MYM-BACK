<?php
// cron_notificaciones.php
// Este archivo busca pagos próximos y usa tu función Correo existente para avisar.

require_once("../config/conexion.php");
require_once("../models/Registro.php"); // Usamos el modelo que ya tiene la función Correo

class Notificador extends Conectar {
    
    public function procesarNotificaciones() {
        $conectar = parent::conexion();
        parent::set_names();

        // 1. LÓGICA DEL REQUERIMIENTO:
        // Buscamos egresos FIJOS (es_fijo = 1)
        // Cuya fecha límite sea HOY o en los próximos 3 DÍAS.
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
                AND DATEDIFF(e.fecha_limite, CURDATE()) BETWEEN 0 AND 3";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $registroModel = new Registro();
        $contador = 0;

        foreach ($pendientes as $p) {
            $nombre_completo = $p['nombre'] . ' ' . $p['apellido_paterno'];
            
            // AQUÍ DEFINIMOS EL TEXTO DIFERENTE PARA EL RECORDATORIO
            $asunto = "⏰ Recordatorio: Pago de " . $p['descripcion'];
            $mensaje = "Hola $nombre_completo, te recordamos que tu pago fijo de '{$p['descripcion']}' por $ {$p['monto']} vence el día {$p['fecha_limite']}.";

            // ¡REUTILIZAMOS TU FUNCIÓN CORREO! 
            // Ella se encarga de hablarle a Pipedream.
            $registroModel->Correo($nombre_completo, $mensaje, $p['correo'], $asunto);
            
            $contador++;
        }

        return ["estatus" => true, "mensaje" => "Se enviaron $contador notificaciones."];
    }
}

// Ejecutar
$notificador = new Notificador();
echo json_encode($notificador->procesarNotificaciones());
?>