<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// TOKEN desde Railway
$TOKEN = getenv("TOKEN");
$API_URL = "https://api.telegram.org/bot$TOKEN/";

// Obtener datos
$update = json_decode(file_get_contents("php://input"), true);

// Si no hay datos, salir
if (!$update) {
    exit;
}

// Lista de productos
$pasillos = [
    "carne" => "Pasillo 1",
    "queso" => "Pasillo 1",
    "jamon" => "Pasillo 1",

    "leche" => "Pasillo 2",
    "yogurth" => "Pasillo 2",
    "cereal" => "Pasillo 2",

    "bebidas" => "Pasillo 3",
    "jugos" => "Pasillo 3",

    "pan" => "Pasillo 4",
    "pasteles" => "Pasillo 4",
    "tortas" => "Pasillo 4",

    "detergente" => "Pasillo 5",
    "lavaloza" => "Pasillo 5"
];

// Función enviar mensaje
function sendMessage($chat_id, $text) {
    global $API_URL;

    $url = $API_URL . "sendMessage";

    $data = [
        "chat_id" => $chat_id,
        "text" => $text
    ];

    file_get_contents($url . "?" . http_build_query($data));
}

// Procesar mensaje
if (isset($update["message"])) {

    $chat_id = $update["message"]["chat"]["id"];
    $text = strtolower(trim($update["message"]["text"]));

    // Inicio
    if ($text == "/start") {
        sendMessage($chat_id, "🏪 Bienvenido al supermercado\n\nEscribe un producto para saber su pasillo.\nEj: leche, pan, carne");
        exit;
    }

    // Buscar producto
    if (isset($pasillos[$text])) {
        sendMessage($chat_id, "📍 El producto '$text' está en " . $pasillos[$text]);
    } else {
        sendMessage($chat_id, "❌ Producto no encontrado\nIntenta con: carne, leche, pan...");
    }
}
?>
