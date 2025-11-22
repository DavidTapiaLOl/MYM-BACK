<?php
require_once("../config/conexion.php");
require_once("../models/Estado.php");
$estado = new Estado();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $estado->get_estado();
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $estado->get_estado_x_id($body["id"]);
        echo json_encode($datos);
        break;

    case "GetIdxPais":
        $datos = $estado->get_estado_x_pais($body["id"]);
        echo json_encode($datos);
        break;

    case "Insert":
        $datos = $estado->insert_estado($body["nombre"], $body["tbl_pais_id"]);
        echo json_encode($datos);
        break;

    case "Update":
        $datos = $estado->update_estado($body["nombre"], $body["tbl_pais_id"], $body["id"]);
        echo json_encode($datos);
        break;
}
