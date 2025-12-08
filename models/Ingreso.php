<?php
class Ingreso extends Conectar
{
    public function get_ingreso($id)
    {
        $db = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    i.*,
                    tp.nombre as nombre_tipo_pago,
                    p.nombre as nombre_periodo,
                    c.nombre as nombre_concepto
                FROM tbl_ingreso i
                LEFT JOIN tbl_tipo_pago tp ON i.tbl_tipo_pago_id = tp.id
                LEFT JOIN tbl_periodo p ON i.tbl_periodo_id = p.id
                LEFT JOIN tbl_concepto c ON i.tbl_concepto_id = c.id
                WHERE i.tbl_registro_id = ?";

        $sql = $db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);

        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id,
                'tbl_tipo_pago_id' => (int)$d->tbl_tipo_pago_id,
                'tbl_periodo_id' => (int)$d->tbl_periodo_id,
                'tbl_concepto_id' => (int)$d->tbl_concepto_id,
                'tbl_registro_id' => (int)$d->tbl_registro_id,
                
                // COLUMNAS VISIBLES (Con protección para nulos)
                '01_descripcion' => $d->descripcion,
                '02_monto' => $d->monto,
                '03_tipo_pago' => $d->nombre_tipo_pago ? $d->nombre_tipo_pago : 'Sin Tipo',
                '04_periodo' => $d->nombre_periodo ? $d->nombre_periodo : 'Sin Periodo',
                '05_concepto' => $d->nombre_concepto ? $d->nombre_concepto : 'Sin Concepto',
                '06_fecha' => $d->fecha_registro
            ];
        }
        return $Array;
    }

    public function getinfo($id) {
    $db = parent::conexion();
    parent::set_names();

    // Consultamos Ingresos y Egresos unidos, ordenados por fecha
    $sql = "SELECT i.id, i.descripcion, i.monto, i.fecha_registro, 'Ingreso' as tipo_mov 
            FROM tbl_ingreso i WHERE i.tbl_registro_id = ?
            UNION ALL
            SELECT e.id, e.descripcion, e.monto, e.fecha_registro, 'Egreso' as tipo_mov 
            FROM tbl_egreso e WHERE e.tbl_registro_id = ?
            ORDER BY fecha_registro DESC LIMIT 10";

    $sql = $db->prepare($sql);
    $sql->bindValue(1, $id);
    $sql->bindValue(2, $id);
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_OBJ);

    $Array = [];
    foreach ($resultado as $d) {
        $Array[] = [
            'id' => (int)$d->id,
            '01_descripcion' => $d->descripcion,
            '02_monto' => $d->monto,
            '03_tipo' => $d->tipo_mov, // Mostrará si es Ingreso o Egreso
            '04_fecha' => $d->fecha_registro
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
        
        if ($resultado) {
            return [
                'id' => (int)$resultado->id,
                'descripcion' => $resultado->descripcion,
                'monto' => $resultado->monto,
                'fecha_registro' => $resultado->fecha_registro,
                'fecha_pago' => $resultado->fecha_pago,
                'tbl_concepto_id' => (int)$resultado->tbl_concepto_id,
                'tbl_tipo_pago_id' => (int)$resultado->tbl_tipo_pago_id,
                'tbl_periodo_id' => (int)$resultado->tbl_periodo_id,
                'tbl_registro_id' => (int)$resultado->tbl_registro_id
            ];
        }
        return [];
    }

    public function insert_ingreso($descripcion, $monto, $fecha_registro, $fecha_pago, $tbl_concepto_id, $tbl_tipo_pago_id, $tbl_periodo_id, $tbl_registro_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "INSERT INTO `tbl_ingreso`(`descripcion`, `monto`, 
         `fecha_registro`, `fecha_pago`, `tbl_concepto_id`, `tbl_tipo_pago_id`, `tbl_periodo_id`, `tbl_registro_id`) 
        VALUES (?,?, CURDATE(), ?,?,?,?,?);"; 
        
        try {
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $descripcion);
            $sql->bindValue(2, $monto);
            $sql->bindValue(3, $fecha_pago);
            $sql->bindValue(4, $tbl_concepto_id);
            $sql->bindValue(5, $tbl_tipo_pago_id);
            $sql->bindValue(6, $tbl_periodo_id);
            $sql->bindValue(7, $tbl_registro_id);
            
            $resultado['estatus'] =  $sql->execute();
            $lastInserId =  $conectar->lastInsertId();
            if ($lastInserId != "0") {
                $resultado['id'] = (int)$lastInserId;
            }
            return $resultado;
        } catch (PDOException $e) {
            return ['estatus' => false, 'mensaje' => $e->getMessage()];
        }
    }

    public function update_ingreso($descripcion, $monto, $fecha_registro, $fecha_pago, $tbl_concepto_id, $tbl_tipo_pago_id, $tbl_periodo_id, $tbl_registro_id, $id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "UPDATE `tbl_ingreso` SET `descripcion`= ?, `monto`= ?, 
        `fecha_pago`= ? ,`tbl_concepto_id`= ? ,`tbl_tipo_pago_id`= ? ,`tbl_periodo_id`= ? ,`tbl_registro_id`= ? 
        WHERE id = ?;";
        
        try {
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $descripcion);
            $sql->bindValue(2, $monto);
            $sql->bindValue(3, $fecha_pago);
            $sql->bindValue(4, $tbl_concepto_id);
            $sql->bindValue(5, $tbl_tipo_pago_id);
            $sql->bindValue(6, $tbl_periodo_id);
            $sql->bindValue(7, $tbl_registro_id);
            $sql->bindValue(8, $id);
            
            $resultado['estatus'] = $sql->execute();
            return $resultado;
        } catch (PDOException $e) {
            return ['estatus' => false, 'mensaje' => $e->getMessage()];
        }
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

    public function get_suma($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT SUM(monto) as monto FROM tbl_ingreso WHERE tbl_registro_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->monto;
    }

    public function get_monto($id){
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "SELECT 
                    MAX(MONTHNAME(fecha)) as mes_nombre, 
                    MAX(YEAR(fecha)) as anio,
                    SUM(IF(tipo='ingreso', monto, 0)) as total_ingreso,
                    SUM(IF(tipo='egreso', monto, 0)) as total_egreso
                FROM (
                    SELECT fecha_pago as fecha, monto, 'ingreso' as tipo 
                    FROM tbl_ingreso 
                    WHERE tbl_registro_id = ? AND fecha_pago IS NOT NULL
                    
                    UNION ALL
                    
                    SELECT fecha_pago as fecha, monto, 'egreso' as tipo 
                    FROM tbl_egreso 
                    WHERE tbl_registro_id = ? AND fecha_pago IS NOT NULL
                ) as tabla_unida
                GROUP BY YEAR(fecha), MONTH(fecha)
                ORDER BY MAX(fecha) DESC;";

        try {
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $id);
            $stmt->execute();
            
            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            $Array = [];
            foreach ($resultado as $d) {
                $ingreso = (float)$d->total_ingreso;
                $egreso = (float)$d->total_egreso;
                
                $Array[] = [
                    'mes' => $d->mes_nombre . ' ' . $d->anio,
                    'ingreso' => $ingreso,
                    'egreso' => $egreso,
                    'balance' => $ingreso - $egreso
                ];
            }
            return $Array;

        } catch (PDOException $e) {
            return [];
        }
    }
}
?>