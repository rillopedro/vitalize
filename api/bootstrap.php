<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/iniciar_sessao.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

require_once dirname(__DIR__) . '/conexao.php';

function api_responder(int $status, array $dados): never
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function api_body(): array
{
    if (str_contains(strtolower($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json')) {
        $dados = json_decode(file_get_contents('php://input') ?: '', true);
        return is_array($dados) ? $dados : [];
    }
    return $_POST;
}

function api_usuario(): int
{
    $id = filter_var($_SESSION['id_usuario'] ?? null, FILTER_VALIDATE_INT);
    if (!$id || $id < 1) api_responder(401, ['erro' => 'Autenticação necessária.']);
    $stmt = $GLOBALS['pdo']->prepare('SELECT versao_sessao FROM usuarios WHERE id_usuario=? LIMIT 1');
    $stmt->execute([$id]);
    $versaoBanco = $stmt->fetchColumn();
    if ($versaoBanco === false || (int) ($_SESSION['versao_sessao'] ?? -1) !== (int) $versaoBanco) {
        $_SESSION = [];
        session_destroy();
        api_responder(401, ['erro' => 'Sua sessão expirou. Entre novamente.']);
    }
    return $id;
}

function api_exigir_admin(PDO $pdo): int
{
    $id = api_usuario();
    $stmt = $pdo->prepare("SELECT tipo FROM usuarios WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() !== 'admin') api_responder(403, ['erro' => 'Acesso restrito a administradores.']);
    return $id;
}

function api_aplicar_limite(PDO $pdo, string $acao, string $identificador, int $maximo, int $janelaSegundos): void
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'desconhecido');
    $intervalo = max(1, min($janelaSegundos, 86400));
    $escopos = [[$identificador, $maximo]];
    if ($identificador !== '*ip*') $escopos[] = ['*ip*', $maximo * 5];
    foreach ($escopos as [$escopo, $limite]) {
        $chave = hash('sha256', $acao . "\0" . $ip . "\0" . strtolower($escopo));
        $sql = "INSERT INTO api_rate_limits (chave, janela_inicio, tentativas) VALUES (?, UTC_TIMESTAMP(), 1) ON DUPLICATE KEY UPDATE tentativas=IF(janela_inicio <= UTC_TIMESTAMP() - INTERVAL {$intervalo} SECOND, 1, tentativas + 1), janela_inicio=IF(janela_inicio <= UTC_TIMESTAMP() - INTERVAL {$intervalo} SECOND, UTC_TIMESTAMP(), janela_inicio)";
        $pdo->prepare($sql)->execute([$chave]);
        $stmt = $pdo->prepare('SELECT tentativas, GREATEST(1, ? - TIMESTAMPDIFF(SECOND, janela_inicio, UTC_TIMESTAMP())) AS aguarde FROM api_rate_limits WHERE chave=?');
        $stmt->execute([$intervalo, $chave]);
        $resultado = $stmt->fetch();
        if ((int) ($resultado['tentativas'] ?? 0) > $limite) {
            header('Retry-After: ' . (int) $resultado['aguarde']);
            api_responder(429, ['erro' => 'Muitas tentativas. Aguarde antes de tentar novamente.']);
        }
    }
    if (random_int(1, 100) === 1) $pdo->exec('DELETE FROM api_rate_limits WHERE janela_inicio < UTC_TIMESTAMP() - INTERVAL 2 DAY');
}

function api_exigir_csrf(): void
{
    $recebido = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $esperado = $_SESSION['csrf_api'] ?? '';
    if (!is_string($recebido) || !is_string($esperado) || $esperado === '' || !hash_equals($esperado, $recebido)) {
        api_responder(403, ['erro' => 'Token de segurança inválido. Atualize a página e tente novamente.']);
    }
}

function api_usuario_publico(array $usuario): array
{
    unset($usuario['senha'], $usuario['token_verificacao']);
    return $usuario;
}

function api_validar_link(?string $url): ?string
{
    $url = trim((string) $url);
    if ($url === '') return null;
    $esquema = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    if (strlen($url) > 2048 || !filter_var($url, FILTER_VALIDATE_URL) || !in_array($esquema, ['http', 'https'], true) || parse_url($url, PHP_URL_USER) !== null) {
        api_responder(422, ['erro' => 'Informe um link HTTP ou HTTPS válido.']);
    }
    return $url;
}

function api_data(PDO $pdo, int $idUsuario, bool $admin = false): array
{
    $stmt = $pdo->prepare('SELECT tipo FROM usuarios WHERE id_usuario = ?');
    $stmt->execute([$idUsuario]);
    $tipo = $stmt->fetchColumn();
    if ($admin && $tipo !== 'admin') api_responder(403, ['erro' => 'Acesso restrito a administradores.']);
    return ['tipo' => $tipo ?: 'usuario'];
}

