<?php
header('Content-Type: application/json');

require_once("../config/conexion.php");
require_once("../models/Ingreso.php");

$ingreso = new Ingreso();


$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $ingreso->get_ingreso($body['id']);
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $ingreso->get_ingreso_x_id($body["id"]);
        echo json_encode($datos);
        break;


    case "Getinfo":
        $datos = $ingreso->getinfo($body["id"]);
        echo json_encode($datos);
        break;


    case "Insert":
        $datos = $ingreso->insert_ingreso(
            $body["descripcion"],
            $body["monto"],
            $body["fecha_registro"], 
            $body["fecha_pago"],
            $body["tbl_concepto_id"],
            $body["tbl_tipo_pago_id"],
            $body["tbl_periodo_id"],
            $body["tbl_registro_id"]
        );
        echo json_encode($datos);
        break;

    case "Update":
        $datos = $ingreso->update_ingreso(
            $body["descripcion"],
            $body["monto"],
            $body["fecha_registro"],
            $body["fecha_pago"],
            $body["tbl_concepto_id"],
            $body["tbl_tipo_pago_id"],
            $body["tbl_periodo_id"],
            $body["tbl_registro_id"],
            $body["id"]
        );
        echo json_encode($datos);
        break;


    case "Delete":
        $datos = $ingreso->delete($body["id"]);
        echo json_encode($datos);
        break;


    case "Getsuma":
    require_once("../models/Egreso.php");
    $egreso = new Egreso();
    
    $totalIngreso = $ingreso->get_suma($body["id"]);
    $totalEgreso = $egreso->get_suma($body["id"]);
    

    echo json_encode([
        "ingreso" => (float)$totalIngreso,
        "egreso" => (float)$totalEgreso
    ]);
    break;

    case "graficaMes":
        $datos = $ingreso->get_monto($body["id"]);
        echo json_encode($datos);
        break;
}
?>