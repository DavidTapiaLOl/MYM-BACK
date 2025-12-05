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
        
        // CORRECCIÓN: Eliminamos 'monto_general' porque esa columna no existe en la tabla
        $Array = $resultado ? [
            'id' => (int)$resultado->id,
            'descripcion' => $resultado->descripcion,
            'monto' => $resultado->monto,
            // 'monto_general' => $resultado->monto_general,  <-- ELIMINAR ESTA LÍNEA
            'fecha_registro' => $resultado->fecha_registro,
            'fecha_pago' => $resultado->fecha_pago,
            'tbl_concepto_id' => (int)$resultado->tbl_concepto_id,
            'tbl_tipo_pago_id' => (int)$resultado->tbl_tipo_pago_id,
            'tbl_periodo_id' => (int)$resultado->tbl_periodo_id,
            'tbl_registro_id' => (int)$resultado->tbl_registro_id
        ] : [];
        return $Array;
    }

    public function insert_ingreso($descripcion, $monto, $fecha_registro, $fecha_pago, $tbl_concepto_id, $tbl_tipo_pago_id, $tbl_periodo_id, $tbl_registro_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        // CORRECCIÓN: Usamos CURDATE() directamente en el SQL
        $sql = "INSERT INTO `tbl_ingreso`(`descripcion`, `monto`, 
         `fecha_registro`, `fecha_pago`, `tbl_concepto_id`, `tbl_tipo_pago_id`, `tbl_periodo_id`, `tbl_registro_id`) 
        VALUES (?,?, CURDATE(), ?,?,?,?,?);"; // <-- Quitamos un ? y pusimos CURDATE()
        
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $descripcion);
        $sql->bindValue(2, $monto);
        // $sql->bindValue(3, $fecha_registro); <-- ELIMINADO
        $sql->bindValue(3, $fecha_pago);        // Recorremos índices
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
    }

    public function update_ingreso($descripcion, $monto, $fecha_registro, $fecha_pago, $tbl_concepto_id, $tbl_tipo_pago_id, $tbl_periodo_id, $tbl_registro_id, $id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        // CORRECCIÓN: Quitamos `fecha_registro` del UPDATE para que no se pueda cambiar
        $sql = "UPDATE `tbl_ingreso` SET `descripcion`= ?, `monto`= ?, 
        `fecha_pago`= ? ,`tbl_concepto_id`= ? ,`tbl_tipo_pago_id`= ? ,`tbl_periodo_id`= ? ,`tbl_registro_id`= ? 
        WHERE id = ?;";
        
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $descripcion);
        $sql->bindValue(2, $monto);
        // $sql->bindValue(3, $fecha_registro); <-- ELIMINADO
        $sql->bindValue(3, $fecha_pago);
        $sql->bindValue(4, $tbl_concepto_id);
        $sql->bindValue(5, $tbl_tipo_pago_id);
        $sql->bindValue(6, $tbl_periodo_id);
        $sql->bindValue(7, $tbl_registro_id);
        $sql->bindValue(8, $id);
        
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
        
        // CORRECCIÓN SQL:
        // 1. Usamos MAX(MONTHNAME(fecha)) para cumplir con ONLY_FULL_GROUP_BY.
        // 2. Filtramos nulos en la subconsulta para evitar problemas.
        
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
                // Cálculo del balance
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
            // Si falla, devolvemos un array vacío para no romper la tabla del dashboard
            // Opcional: Podrías loguear el error: error_log($e->getMessage());
            return [];
        }
    }


    
}
