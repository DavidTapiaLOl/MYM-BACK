<?php
require_once("../config/conexion.php");
require_once("../models/Tipo_pago.php");
$tipo_pago = new Tipo_pago();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $tipo_pago->get_tipo_pago();
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $tipo_pago->get_tipo_pago_x_id($body["id"]);
        echo json_encode($datos);
        break;

    case "Insert":
        $datos = $tipo_pago->insert_tipo_pago($body["nombre"], $body["acceso"]);
        echo json_encode($datos);
        break;

    case "Update":
        $datos = $tipo_pago->update_tipo_pago($body["nombre"], $body["acceso"], $body["id"]);
        echo json_encode($datos);
        break;

    case "Delete":
        $datos = $tipo_pago->delete_tipo_pago($body["id"]);
        echo json_encode($datos);
        break;
}
