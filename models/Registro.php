<?php
class Registro extends Conectar

{
    public function get_registro()
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_registro;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id,
                'nombre' => $d->nombre,
                'apellido_paterno' => $d->apellido_paterno,
                'apellido_materno' => (int)$d->apellido_materno,
                'fecha_nacimiento' => (int)$d->fecha_nacimiento,
                'correo' => (int)$d->correo,
                'telefono' => (int)$d->telefono,
                'usuario' => (int)$d->usuario,
                'password' => (int)$d->password,
                'estatus' => (int)$d->estatus,
                'tbl_municipio_id' => (int)$d->tbl_municipio_id,
                'tbl_pais_id' => (int)$d->tbl_pais_id,
                'tbl_estado_id' => (int)$d->tbl_estado_id
            ];
        }
        return $Array;
    }


    public function get_registro_x_id($registro_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_registro WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $registro_id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id,
            'nombre' => $resultado->nombre,
            'apellido_paterno' => $resultado->apellido_paterno,
            'apellido_materno' => $resultado->apellido_materno,
            'fecha_nacimiento' => $resultado->fecha_nacimiento,
            'correo' => $resultado->correo,
            'telefono' => $resultado->telefono,
            'usuario' => $resultado->usuario,
            'password' => $resultado->password,
            'tbl_municipio_id' => $resultado->tbl_municipio_id,
            'tbl_pais_id' => $resultado->tbl_pais_id,
            'tbl_estado_id' => $resultado->tbl_estado_id,
        ] : [];
        return $Array;
    }

    public function insert_registro(
        $nombre,
        $apellido_paterno,
        $apellido_materno,
        $fecha_nacimiento,
        $correo,
        $telefono,
        $usuario,
        $password,
        $tbl_municipio_id,
        $tbl_pais_id,
        $tbl_estado_id
    ) {

        // $descripcion = '
        // <p>Hola <strong>$nombre_completo</strong></p>
        // <p>Bienvenido a <span style="color: rgb(97, 189, 109);">Manage your Money</span>.</p>
        // ';

        // $descripcion = <<<HTML
        // <h3>Hola Bienvenidos a Manage Your Money</h3>
        // <p>Gracias por registrarte</p>
        // HTML;

        $nombre_completo = $nombre . ' ' . $apellido_paterno;

        $descripcion = "Bienvedio a Manage Your Money  $nombre_completo";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Genera el hash

        $conectar = parent::conexion();
        $asunto = 'Registro MYM';

        // Instancerar una funcion en el mismo archivo
        Registro::Correo($nombre_completo, $descripcion, $correo, $asunto);

        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO `tbl_registro`(`nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`,
        `correo`, `telefono`, `usuario`, `password`, `tbl_municipio_id`, `tbl_pais_id`, `tbl_estado_id`) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $apellido_paterno);
        $sql->bindValue(3, $apellido_materno);
        $sql->bindValue(4, $fecha_nacimiento);
        $sql->bindValue(5, $correo);
        $sql->bindValue(6, $telefono);
        $sql->bindValue(7, $usuario);
        $sql->bindValue(8, $hashed_password);
        $sql->bindValue(9, $tbl_municipio_id);
        $sql->bindValue(10, $tbl_pais_id);
        $sql->bindValue(11, $tbl_estado_id);
        $resultado['estatus'] =  $sql->execute();
        $lastInserId =  $conectar->lastInsertId();
        if ($lastInserId != "0") {
            $resultado['id'] = (int)$lastInserId;
        }
        return $resultado;
    }

    public function update_registro(
        $nombre, 
        $apellido_paterno, 
        $apellido_materno, 
        $fecha_nacimiento, 
        $correo, 
        $telefono, 
        $usuario, 
        $password, 
        $estatus, 
        $tbl_municipio_id, 
        $tbl_pais_id, 
        $tbl_estado_id, 
        $id)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Genera el hash
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE `tbl_registro` SET `nombre`= ?, `apellido_paterno`= ?, `apellido_materno`= ?,`fecha_nacimiento`= ? ,`correo`= ?,
        `telefono`= ? ,`usuario`= ? ,`password`= ? ,`tbl_municipio_id`= ? ,`tbl_pais_id`= ?,`tbl_estado_id`= ? 
        WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $apellido_paterno);
        $sql->bindValue(3, $apellido_materno);
        $sql->bindValue(4, $fecha_nacimiento);
        $sql->bindValue(5, $correo);
        $sql->bindValue(6, $telefono);
        $sql->bindValue(7, $usuario);
        $sql->bindValue(8, $hashed_password);
        $sql->bindValue(9, $tbl_municipio_id);
        $sql->bindValue(10, $tbl_pais_id);
        $sql->bindValue(11, $tbl_estado_id);
        $sql->bindValue(12, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    public function delete_registro($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM `tbl_registro` WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    public function Correo($nombre, $description, $email, $asunto)
    {

        $curl_options = array(
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false

        );
        // Los datos que le vamos a mandar para armar el correo
        $fields = json_encode(array(
            "nombre" => $nombre,
            "concepto" => $description,
            "correo" => $email,
            "asunto" => $asunto
        ));
        // El tratado de la url con la peticion
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://eocr5b401bk1z2i.m.pipedream.net");
        curl_setopt_array($curl, $curl_options);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        $response = json_decode(curl_exec($curl));
        echo $response;
        curl_close($curl);
    }
}
