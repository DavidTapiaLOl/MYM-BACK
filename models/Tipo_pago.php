<?php
class Tipo_pago extends Conectar
{
    public function get_tipo_pago()
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_tipo_pago ;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, 'nombre' => $d->nombre,'acceso' => $d->acceso
            ];
        }
        return $Array;
    }

    public function get_tipo_pago_x_id($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_tipo_pago WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id, 'nombre' => $resultado->nombre,'acceso' => $resultado->acceso
        ] : [];
        return $Array;
    }

    public function insert_tipo_pago($nombre,$acceso)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO `tbl_tipo_pago`(`nombre`,`acceso`) 
        VALUES (?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $acceso);
        $resultado['estatus'] =  $sql->execute();
        $lastInserId =  $conectar->lastInsertId();
        if ($lastInserId != "0") {
            $resultado['id'] = (int)$lastInserId;
        }
        return $resultado;
    }

    public function update_tipo_pago($nombre,$acceso,$id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE `tbl_tipo_pago` SET `nombre`= ?,`acceso`= ? 
        WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $nombre);
        $sql->bindValue(2, $acceso);
        $sql->bindValue(3, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    public function delete_tipo_pago($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM `tbl_tipo_pago` WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

}
