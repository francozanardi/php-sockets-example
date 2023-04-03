<?php

require_once('util/SocketReader.php');
require_once('util/SocketWriter.php');

use util\SocketReader;
use util\SocketWriter;

function setUpClientSocket(string $ip, int $port): Socket {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$socket) {
        die('Error creating socket: ' . socket_strerror(socket_last_error()));
    }
    $socketConnected = socket_connect($socket, $ip, $port);
    if (!$socketConnected) {
        die('Error connecting socket: ' . socket_strerror(socket_last_error()));
    }
    return $socket;
}

function getNameReceived(array $argv): string {
    $name = $argv[1] ?? '';
    if (empty($name)) {
        die('Debes ingresar un nombre válido.');
    }
    return $name;
}

function unserializeResponse(string $response): array {
    $serializedResponse = json_decode($response, true);
    if (!is_array($serializedResponse)) {
        throw new Exception('Invalid server response');
    }
    return $serializedResponse;
}

function showMessages(array $messages): void {
    if (empty($messages)) {
        showMessage('No se encontraron mesansajes asociados');
        return;
    }
    showMessage('Se encontraron los siguientes mensajes:');
    foreach ($messages as $message) {
        showMessage('---------------------------');
        showMessage('Sender: ' . $message['sender']);
        showMessage('Receiver: ' . $message['receiver']);
        showMessage('Message: ' . $message['message']);
    }
    showMessage('---------------------------');
}

function showPhotoFilenames(array $photos): void {
    if (empty($photos)) {
        showMessage('No se encontraron archivos de fotos asociados');
        return;
    }
    showMessage('Se encontraron los siguientes archivos de fotos:');
    foreach ($photos as $photo) {
        showMessage('* ' . $photo);
    }
}

function showServerResponse(array $response): void {
    $messages = $response['messages'] ?? [];
    $photos = $response['photos'] ?? [];
    showMessages($messages);
    showMessage('');
    showPhotoFilenames($photos);
}

function handleServerResponse(string $response): void {
    switch ($response) {
        case 'NOT_FOUND':
            showMessage('El nombre proporcionado no ha sido encontrado en el servidor');
            break;
        case 'UNEXPECTED_ERROR':
            showMessage('Error inesperado en el servidor');
            break;
        default:
            $unserializedResponse = unserializeResponse($response);
            showServerResponse($unserializedResponse);
    }
}

function showMessage(string $message): void {
    echo $message . PHP_EOL;
}

$nameServerIp = '127.0.0.1';
$nameServerPort = 9000;
$socketReader = new SocketReader();
$socketWriter = new SocketWriter();

$name = getNameReceived($argv);
$socket = setUpClientSocket($nameServerIp, $nameServerPort);
$socketWriter->write($socket, $name);
$response = $socketReader->read($socket);
try {
    handleServerResponse($response);
} catch (Exception $exception) {
    showMessage($exception->getMessage());
}
socket_close($socket);
