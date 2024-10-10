<?php

class Conexion
{
    static public function conectar()
    {
        try {
            $link = new PDO(
                "mysql:host=127.0.0.1;port=3306;dbname=parking_system", // Corrección aquí
                "root",
                ""
            );

            $link->exec("set names utf8");

            return $link;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage(); // Manejo de excepciones
            return null; // Devolver null en caso de error
        }
    }
}
