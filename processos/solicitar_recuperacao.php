<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../conexao.php';

function voltarRecuperacao(string $mensagem): never {
    $_SESSION['mensagem_recuperacao'] = $mensagem;
    header('Location: ../recuperar_senha.php?etapa=codigo', true, 303);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf_recuperacao'] ?? '', $_POST['csrf'])) {
    http_response_code(403);
    exit('Solicitação inválida. Atualize a página e tente novamente.');
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
    $_SESSION['mensagem_recuperacao'] = 'Informe um e-mail válido.';
    header('Location: ../recuperar_senha.php', true, 303);
    exit();
}

try {
    $busca = $pdo->prepare('SELECT id_usuario, email, nome FROM usuarios WHERE email = :email LIMIT 1');
    $busca->execute([':email' => $email]);
    $usuario = $busca->fetch();
    if ($usuario) {
        $ultima = $pdo->prepare('SELECT solicitado_em FROM codigos_recuperacao WHERE id_usuario = ?');
        $ultima->execute([$usuario['id_usuario']]);
        $solicitadoEm = $ultima->fetchColumn();
        if ($solicitadoEm && strtotime($solicitadoEm . ' UTC') > time() - 60) {
            voltarRecuperacao('Se houver uma conta com esse e-mail, enviaremos um código. Confira também a caixa de spam.');
        }
        $codigo = (string) random_int(100000, 999999);
        $hash = password_hash($codigo, PASSWORD_DEFAULT);
        $grava = $pdo->prepare(
            'INSERT INTO codigos_recuperacao (id_usuario, codigo_hash, expira_em, tentativas, solicitado_em)
             VALUES (:id_usuario, :codigo_hash, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 10 MINUTE), 0, UTC_TIMESTAMP())
             ON DUPLICATE KEY UPDATE codigo_hash = VALUES(codigo_hash), expira_em = VALUES(expira_em), tentativas = 0, solicitado_em = UTC_TIMESTAMP()'
        );
        $grava->execute([':id_usuario' => $usuario['id_usuario'], ':codigo_hash' => $hash]);

        $apiKey = getenv('RESEND_API_KEY') ?: '';
        $mailFrom = getenv('MAIL_FROM') ?: '';
        if ($apiKey === '' || $mailFrom === '') {
            error_log('Vitalize: configure RESEND_API_KEY e MAIL_FROM para enviar códigos de recuperação.');
        } else {
            $payload = json_encode([
                'from' => $mailFrom,
                'to' => [$usuario['email']],
                'subject' => 'Código para recuperar sua senha — Vitalize',
                'text' => "Olá, {$usuario['nome']}!\n\nSeu código para redefinir a senha é: {$codigo}\n\nEle expira em 10 minutos e pode ser usado uma única vez. Se você não solicitou a recuperação, ignore este e-mail."
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $curl = curl_init('https://api.resend.com/emails');
            curl_setopt_array($curl, [
                CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 20,
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json'],
                CURLOPT_POSTFIELDS => $payload, CURLOPT_PROTOCOLS => CURLPROTO_HTTPS
            ]);
            $resposta = curl_exec($curl);
            $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($resposta === false || $status < 200 || $status >= 300) {
                error_log('Vitalize: falha ao enviar o código de recuperação (HTTP ' . $status . ').');
            }
            curl_close($curl);
        }
    }
} catch (Throwable $e) {
    error_log('Vitalize: falha no pedido de recuperação: ' . $e->getMessage());
}

voltarRecuperacao('Se houver uma conta com esse e-mail, enviaremos um código. Confira também a caixa de spam.');
