<?php
require_once("../config/conexion.php");
require_once("../models/Pais.php");
$pais = new Pais();
 
$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $pais->get_pais();
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $pais->get_pais_x_id($body["id"]);
        echo json_encode($datos);
        break;

    case "Insert":
        $datos = $pais->insert_pais($body["nombre"]);
        echo json_encode($datos);
        break;

    case "Update":
        $datos = $pais->update_pais($body["nombre"],$body["id"]);
        echo json_encode($datos);
        break;
}
