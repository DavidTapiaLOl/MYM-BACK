<?php
class Pais extends Conectar
{
    public function get_pais()
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_pais;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, 'nombre' => $d->nombre
            ];
        }
        return $Array;
    }

    public function get_pais_x_id($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_pais WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id, 'nombre' => $resultado->nombre
        ] : [];
        return $Array;
    }

    public function insert_pais($nombre)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO `tbl_pais`(`nombre`) 
        VALUES (?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $resultado['estatus'] =  $sql->execute();
        $lastInserId =  $conectar->lastInsertId();
        if ($lastInserId != "0") {
            $resultado['id'] = (int)$lastInserId;
        }
        return $resultado;
    }

    public function update_pais($nombre,$id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE `tbl_pais` SET `nombre`= ? 
        WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

}
