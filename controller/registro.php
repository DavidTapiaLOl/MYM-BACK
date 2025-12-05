<?php
require_once("../config/conexion.php");
require_once("../models/Registro.php");
$registros = new Registro();
 
$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "GetAll":
        $datos = $registros->get_registro();
        echo json_encode($datos);
        break;

    case "GetId":
        $datos = $registros->get_registro_x_id($body["id"]);
        echo json_encode($datos);
        break;

    case "Insert":
        // Validar si viene foto_perfil, sino null
        $foto = isset($body["foto_perfil"]) ? $body["foto_perfil"] : null;

        $datos = $registros->insert_registro(
            $body["nombre"], $body["apellido_paterno"], $body["apellido_materno"], $body["fecha_nacimiento"],
            $body["correo"], $body["telefono"], $body["usuario"], $body["password"], 
            $body["tbl_municipio_id"], $body["tbl_pais_id"], $body["tbl_estado_id"],
            $foto // <--- NUEVO
        );
        echo json_encode($datos);
        break;

    case "Update":
        $estatus = isset($body["estatus"]) ? $body["estatus"] : 1;
        $foto = isset($body["foto_perfil"]) ? $body["foto_perfil"] : null;

        $datos = $registros->update_registro(
            $body["nombre"], $body["apellido_paterno"], $body["apellido_materno"], $body["fecha_nacimiento"],
            $body["correo"], $body["telefono"], $body["usuario"], $body["password"],
            $estatus, 
            $body["tbl_municipio_id"], $body["tbl_pais_id"], $body["tbl_estado_id"],
            $foto, // <--- NUEVO
            $body["id"]
        );
        echo json_encode($datos);
        break;

    case "Delete":
        $datos = $registros->delete_registro($body["id"]);
        echo json_encode($datos);
        break;
}
?>