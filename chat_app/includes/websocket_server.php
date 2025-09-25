<?php
// A simplified WebSocket server implementation for demonstration purposes.
$host = '127.0.0.1'; // localhost
$port = 8080;

$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
if (!$server) {
    die("Server creation failed: $errstr ($errno)");
}

$clients = [];

function perform_handshake($socket) {
    $buffer = fread($socket, 4096);
    $headers = [];
    $lines = explode("\r\n", $buffer);

    foreach ($lines as $line) {
        $parts = explode(': ', $line, 2);
        if (isset($parts[1])) {
            $headers[$parts[0]] = $parts[1];
        }
    }

    if (!isset($headers['Sec-WebSocket-Key'])) {
        return false;
    }

    $secKey = $headers['Sec-WebSocket-Key'];
    $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
    $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
               "Upgrade: websocket\r\n" .
               "Connection: Upgrade\r\n" .
               "Sec-WebSocket-Accept: $secAccept\r\n\r\n";
    fwrite($socket, $upgrade);
    return true;
}

function decode_message($data) {
    $length = ord($data[1]) & 127;
    if ($length == 126) {
        $masks = substr($data, 4, 4);
        $payload = substr($data, 8);
    } elseif ($length == 127) {
        $masks = substr($data, 10, 4);
        $payload = substr($data, 14);
    } else {
        $masks = substr($data, 2, 4);
        $payload = substr($data, 6);
    }
    
    $text = '';
    for ($i = 0; $i < strlen($payload); ++$i) {
        $text .= $payload[$i] ^ $masks[$i % 4];
    }
    return $text;
}

function encode_message($text) {
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);

    if ($length <= 125) {
        $header = pack('C*', $b1, $length);
    } elseif ($length > 125 && $length < 65536) {
        $header = pack('C*', $b1, 126, ($length >> 8) & 0xFF, $length & 0xFF);
    } else {
        $header = pack('C*', $b1, 127, ($length >> 56) & 0xFF, ($length >> 48) & 0xFF, ($length >> 40) & 0xFF, ($length >> 32) & 0xFF, ($length >> 24) & 0xFF, ($length >> 16) & 0xFF, ($length >> 8) & 0xFF, $length & 0xFF);
    }
    return $header . $text;
}

while (true) {
    $read_sockets = $clients;
    $read_sockets[] = $server;

    $write = null;
    $except = null;

    if (stream_select($read_sockets, $write, $except, 0) < 1) {
        continue;
    }

    if (in_array($server, $read_sockets)) {
        $new_client = stream_socket_accept($server);
        if ($new_client) {
            if (perform_handshake($new_client)) {
                $clients[] = $new_client;
                echo "New client connected!\n";
            } else {
                fclose($new_client);
            }
        }
        unset($read_sockets[array_search($server, $read_sockets)]);
    }

    foreach ($read_sockets as $socket) {
        $data = fread($socket, 4096);
        if ($data === false || empty($data)) {
            $key = array_search($socket, $clients);
            if ($key !== false) {
                unset($clients[$key]);
            }
            fclose($socket);
            echo "Client disconnected!\n";
            continue;
        }

        $message = decode_message($data);
        echo "Received: " . $message . "\n";
        
        foreach ($clients as $client) {
            fwrite($client, encode_message($message));
        }
    }
}
fclose($server);