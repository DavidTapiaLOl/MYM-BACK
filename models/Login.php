<?php
class Login extends Conectar
{

    public function Login_access($usuario, $password)
    {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tbl_registro WHERE usuario = ?";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $usuario);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        if (!empty($resultado)) {
            // Verificar si el password es correcto
            if (password_verify($password, $resultado->password)){
                $Array = [
                    'id' => (int)$resultado->id,
                    'usuario' => $resultado->usuario,
                    'password' => $resultado->password,
                    'estatus' => true,
                ];
            } else {
                $Array['mensaje'] = 'Contraseña incorrecta';
            }
        }
        return $Array;
    }
}