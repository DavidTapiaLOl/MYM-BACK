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
                'apellido_materno' => (isset($d->apellido_materno) ? $d->apellido_materno : ''),
                'fecha_nacimiento' => $d->fecha_nacimiento,
                'correo' => $d->correo,
                'telefono' => $d->telefono,
                'usuario' => $d->usuario,
                'password' => $d->password,
                'estatus' => (int)$d->estatus,
                'tbl_municipio_id' => (int)$d->tbl_municipio_id,
                'tbl_pais_id' => (int)$d->tbl_pais_id,
                'tbl_estado_id' => (int)$d->tbl_estado_id,
                'foto_perfil' => $d->foto_perfil // <--- NUEVO
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
            'tbl_municipio_id' => (int)$resultado->tbl_municipio_id,
            'tbl_pais_id' => (int)$resultado->tbl_pais_id,
            'tbl_estado_id' => (int)$resultado->tbl_estado_id,
            'foto_perfil' => $resultado->foto_perfil
        ] : [];
        return $Array;
    }

    public function insert_registro($nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, $correo, $telefono, $usuario, $password, $tbl_municipio_id, $tbl_pais_id, $tbl_estado_id, $foto_perfil)
    {
        if (strlen($fecha_nacimiento) > 10) {
            $fecha_nacimiento = substr($fecha_nacimiento, 0, 10);
        }

        $nombre_completo = $nombre . ' ' . $apellido_paterno;
        $descripcion = "Bienvenido a Manage Your Money $nombre_completo";
        $asunto = 'Registro MYM';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->Correo($nombre_completo, $descripcion, $correo, $asunto);
        } catch (Exception $e) {}

        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "INSERT INTO `tbl_registro`(`nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`,
        `correo`, `telefono`, `usuario`, `password`, `tbl_municipio_id`, `tbl_pais_id`, `tbl_estado_id`, `foto_perfil`) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?);"; 
        
        try {
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
            $sql->bindValue(12, $foto_perfil); // <--- NUEVO BIND
            
            $execute = $sql->execute();
            $resultado = ['estatus' => $execute];
            
            $lastInserId = $conectar->lastInsertId();
            if ($lastInserId != "0") {
                $resultado['id'] = (int)$lastInserId;
            }
            return $resultado;

        } catch (PDOException $e) {
            return ['estatus' => false, 'mensaje' => 'Error BD: ' . $e->getMessage()];
        }
    }

   public function update_registro(
        $nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, 
        $correo, $telefono, $usuario, $password, $estatus, 
        $tbl_municipio_id, $tbl_pais_id, $tbl_estado_id, $foto_perfil, $id
    ) {
        if (strlen($fecha_nacimiento) > 10) {
            $fecha_nacimiento = substr($fecha_nacimiento, 0, 10);
        }

        $conectar = parent::conexion();
        parent::set_names();
        

        if (!empty($password)) {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE `tbl_registro` SET `nombre`= ?, `apellido_paterno`= ?, `apellido_materno`= ?, `fecha_nacimiento`= ? ,`correo`= ?,
            `telefono`= ? ,`usuario`= ? ,`password`= ? ,`tbl_municipio_id`= ? ,`tbl_pais_id`= ?,`tbl_estado_id`= ?, `foto_perfil`= ? 
            WHERE id = ?;";
            
            $stmt = $conectar->prepare($sql);
    
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $apellido_paterno);
            $stmt->bindValue(3, $apellido_materno);
            $stmt->bindValue(4, $fecha_nacimiento);
            $stmt->bindValue(5, $correo);
            $stmt->bindValue(6, $telefono);
            $stmt->bindValue(7, $usuario);
            $stmt->bindValue(8, $hashed_password); // <--- Nueva contraseña encriptada
            $stmt->bindValue(9, $tbl_municipio_id);
            $stmt->bindValue(10, $tbl_pais_id);
            $stmt->bindValue(11, $tbl_estado_id);
            $stmt->bindValue(12, $foto_perfil);
            $stmt->bindValue(13, $id);

        } else {
           
            
            $sql = "UPDATE `tbl_registro` SET `nombre`= ?, `apellido_paterno`= ?, `apellido_materno`= ?, `fecha_nacimiento`= ? ,`correo`= ?,
            `telefono`= ? ,`usuario`= ? ,`tbl_municipio_id`= ? ,`tbl_pais_id`= ?,`tbl_estado_id`= ?, `foto_perfil`= ? 
            WHERE id = ?;";
            
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $apellido_paterno);
            $stmt->bindValue(3, $apellido_materno);
            $stmt->bindValue(4, $fecha_nacimiento);
            $stmt->bindValue(5, $correo);
            $stmt->bindValue(6, $telefono);
            $stmt->bindValue(7, $usuario);
            $stmt->bindValue(8, $tbl_municipio_id);
            $stmt->bindValue(9, $tbl_pais_id);
            $stmt->bindValue(10, $tbl_estado_id);
            $stmt->bindValue(11, $foto_perfil);
            $stmt->bindValue(12, $id);
        }
        
        try {
            $resultado['estatus'] = $stmt->execute();
            return $resultado;
        } catch (PDOException $e) {
             return ['estatus' => false, 'mensaje' => 'Error BD: ' . $e->getMessage()];
        }
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



        $url_pipedream = "https://eoz9fl1ucpbv1za.m.pipedream.net";

        // El tratado de la url con la peticion

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url_pipedream);

        curl_setopt_array($curl, $curl_options);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        $response = json_decode(curl_exec($curl));

        

        curl_close($curl);

    }

public function get_datos_usuario($id) {
        $conectar = parent::conexion();
        parent::set_names();
        // Solo pedimos nombre y correo del ID específico
        $sql = "SELECT nombre, apellido_paterno, correo FROM tbl_registro WHERE id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

}
?>