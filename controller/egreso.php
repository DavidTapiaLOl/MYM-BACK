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
        echo json_encode($datos);
        break;

    case "Insert":
        $es_fijo = !empty($body["es_fijo"]) ? 1 : 0;
        
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
            $es_fijo
        );
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

    // --- ESTO FALTABA PARA LA GRÁFICA ---
    case "graficaConceptos":
        $datos = $egreso->get_grafica_conceptos($body["id"]);
        echo json_encode($datos);
        break;
}
?>