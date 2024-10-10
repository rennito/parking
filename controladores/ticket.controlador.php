<?php
// ticket.controlador.php

require_once "../modelos/ticket.modelo.php"; // Importa el modelo correspondiente

class ControladorTicket {
    public static function ctrGuardarTicket($data) {
        if (isset($data['registro_id']) && isset($data['archivo_pdf'])) {
            return ModeloTicket::mdlGuardarTicket($data);
        }
        return ["success" => false, "message" => "Datos del ticket incompletos."];
    }

    public static function ctrImprimirTicket($data) {
        $response = self::enviarImpresion($data);
        return $response; // Devuelve la respuesta de impresión
    }

    private static function enviarImpresion($data) {
        $url = "http://localhost:8080/api/imprimirTicket"; // Ajusta la URL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            return ["success" => true, "data" => json_decode($response, true)];
        }
        return ["success" => false, "message" => "Error de comunicación con el servidor de impresión."];
    }
}
