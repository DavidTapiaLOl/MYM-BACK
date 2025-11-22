<?php
require_once("../config/conexion.php");
require_once("../models/Concepto.php");
$concepto = new Concepto();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $concepto->get_concepto();
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $concepto->get_estado_x_id($body["id"]);
        echo json_encode($datos);
        break;

    case "Insert":
        $datos = $concepto->insert_concepto($body["nombre"], $body["acceso"]);
        echo json_encode($datos);
        break;

    case "Update":
        $datos = $concepto->update_concepto($body["nombre"], $body["acceso"], $body["id"]);
        echo json_encode($datos);
        break;

    case "Delete":
        $datos = $concepto->delete_concepto($body["id"]);
        echo json_encode($datos);
        break;
}
