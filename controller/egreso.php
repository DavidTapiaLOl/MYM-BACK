<?php
require_once("../config/conexion.php");
require_once("../models/Egreso.php");
$egreso = new Egreso();
 
$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $egreso->get_egreso($body['id']);
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $egreso->get_egreso_x_id($body["id"]);

       
        if (!empty($datos) && !empty($datos["fecha_limite"]) && empty($datos["fecha_pago"])) {
            
            try {
                $hoy = new DateTime();
                $limite = new DateTime($datos["fecha_limite"]);
                $diferencia = $hoy->diff($limite);
                $dias = (int)$diferencia->format("%r%a"); // Calculamos diferencia en días

                // Si la fecha es HOY o en los próximos 3 DÍAS
                if ($dias >= 0 && $dias <= 3) {
                    
                    // Necesitamos el modelo Registro para sacar el correo y enviar
                    require_once("../models/Registro.php");
                    $registroModel = new Registro();
                    
                    // Obtenemos datos del usuario dueño del egreso
                    $usuario = $registroModel->get_datos_usuario($datos["tbl_registro_id"]);
                    
                    if ($usuario) {
                        $nombre_completo = $usuario->nombre . ' ' . $usuario->apellido_paterno;
                        $fecha_fmt = date("d-m-Y", strtotime($datos["fecha_limite"]));
                        
                        $asunto = "⚠️ Recordatorio de Pago Pendiente: " . $datos["descripcion"];
                        $mensaje = "Hola $nombre_completo, notamos que estás revisando un pago pendiente.\n\n" .
                                   "Te recordamos que tu compromiso de '{$datos['descripcion']}' " .
                                   "por $" . number_format($datos['monto'], 2) . "\n" .
                                   "Vence el día: $fecha_fmt (En $dias días).\n\n" .
                                   "¡No olvides registrar tu pago!";

                        // Enviamos el correo
                        $registroModel->Correo($nombre_completo, $mensaje, $usuario->correo, $asunto);
                    }
                }
            } catch (Exception $e) {
                
            }
        }

        
        echo json_encode($datos);
        break;

    case "Insert":

        $datos = $egreso->insert_egreso(
            $body["descripcion"],
            $body["monto"],
            $body["fecha_limite"],
            $body["fecha_registro"], 
            $body["fecha_pago"], 
            $body["tbl_concepto_id"],
            $body["tbl_tipo_pago_id"],
            $body["tbl_periodo_id"],
            $body["tbl_registro_id"],
            $body["es_fijo"]
        );

        
        if ($datos['estatus'] && !empty($body["es_fijo"]) && !empty($body["fecha_limite"])) {
            try {
                // Calcular días restantes
                $hoy = new DateTime();
                $limite = new DateTime($body["fecha_limite"]);
                $diff = $hoy->diff($limite);
                $dias = (int)$diff->format("%r%a"); 

            
                if ($dias >= 0 && $dias <= 3 && empty($body["fecha_pago"])) {
                    
                    require_once("../models/Registro.php");
                    $registroModel = new Registro();
                    
                    
                    $usuario = $registroModel->get_datos_usuario($body["tbl_registro_id"]);
                    
                    if ($usuario) {
                        $nombre = $usuario->nombre . ' ' . $usuario->apellido_paterno;
                        $fecha_fmt = date("d-m-Y", strtotime($body["fecha_limite"]));
                        
                        $asunto = "🔔 Nuevo Compromiso Próximo: " . $body["descripcion"];
                        $mensaje = "Hola $nombre, registraste un pago fijo próximo a vencer.\n\n" .
                                   "Concepto: " . $body["descripcion"] . "\n" .
                                   "Monto: $" . number_format($body["monto"], 2) . "\n" .
                                   "Vence el: " . $fecha_fmt;

      
                        $registroModel->Correo($nombre, $mensaje, $usuario->correo, $asunto);
                    }
                }
            } catch (Exception $e) {
            }
        }

        echo json_encode($datos);
        break;


    case "Update":
        $es_fijo = !empty($body["es_fijo"]) ? 1 : 0;

        $datos = $egreso->update_egreso(
            $body["descripcion"], 
            $body["monto"],
            $body["fecha_limite"],
            $body["fecha_registro"], 
            $body["fecha_pago"], 
            $body["tbl_concepto_id"], 
            $body["tbl_tipo_pago_id"], 
            $body["tbl_periodo_id"], 
            $body["tbl_registro_id"],
            $es_fijo,
            $body["id"]
        );
        echo json_encode($datos);
        break;

    case "Delete":
        $datos = $egreso->delete($body["id"]);
        echo json_encode($datos);
        break;

    case "graficaConceptos":
        $datos = $egreso->get_grafica_conceptos($body["id"]);
        echo json_encode($datos);
        break;
}
?>