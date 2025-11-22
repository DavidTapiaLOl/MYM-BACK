<?php
class Ingreso extends Conectar
{
    public function get_ingreso($id)
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_ingreso WHERE tbl_registro_id = ?;";
        $sql = $db->prepare($sql);
        $sql -> bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, '01_descripcion' => $d->descripcion,
                '02_monto' => $d->monto,'03_fecha_registro' => $d->fecha_registro,'04_fecha_pago' => $d->fecha_pago
                ,'tbl_concepto_id' => (int)$d->tbl_concepto_id,'tbl_tipo_pago_id' => (int)$d->tbl_tipo_pago_id
                ,'tbl_periodo_id' => (int)$d->tbl_periodo_id,
                'tbl_registro_id' => (int)$d->tbl_registro_id
            ];
        }
        return $Array;
    }


    public function get_ingreso_x_id($registro_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_ingreso WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $registro_id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id,'descripcion' => $resultado->descripcion,
            'monto' => $resultado->monto, 'monto_general' => $resultado->monto_general,'fecha_registro' => $resultado->fecha_registro,
            'fecha_pago' => $resultado->fecha_pago,'tbl_concepto_id' => (int)$resultado->tbl_concepto_id,
            'tbl_tipo_pago_id' => (int)$resultado->tbl_tipo_pago_id,'tbl_periodo_id' => (int)$resultado->tbl_periodo_id,
            'tbl_registro_id' => (int)$resultado->tbl_registro_id
        ] : [];
        return $Array;
    }

    public function insert_ingreso($descripcion, $monto,$fecha_registro,$fecha_pago,$tbl_concepto_id,$tbl_tipo_pago_id,$tbl_periodo_id,$tbl_registro_id)
    {

        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO `tbl_ingreso`(`descripcion`, `monto`, 
         `fecha_registro`, `fecha_pago`, `tbl_concepto_id`, `tbl_tipo_pago_id`, `tbl_periodo_id`, `tbl_registro_id`) 
        VALUES (?,?,?,?,?,?,?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $descripcion);
        $sql->bindValue(2, $monto);
        $sql->bindValue(3, $fecha_registro);
        $sql->bindValue(4, $fecha_pago);
        $sql->bindValue(5, $tbl_concepto_id);
        $sql->bindValue(6, $tbl_tipo_pago_id);
        $sql->bindValue(7, $tbl_periodo_id);
        $sql->bindValue(8, $tbl_registro_id);
        $resultado['estatus'] =  $sql->execute();
        $lastInserId =  $conectar->lastInsertId();
        if ($lastInserId != "0") {
            $resultado['id'] = (int)$lastInserId;
        }
        return $resultado;
    }

    public function update_ingreso($descripcion, $monto,$fecha_registro,$fecha_pago,$tbl_concepto_id,$tbl_tipo_pago_id,$tbl_periodo_id,$tbl_registro_id,$id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE `tbl_ingreso` SET `descripcion`= ?, `monto`= ?,`fecha_registro`= ?,
        `fecha_pago`= ? ,`tbl_concepto_id`= ? ,`tbl_tipo_pago_id`= ? ,`tbl_periodo_id`= ? ,`tbl_registro_id`= ? 
        WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $descripcion);
        $sql->bindValue(2, $monto);
        $sql->bindValue(3, $fecha_registro);
        $sql->bindValue(4, $fecha_pago);
        $sql->bindValue(5, $tbl_concepto_id);
        $sql->bindValue(6, $tbl_tipo_pago_id);
        $sql->bindValue(7, $tbl_periodo_id);
        $sql->bindValue(8, $tbl_registro_id);
        $sql->bindValue(9, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    public function delete($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM `tbl_ingreso` WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    public function get_ingreso2($id)
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_ingreso WHERE tbl_registro_id = ?;";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, '01_descripcion' => $d->descripcion,
                '02_monto' => $d->monto,'tbl_concepto_id' => (int)$d->tbl_concepto_id,'tbl_tipo_pago_id' => (int)$d->tbl_tipo_pago_id
                ,'tbl_periodo_id' => (int)$d->tbl_periodo_id,
                'tbl_registro_id' => (int)$d->tbl_registro_id,  '03_tipo' => 'Ingreso',
                //  'ruta' => "ingreso"
            ];
        }
        return $Array;
    }

    public function get_suma($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT SUM(monto) as monto FROM tbl_ingreso  WHERE tbl_registro_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado -> monto;
    }

    public function get_monto($id){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT  SUM(I.monto) as monto, MONTHNAME(I.fecha_pago) as mes
        FROM `tbl_ingreso` as I
        WHERE I.tbl_registro_id = ?
        GROUP BY mes
        ORDER BY mes;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'mes' => $d->mes, 'monto' => $d->monto,
            ];
        }
        return $Array;
        
    }


    
}
