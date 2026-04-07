<?php

$TOKEN = "8781247105:AAFeK81hPYg9xTg7fasA2lGwIdFL0j8Un_M";
$API_URL = "https://api.telegram.org/bot$TOKEN/";

// Obtener datos enviados por Telegram
$update = json_decode(file_get_contents("php://input"), true);

// Productos
$productos = [
    "hbo" => ["precio" => "$5.000", "stock" => 10],
    "disney" => ["precio" => "$4.000", "stock" => 7],
    "netflix" => ["precio" => "$6.000", "stock" => 5]
];

// Función para enviar mensajes
function sendMessage($chat_id, $text, $reply_markup = null) {
    global $API_URL;

    $data = [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "HTML"
    ];

    if ($reply_markup) {
        $data["reply_markup"] = json_encode($reply_markup);
    }

    file_get_contents($API_URL . "sendMessage?" . http_build_query($data));
}

// /start
if (isset($update["message"])) {

    $chat_id = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];

    if ($text == "/start") {

        $keyboard = [
            "inline_keyboard" => [
                [["text" => "HBO", "callback_data" => "hbo"]],
                [["text" => "Disney", "callback_data" => "disney"]],
                [["text" => "Netflix", "callback_data" => "netflix"]],
                [["text" => "Soporte", "callback_data" => "soporte"]]
            ]
        ];

        sendMessage($chat_id, "👋 Bienvenido\n\nSelecciona un producto:", $keyboard);
    }
}

// Botones
if (isset($update["callback_query"])) {

    $callback = $update["callback_query"];
    $data = $callback["data"];
    $chat_id = $callback["message"]["chat"]["id"];

    global $productos;

    if (isset($productos[$data])) {

        $info = $productos[$data];
        $mensaje = "📦 Producto: " . strtoupper($data) .
                   "\n💰 Precio: " . $info["precio"] .
                   "\n📊 Disponible: " . $info["stock"];

    } elseif ($data == "soporte") {

        $mensaje = "📞 Contacta con soporte: @tuusuario";

    } else {

        $mensaje = "❌ Opción no válida";
    }

    sendMessage($chat_id, $mensaje);
}

?>