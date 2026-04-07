<?php

$TOKEN = getenv("8781247105:AAFeK81hPYg9xTg7fasA2lGwIdFL0j8Un_M");
$API_URL = "https://api.telegram.org/bot$TOKEN/";

// Obtener datos
$update = json_decode(file_get_contents("php://input"), true);

// Productos
$productos = [
    "pan" => ["nombre" => "Pan", "precio" => 1000],
    "leche" => ["nombre" => "Leche", "precio" => 1200],
    "huevos" => ["nombre" => "Huevos", "precio" => 2500]
];

// Función enviar mensaje
function sendMessage($chat_id, $text, $keyboard = null) {
    global $API_URL;

    $data = [
        "chat_id" => $chat_id,
        "text" => $text
    ];

    if ($keyboard) {
        $data["reply_markup"] = json_encode($keyboard);
    }

    file_get_contents($API_URL . "sendMessage?" . http_build_query($data));
}

// MENÚ PRINCIPAL
function menuPrincipal() {
    return [
        "inline_keyboard" => [
            [["text" => "🛒 Ver productos", "callback_data" => "ver_productos"]],
            [["text" => "🧾 Ver carrito", "callback_data" => "carrito"]]
        ]
    ];
}

// LISTA DE PRODUCTOS
function listaProductos($productos) {
    $keyboard = [];

    foreach ($productos as $key => $prod) {
        $keyboard[] = [
            ["text" => $prod["nombre"] . " - $" . $prod["precio"], "callback_data" => $key]
        ];
    }

    $keyboard[] = [["text" => "⬅️ Volver", "callback_data" => "menu"]];

    return ["inline_keyboard" => $keyboard];
}

// INICIO
if (isset($update["message"])) {

    $chat_id = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];

    if ($text == "/start") {
        sendMessage($chat_id, "🏪 Bienvenido al minimarket\nSelecciona una opción:", menuPrincipal());
    }
}

// BOTONES
if (isset($update["callback_query"])) {

    $callback = $update["callback_query"];
    $data = $callback["data"];
    $chat_id = $callback["message"]["chat"]["id"];

    // Mostrar productos
    if ($data == "ver_productos") {
        sendMessage($chat_id, "🛒 Lista de productos:", listaProductos($productos));
    }

    // Volver al menú
    elseif ($data == "menu") {
        sendMessage($chat_id, "🏪 Menú principal:", menuPrincipal());
    }

    // Carrito (simulado)
    elseif ($data == "carrito") {
        sendMessage($chat_id, "🧾 Tu carrito está vacío (próximamente 😄)");
    }

    // Producto seleccionado
    elseif (isset($productos[$data])) {
        $prod = $productos[$data];

        $mensaje = "🛒 Agregaste:\n" .
                   $prod["nombre"] . "\n💰 $" . $prod["precio"];

        sendMessage($chat_id, $mensaje);
    }

    else {
        sendMessage($chat_id, "❌ Opción no válida");
    }
}
?>
