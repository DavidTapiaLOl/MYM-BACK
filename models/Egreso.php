<?php
class Egreso extends Conectar
{
    public function get_egreso($id)
    {
        $db = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    e.*,
                    c.nombre as nombre_concepto,
                    tp.nombre as nombre_tipo_pago,
                    p.nombre as nombre_periodo
                FROM tbl_egreso e
                LEFT JOIN tbl_concepto c ON e.tbl_concepto_id = c.id
                LEFT JOIN tbl_tipo_pago tp ON e.tbl_tipo_pago_id = tp.id
                LEFT JOIN tbl_periodo p ON e.tbl_periodo_id = p.id
                WHERE e.tbl_registro_id = ?"; 

        $sql = $db->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_OBJ);

        $Array = [];
        foreach ($resultado as $d) {
            $Array[] = [
                'id' => (int)$d->id,
                'tbl_concepto_id' => (int)$d->tbl_concepto_id,
                'tbl_tipo_pago_id' => (int)$d->tbl_tipo_pago_id,
                'tbl_periodo_id' => (int)$d->tbl_periodo_id,
                'tbl_registro_id' => (int)$d->tbl_registro_id,
                'es_fijo' => (int)$d->es_fijo,
                'fecha_limite' => $d->fecha_limite,
                'fecha_pago' => $d->fecha_pago,

               
                '01_descripcion' => $d->descripcion,
                '02_monto' => $d->monto,
                '03_concepto' => $d->nombre_concepto ? $d->nombre_concepto : 'Sin Concepto', 
                '04_periodo' => $d->nombre_periodo ? $d->nombre_periodo : 'Sin Periodo',
                '05_tipo_pago' => $d->nombre_tipo_pago ? $d->nombre_tipo_pago : 'Sin Tipo',
                '06_fecha' => $d->fecha_registro
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
        
        if ($resultado) {
            return [
                'id' => (int)$resultado->id,
                'descripcion' => $resultado->descripcion,
                'monto' => $resultado->monto, 
                'fecha_limite' => $resultado->fecha_limite,
                'fecha_registro' => $resultado->fecha_registro,
                'fecha_pago' => $resultado->fecha_pago,
                'tbl_concepto_id' => (int)$resultado->tbl_concepto_id,
                'tbl_tipo_pago_id' => (int)$resultado->tbl_tipo_pago_id,
                'tbl_periodo_id' => (int)$resultado->tbl_periodo_id,
                'tbl_registro_id' => (int)$resultado->tbl_registro_id,
                'es_fijo' => (int)$resultado->es_fijo 
            ];
        }
        return [];
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

    public function get_grafica_conceptos($id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    c.nombre as concepto,
                    p.nombre as periodo,
                    SUM(e.monto) as total
                FROM tbl_egreso e
                LEFT JOIN tbl_concepto c ON e.tbl_concepto_id = c.id
                LEFT JOIN tbl_periodo p ON e.tbl_periodo_id = p.id
                WHERE e.tbl_registro_id = ?
                GROUP BY c.nombre, p.nombre
                ORDER BY total DESC;";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [];
        $todosLosPeriodos = []; 

        foreach ($rows as $r) {
            // Protección contra nulos si el LEFT JOIN no encuentra nada
            $concepto = $r->concepto ? $r->concepto : 'Otros';
            $periodo = $r->periodo ? $r->periodo : 'Único';
            
            $montoRaw = (float)$r->total;
            $montoMensual = 0;

            switch ($periodo) {
                case 'Semanal': $montoMensual = $montoRaw * 4; break;
                case 'Quincenal': $montoMensual = $montoRaw * 2; break;
                case 'Mensual': $montoMensual = $montoRaw * 1; break;
                case 'Bimestral': $montoMensual = $montoRaw / 2; break;
                case 'Semestral': $montoMensual = $montoRaw / 6; break;
                case 'Anual': $montoMensual = $montoRaw / 12; break;
                default: $montoMensual = $montoRaw; break;
            }

            if (!isset($data[$concepto])) {
                $data[$concepto] = ['category' => $concepto];
            }
            $data[$concepto][$periodo] = round($montoMensual, 2);
            $todosLosPeriodos[$periodo] = true;
        }

        $listaPeriodos = array_keys($todosLosPeriodos);

        foreach ($data as &$fila) {
            foreach ($listaPeriodos as $p) {
                if (!isset($fila[$p])) {
                    $fila[$p] = 0;
                }
            }
        }
        return array_values($data);
    }
}
?>