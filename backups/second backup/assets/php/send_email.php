<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Server-side validation
    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Proszę podać imię'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Proszę podać prawidłowy adres mailowy'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (empty($subject)) {
        echo json_encode(['status' => 'error', 'message' => 'Proszę podać temat'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Proszę podać treść wiadomości'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $to = "kontakt@ga-meblobud.pl";
    $from = "kontakt@ga-meblobud.pl";
    $headers = "From: $from\r\nReply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body = "Imię: $name\nEmail: $email\nTemat: $subject\nWiadomość:\n$message";

    if (mail($to, $subject, $body, $headers)) {
        $userSubject = "Wiadomość wysłana do G.A Meblobud";
        $userBody = "Dziękujemy, wkrótce się z Tobą skontaktujemy.";
        $userHeaders = "From: $from\r\nReply-To: $to\r\n";
        $userHeaders .= "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($email, $userSubject, $userBody, $userHeaders);

        echo json_encode(['status' => 'success', 'message' => 'Wiadomość wysłana! Dziękujemy.'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd wysyłania wiadomości. Spróbuj ponownie.'], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe żądanie.'], JSON_UNESCAPED_UNICODE);
}
?>