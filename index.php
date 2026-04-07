<?php

$TOKEN = getenv("8781247105:AAFeK81hPYg9xTg7fasA2lGwIdFL0j8Un_M");
$API_URL = "https://api.telegram.org/bot$TOKEN/";

// Obtener datos
$update = json_decode(file_get_contents("php://input"), true);

// Lista de productos y pasillos
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

    $data = [
        "chat_id" => $chat_id,
        "text" => $text
    ];

    file_get_contents($API_URL . "sendMessage?" . http_build_query($data));
}

// Cuando el usuario escribe
if (isset($update["message"])) {

    $chat_id = $update["message"]["chat"]["id"];
    $text = strtolower($update["message"]["text"]); // pasar a minúsculas

    // Comando start
    if ($text == "/start") {
        sendMessage($chat_id, "🏪 Bienvenido al supermercado\n\nEscribe el nombre de un producto para saber su pasillo.\n\nEjemplo: leche, pan, carne");
        exit;
    }

    // Buscar producto
    if (isset($pasillos[$text])) {
        $respuesta = "📍 El producto *$text* está en " . $pasillos[$text];
    } else {
        $respuesta = "❌ Producto no encontrado.\nIntenta con: carne, leche, pan, detergente...";
    }

    sendMessage($chat_id, $respuesta);
}
?>
