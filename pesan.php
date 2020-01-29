<?php
// ambil databasenya
include 'query.php';
// ----------- pantengin mulai ini
function sendMessage($idchat, $pesan)
{

    $data = [
        'chat_id'             => $idchat,
        'text'                => $pesan,
    ];

    return apiRequest('sendMessage', $data);
}

function processMessage($message)
{
    if (isset($message['message'])) {
        $sumber = $message['message'];
        include 'perintah.php';
    }

    if (isset($message['edited_message'])) {
        $sumber = $message['edited_message'];
        include 'perintah.php';
    }
}

// pencetakan versi dan info waktu server, berfungsi jika test hook
echo 'Ver. ' . myVERSI . ' OK Start!' . PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL;


function printUpdates($result)
{

    foreach ($result as $obj) {
        // echo $obj['message']['text'].PHP_EOL;
        processMessage($obj);
        $last_id = $obj['update_id'];
    }

    return $last_id;
}
?>