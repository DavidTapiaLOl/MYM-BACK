<?php
class Egreso extends Conectar
{
    public function get_egreso($id)
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_egreso WHERE tbl_registro_id = ?;";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, '01_descripcion' => $d->descripcion,
                '02_monto' => $d->monto, '03_fecha_limite' => $d->fecha_limite,'04_fecha_registro' => $d->fecha_registro,
                '05_fecha_pago' => $d->fecha_pago,'06_tbl_concepto_id' => (int)$d->tbl_concepto_id,
                '07_tbl_tipo_pago_id' => (int)$d->tbl_tipo_pago_id,'08_tbl_periodo_id' => (int)$d->tbl_periodo_id,
                '09_tbl_registro_id' => (int)$d->tbl_registro_id,
                'es_fijo' => (int)$d->es_fijo 
            ];
        }
        return $Array;
    }

    public function get_egreso_x_id($registro_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_egreso WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $registro_id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        $Array = $resultado ? [
            'id' => (int)$resultado->id,'descripcion' => $resultado->descripcion,
            'monto' => $resultado->monto, 
            'fecha_limite' => $resultado->fecha_limite,'fecha_registro' => $resultado->fecha_registro,
            'fecha_pago' => $resultado->fecha_pago,'tbl_concepto_id' => (int)$resultado->tbl_concepto_id,
            'tbl_tipo_pago_id' => (int)$resultado->tbl_tipo_pago_id,'tbl_periodo_id' => (int)$resultado->tbl_periodo_id,
            'tbl_registro_id' => (int)$resultado->tbl_registro_id,
            'es_fijo' => (int)$resultado->es_fijo 
        ] : [];
        return $Array;
    }

    public function insert_egreso($descripcion, $monto, $fecha_limite, $fecha_registro, $fecha_pago, $tbl_concepto_id, $tbl_tipo_pago_id, $tbl_periodo_id, $tbl_registro_id, $es_fijo)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "INSERT INTO `tbl_egreso`(`descripcion`, `monto`, `fecha_limite`,
         `fecha_registro`, `fecha_pago`, `tbl_concepto_id`, `tbl_tipo_pago_id`, `tbl_periodo_id`, `tbl_registro_id`, `es_fijo`) 
        VALUES (?,?,?, CURDATE(), ?,?,?,?,?,?);"; 
        
        try {
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $descripcion);
            $sql->bindValue(2, $monto);
            
            if (empty($fecha_limite)) {
                $sql->bindValue(3, null, PDO::PARAM_NULL);
            } else {
                $sql->bindValue(3, $fecha_limite);
            }

            $sql->bindValue(4, $fecha_pago);
            $sql->bindValue(5, $tbl_concepto_id);
            $sql->bindValue(6, $tbl_tipo_pago_id);
            $sql->bindValue(7, $tbl_periodo_id);
            $sql->bindValue(8, $tbl_registro_id);
            $sql->bindValue(9, $es_fijo);
            
            $resultado['estatus'] =  $sql->execute();
            $lastInserId =  $conectar->lastInsertId();
            if ($lastInserId != "0") {
                $resultado['id'] = (int)$lastInserId;
            }
            return $resultado;

        } catch (PDOException $e) {
            return ['estatus' => false, 'mensaje' => 'Error BD: ' . $e->getMessage()];
        }
    }

    public function update_egreso($descripcion, $monto, $fecha_limite, $fecha_registro, $fecha_pago, $tbl_concepto_id, $tbl_tipo_pago_id, $tbl_periodo_id, $tbl_registro_id, $es_fijo, $id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "UPDATE `tbl_egreso` SET `descripcion`= ?, `monto`= ?,`fecha_limite`= ? ,
        `fecha_pago`= ? ,`tbl_concepto_id`= ? ,`tbl_tipo_pago_id`= ? ,`tbl_periodo_id`= ? ,`tbl_registro_id`= ?, `es_fijo`= ? 
        WHERE id = ?;";
        
        try {
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $descripcion);
            $sql->bindValue(2, $monto);
            
            if (empty($fecha_limite)) {
                $sql->bindValue(3, null, PDO::PARAM_NULL);
            } else {
                $sql->bindValue(3, $fecha_limite);
            }
            
            $sql->bindValue(4, $fecha_pago);
            $sql->bindValue(5, $tbl_concepto_id);
            $sql->bindValue(6, $tbl_tipo_pago_id);
            $sql->bindValue(7, $tbl_periodo_id);
            $sql->bindValue(8, $tbl_registro_id);
            $sql->bindValue(9, $es_fijo);
            $sql->bindValue(10, $id);
            
            $resultado['estatus'] = $sql->execute();
            return $resultado;

        } catch (PDOException $e) {
            return ['estatus' => false, 'mensaje' => 'Error BD: ' . $e->getMessage()];
        }
    }

    public function delete($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM `tbl_egreso` WHERE id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $resultado['estatus'] = $sql->execute();
        return $resultado;
    }

    public function get_egreso2($id)
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_egreso WHERE tbl_registro_id = ?;";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id, '01_descripcion' => $d->descripcion,
                '02_monto' => $d->monto,'tbl_concepto_id' => (int)$d->tbl_concepto_id,
                'tbl_tipo_pago_id' => (int)$d->tbl_tipo_pago_id,'tbl_periodo_id' => (int)$d->tbl_periodo_id,
                'tbl_registro_id' => (int)$d->tbl_registro_id, '03_tipo' => 'Egreso',
                '03_fecha' => $d->fecha_registro,
                '04_tipo' => 'Egreso',
                'es_fijo' => (int)$d->es_fijo
            ];
        }
        return $Array;
    }

    public function get_suma($id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT SUM(monto) as monto FROM tbl_egreso WHERE tbl_registro_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->monto;
    }

    // --- ESTA ES LA FUNCIÓN QUE FALTABA ---
    public function get_grafica_conceptos($id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    c.nombre as concepto,
                    p.nombre as periodo,
                    SUM(e.monto) as total
                FROM tbl_egreso e
                INNER JOIN tbl_concepto c ON e.tbl_concepto_id = c.id
                INNER JOIN tbl_periodo p ON e.tbl_periodo_id = p.id
                WHERE e.tbl_registro_id = ?
                GROUP BY c.nombre, p.nombre
                ORDER BY total DESC;";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [];
        
        foreach ($rows as $r) {
            $concepto = $r->concepto;
            $periodo = $r->periodo;
            $total = (float)$r->total;

            if (!isset($data[$concepto])) {
                $data[$concepto] = ['category' => $concepto];
            }
            // Agregamos la propiedad dinámica (ej. "Semanal": 500)
            $data[$concepto][$periodo] = $total;
        }

        return array_values($data);
    }
}
?>