<?php
class Estado extends Conectar
{
    public function get_estado()
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_estado;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, 'nombre' => $d->nombre,'tbl_pais_id' => $d->tbl_pais_id
            ];
        }
        return $Array;
    }

    public function get_estado_x_id($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_estado WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id, 'nombre' => $resultado->nombre,'tbl_pais_id' => $resultado->tbl_pais_id
        ] : [];
        return $Array;
    }

    public function get_estado_x_pais($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_estado WHERE tbl_pais_id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, 'nombre' => $d->nombre,'tbl_pais_id' => $d->tbl_pais_id
            ];
        }
        return $Array;
    }

    public function insert_estado($nombre,$tbl_pais_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO `tbl_estado`(`nombre`,`tbl_pais_id`) 
        VALUES (?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $tbl_pais_id);
        $resultado['estatus'] =  $sql->execute();
        $lastInserId =  $conectar->lastInsertId();
        if ($lastInserId != "0") {
            $resultado['id'] = (int)$lastInserId;
        }
        return $resultado;
    }

    public function update_estado($nombre,$tbl_pais_id,$id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE `tbl_estado` SET `nombre`= ?,`tbl_pais_id`= ? 
        WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $tbl_pais_id);
        $sql->bindValue(3, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    

}
