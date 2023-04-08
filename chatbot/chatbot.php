<?php
// chatbot.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message = strtolower(trim($data['message']));

    $reply = '';

    if (preg_match('/\b(hello|hi|hey)\b/', $message)) {
        $reply = 'Hello! How can I help you?';
    } elseif (preg_match('/\b(thank you|thanks)\b/', $message)) {
        $reply = 'You\'re welcome! Let me know if you need any more assistance.';
    } elseif (preg_match('/\b(bye|goodbye)\b/', $message)) {
        $reply = 'Goodbye! Have a great day!';
    } else {
        $reply = 'I\'m not sure how to help with that. Please try rephrasing your question.';
    }

    echo json_encode(['reply' => $reply]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
