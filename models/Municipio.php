<?php
class Municipio extends Conectar
{
    public function get_municipio()
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_municipio;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, 'nombre' => $d->nombre,'tbl_estado_id' => $d->tbl_estado_id
            ];
        }
        return $Array;
    }

    public function get_municipio_x_id($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_municipio WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id, 'nombre' => $resultado->nombre,'tbl_estado_id' => $resultado->tbl_estado_id
        ] : [];
        return $Array;
    }

    public function get_municipio_x_estado($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_municipio WHERE tbl_estado_id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, 'nombre' => $d->nombre,'tbl_estado_id' => $d->tbl_estado_id
            ];
        }
        return $Array;
    }

    public function insert_municipio($nombre,$tbl_estado_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO `tbl_municipio`(`nombre`,`tbl_estado_id`) 
        VALUES (?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $tbl_estado_id);
        $resultado['estatus'] =  $sql->execute();
        $lastInserId =  $conectar->lastInsertId();
        if ($lastInserId != "0") {
            $resultado['id'] = (int)$lastInserId;
        }
        return $resultado;
    }

    public function update_municipio($nombre,$tbl_estado_id,$id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE `tbl_municipio` SET `nombre`= ?,`tbl_estado_id`= ?
        WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $tbl_estado_id);
        $sql->bindValue(3, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }


}
