<?php
//fungsi memproses pesan
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
// fungsi mengirim pesan
function sendMessage($idchat, $pesan)
{

    $data = [
        'chat_id'             => $idchat,
        'text'                => $pesan,
    ];

    return apiRequest('sendMessage', $data);
}

// pencetakan versi dan info waktu server, berfungsi jika test hook
echo 'Ver. ' . myVERSI . ' OK Start!' . PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL;

//fungsi print update
function printUpdates($result)
{

    foreach ($result as $obj) {
        processMessage($obj);
        $last_id = $obj['update_id'];
    }

    return $last_id;
}
?>