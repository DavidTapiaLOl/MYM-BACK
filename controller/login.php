<?php
require_once("../config/conexion.php");
require_once("../models/Login.php");

$login = new Login();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["opcion"]) {

    case "Login":
        $datos = $login->Login_access($body["usuario"], $body["password"]);
        echo json_encode($datos);
        break;
}
