<?php
// 1. Permitir acceso desde cualquier origen (necesario para que localhost:4200 hable con localhost:80)
header("Access-Control-Allow-Origin: *");

// 2. Permitir los headers específicos.
// IMPORTANTE: Incluimos 'Simpleauthb2b' y 'Authorization' que tu Front envía en cada petición.
header("Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Simpleauthb2b");

// 3. Permitir los verbos HTTP que usa la aplicación
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

// 4. Definir que siempre devolvemos JSON
header("Content-type: application/json; charset=utf-8");

// 5. Bloque para manejar la petición "OPTIONS" (Preflight)
// Cuando el navegador pregunta "¿puedo conectarme?", respondemos OK y cortamos la ejecución aquí.
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

class Conectar
{
    protected $db;
    
    protected function Conexion()
    {
        try {
            $NAMEDB = 'proyectoFinanciero';
            // 'db' es el nombre del servicio en tu docker-compose.yml
            $HOST = 'db'; 
            $USER = 'root'; 
            $PASSWORD = 'root';
            
            // Cadena de conexión PDO
            $conectar = $this->db = new PDO("mysql:host=$HOST;dbname=$NAMEDB", "$USER", "$PASSWORD");
            return $conectar;
        } catch (Exception $e) {
            print "¡Error BD!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
    public function set_names()
    {
        return $this->db->query("SET NAMES 'utf8'");
    }
}
