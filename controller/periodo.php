<?php
require_once("../config/conexion.php");
require_once("../models/Periodo.php");
$periodo = new Periodo();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $periodo->get_periodo();
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $periodo->get_periodo_x_id($body["id"]);
        echo json_encode($datos);
        break;

    case "Insert":
        $datos = $periodo->insert_periodo($body["nombre"], $body["acceso"]);
        echo json_encode($datos);
        break;

    case "Update":
        $datos = $periodo->update_periodo($body["nombre"], $body["acceso"], $body["id"]);
        echo json_encode($datos);
        break;

    case "Delete":
        $datos = $periodo->delete_periodo($body["id"]);
        echo json_encode($datos);
        break;
}
