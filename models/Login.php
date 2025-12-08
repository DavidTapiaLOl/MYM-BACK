<?php
class Login extends Conectar
{
    public function Login_access($usuario, $password)
    {
        $conectar = parent::conexion();
        parent::set_names();
        
        $sql = "SELECT * FROM tbl_registro WHERE usuario = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $usuario);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        
        // Inicializamos la respuesta por defecto como fallo
        $response = [
            'estatus' => false,
            'mensaje' => 'Usuario o contraseña incorrectos'
        ];

        if ($resultado) {
            // El usuario existe, verificamos el password
            if (password_verify($password, $resultado->password)) {
                // Contraseña correcta
                $response = [
                    'estatus' => true,
                    'id' => (int)$resultado->id,
                    'usuario' => $resultado->usuario,
                    'foto_perfil' => $resultado->foto_perfil, 
                    // No devolvemos el password por seguridad
                ];
            } else {
                $response['mensaje'] = 'Contraseña incorrecta';
            }
        } else {
            $response['mensaje'] = 'El usuario no existe';
        }
        
        return $response;
    }
}
?>