<?php
declare(strict_types=1);

final class VitalizeController
{
public static function dispatch(PDO $pdo): void
{
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = preg_replace('#^.*?/api(?:/index\.php)?#', '', $path) ?: '/';
$path = '/' . trim($path, '/');
if ($path === '//') $path = '/';

if ($method === 'OPTIONS') {
    header('Allow: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    http_response_code(204);
    exit;
}

if ($method === 'GET' && $path === '/csrf') {
    $_SESSION['csrf_api'] ??= bin2hex(random_bytes(32));
    api_responder(200, ['csrf_token' => $_SESSION['csrf_api']]);
}
if ($method === 'GET' && ($path === '/' || $path === '/health')) {
    api_responder(200, ['api' => 'Vitalize', 'versao' => '1.0', 'status' => 'online']);
}

$body = api_body();
if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) api_exigir_csrf();

try {
    // Autenticação e cadastro
    if ($method === 'POST' && $path === '/auth/register') {
        $nome = trim((string) ($body['nome'] ?? ''));
        $sobrenome = trim((string) ($body['sobrenome'] ?? ''));
        $email = strtolower(trim((string) ($body['email'] ?? '')));
        $telefone = trim((string) ($body['telefone'] ?? ''));
        $senha = (string) ($body['senha'] ?? '');
        if (isset($body['confirmar_senha']) && (string) $body['confirmar_senha'] !== $senha) api_responder(422, ['erro' => 'As senhas não coincidem.']);
        if ($nome === '' || mb_strlen($nome) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150 || strlen($senha) < 6 || strlen($senha) > 72) {
            api_responder(422, ['erro' => 'Informe nome, e-mail válido e senha com pelo menos 6 caracteres.']);
        }
        api_aplicar_limite($pdo, 'register', $email, 5, 3600);
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, sobrenome, email, senha, telefone) VALUES (?, ?, ?, ?, ?)');
        try {
            $stmt->execute([$nome, $sobrenome ?: null, $email, password_hash($senha, PASSWORD_DEFAULT), $telefone ?: null]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') api_responder(409, ['erro' => 'Este e-mail já está cadastrado.']);
            throw $e;
        }
        $id = (int) $pdo->lastInsertId();
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = $id;
        $_SESSION['versao_sessao'] = 0;
        $_SESSION['nome'] = $nome;
        $_SESSION['email'] = $email;
        $_SESSION['sobrenome'] = $sobrenome;
        $_SESSION['telefone'] = $telefone;
        $_SESSION['foto_perfil'] = '';
        $_SESSION['csrf_api'] = bin2hex(random_bytes(32));
        api_responder(201, ['mensagem' => 'Cadastro realizado.', 'usuario' => ['id_usuario' => $id, 'nome' => $nome, 'sobrenome' => $sobrenome, 'email' => $email, 'telefone' => $telefone]]);
    }
    if ($method === 'POST' && $path === '/auth/login') {
        $email = strtolower(trim((string) ($body['email'] ?? '')));
        $senha = (string) ($body['senha'] ?? '');
        if ($email === '' || $senha === '') api_responder(422, ['erro' => 'Informe e-mail e senha.']);
        api_aplicar_limite($pdo, 'login', $email, 8, 900);
        $stmt = $pdo->prepare('SELECT id_usuario, nome, sobrenome, email, telefone, foto_perfil, senha, tipo, versao_sessao FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        if (!$usuario || !password_verify($senha, $usuario['senha'])) api_responder(401, ['erro' => 'E-mail ou senha inválidos.']);
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = (int) $usuario['id_usuario'];
        $_SESSION['versao_sessao'] = (int) $usuario['versao_sessao'];
        foreach (['nome', 'sobrenome', 'email', 'telefone', 'foto_perfil'] as $campo) $_SESSION[$campo] = $usuario[$campo] ?? '';
        $_SESSION['csrf_api'] = bin2hex(random_bytes(32));
        api_responder(200, ['mensagem' => 'Login realizado.', 'usuario' => api_usuario_publico($usuario)]);
    }
    if ($method === 'POST' && $path === '/auth/logout') {
        api_usuario();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
        }
        session_destroy();
        api_responder(200, ['mensagem' => 'Sessão encerrada.']);
    }

    // Perfil do usuário
    if ($path === '/me' && $method === 'GET') {
        $id = api_usuario();
        $stmt = $pdo->prepare('SELECT id_usuario, nome, sobrenome, email, telefone, foto_perfil, tipo, data_cadastro FROM usuarios WHERE id_usuario = ?');
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        if (!$usuario) api_responder(404, ['erro' => 'Usuário não encontrado.']);
        api_responder(200, ['usuario' => $usuario]);
    }
    if ($path === '/me' && in_array($method, ['PATCH', 'POST'], true)) {
        $id = api_usuario();
        $nome = trim((string) ($body['nome'] ?? ''));
        $sobrenome = trim((string) ($body['sobrenome'] ?? ''));
        $email = strtolower(trim((string) ($body['email'] ?? '')));
        $telefone = trim((string) ($body['telefone'] ?? ''));
        $senha = (string) ($body['senha'] ?? '');
        $senhaAtual = (string) ($body['senha_atual'] ?? '');
        if (isset($body['confirmar_senha']) && (string) $body['confirmar_senha'] !== $senha) api_responder(422, ['erro' => 'As senhas não coincidem.']);
        if ($nome === '' || strlen($nome) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150 || ($senha !== '' && (strlen($senha) < 6 || strlen($senha) > 72))) api_responder(422, ['erro' => 'Confira nome, e-mail e senha.']);
        if ($senha !== '') {
            $stmt = $pdo->prepare('SELECT senha, versao_sessao FROM usuarios WHERE id_usuario=?'); $stmt->execute([$id]); $credenciais = $stmt->fetch();
            if (!$credenciais || $senhaAtual === '' || !password_verify($senhaAtual, $credenciais['senha'])) api_responder(422, ['erro' => 'Informe sua senha atual corretamente para alterá-la.']);
        }
        $foto = null;
        if (isset($_FILES['foto_perfil']) && ($_FILES['foto_perfil']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            require_once dirname(__DIR__, 2) . '/processos/upload_helper.php';
            try { $foto = salvar_arquivo_upload($_FILES['foto_perfil'], 'usuarios'); }
            catch (Throwable $e) { api_responder(422, ['erro' => $e->getMessage()]); }
        }
        $sql = 'UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, telefone = ?';
        $params = [$nome, $sobrenome ?: null, $email, $telefone ?: null];
        if ($senha !== '') { $sql .= ', senha = ?, versao_sessao = versao_sessao + 1'; $params[] = password_hash($senha, PASSWORD_DEFAULT); }
        if ($foto !== null) { $sql .= ', foto_perfil = ?'; $params[] = $foto; }
        $sql .= ' WHERE id_usuario = ?'; $params[] = $id;
        try { $pdo->prepare($sql)->execute($params); }
        catch (PDOException $e) { if ($e->getCode() === '23000') api_responder(409, ['erro' => 'Este e-mail já está sendo usado.']); throw $e; }
        $_SESSION['nome'] = $nome; $_SESSION['sobrenome'] = $sobrenome; $_SESSION['email'] = $email; $_SESSION['telefone'] = $telefone;
        if ($foto !== null) $_SESSION['foto_perfil'] = $foto;
        if ($senha !== '') $_SESSION['versao_sessao'] = (int) ($_SESSION['versao_sessao'] ?? 0) + 1;
        api_responder(200, ['mensagem' => 'Perfil atualizado.', 'usuario' => ['id_usuario' => $id, 'nome' => $nome, 'sobrenome' => $sobrenome, 'email' => $email, 'telefone' => $telefone, 'foto_perfil' => $foto ?? ($_SESSION['foto_perfil'] ?? '')]]);
    }
    if ($path === '/me' && $method === 'DELETE') {
        $id = api_usuario();
        $pdo->beginTransaction();
        $pdo->prepare('DELETE FROM consultas WHERE id_usuario = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM relatos WHERE id_usuario = ?')->execute([$id]);
        $pdo->prepare('UPDATE grupos SET id_criador = NULL WHERE id_criador = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM usuarios WHERE id_usuario = ?')->execute([$id]);
        $pdo->commit();
        $_SESSION = []; session_destroy();
        api_responder(200, ['mensagem' => 'Conta excluída.']);
    }

    // Agenda de consultas e exames
    if ($path === '/appointments' && $method === 'GET') {
        $id = api_usuario();
        $stmt = $pdo->prepare('SELECT id_consulta, tipo, especialidade, data, horario, medico, nome_local, id_endereco, link_reuniao FROM consultas WHERE id_usuario = ? ORDER BY data ASC, horario ASC, id_consulta ASC');
        $stmt->execute([$id]);
        api_responder(200, ['consultas' => $stmt->fetchAll()]);
    }
    if ($path === '/appointments' && $method === 'POST') {
        $id = api_usuario();
        $tipo = trim((string) ($body['tipo'] ?? ''));
        $especialidade = trim((string) ($body['especialidade'] ?? ''));
        $data = trim((string) ($body['data'] ?? ''));
        $hora = trim((string) ($body['horario'] ?? ''));
        $dt = DateTime::createFromFormat('!Y-m-d', $data); $hr = DateTime::createFromFormat('!H:i', $hora);
        if (!in_array($tipo, ['Consulta', 'Exame'], true) || $especialidade === '' || !$dt || $dt->format('Y-m-d') !== $data || !$hr || $hr->format('H:i') !== $hora) api_responder(422, ['erro' => 'Confira tipo, especialidade, data e horário.']);
        $link = api_validar_link($body['link_reuniao'] ?? null);
        $pdo->prepare('INSERT INTO consultas (id_usuario, tipo, especialidade, medico, nome_local, data, horario, link_reuniao) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')->execute([$id, $tipo, $especialidade, trim((string) ($body['medico'] ?? '')) ?: null, trim((string) ($body['nome_local'] ?? '')) ?: null, $data, $hora . ':00', $link]);
        api_responder(201, ['mensagem' => 'Consulta adicionada.', 'id_consulta' => (int) $pdo->lastInsertId()]);
    }
    if (preg_match('#^/appointments/(\d+)$#', $path, $match) && $method === 'PUT') {
        $id = api_usuario(); $idConsulta = (int) $match[1];
        $tipo = trim((string) ($body['tipo'] ?? '')); $especialidade = trim((string) ($body['especialidade'] ?? ''));
        $data = trim((string) ($body['data'] ?? '')); $hora = trim((string) ($body['horario'] ?? ''));
        $dt = DateTime::createFromFormat('!Y-m-d', $data); $hr = DateTime::createFromFormat('!H:i', $hora);
        if (!in_array($tipo, ['Consulta', 'Exame'], true) || $especialidade === '' || !$dt || $dt->format('Y-m-d') !== $data || !$hr || $hr->format('H:i') !== $hora) api_responder(422, ['erro' => 'Confira tipo, especialidade, data e horário.']);
        $link = api_validar_link($body['link_reuniao'] ?? null);
        $stmt = $pdo->prepare('UPDATE consultas SET tipo=?, especialidade=?, medico=?, nome_local=?, data=?, horario=?, link_reuniao=? WHERE id_consulta=? AND id_usuario=?');
        $stmt->execute([$tipo, $especialidade, trim((string) ($body['medico'] ?? '')) ?: null, trim((string) ($body['nome_local'] ?? '')) ?: null, $data, $hora . ':00', $link, $idConsulta, $id]);
        if ($stmt->rowCount() === 0) { $check = $pdo->prepare('SELECT 1 FROM consultas WHERE id_consulta=? AND id_usuario=?'); $check->execute([$idConsulta, $id]); if (!$check->fetchColumn()) api_responder(404, ['erro' => 'Consulta não encontrada.']); }
        api_responder(200, ['mensagem' => 'Consulta atualizada.']);
    }
    if (preg_match('#^/appointments/(\d+)$#', $path, $match) && $method === 'DELETE') {
        $id = api_usuario(); $stmt = $pdo->prepare('DELETE FROM consultas WHERE id_consulta=? AND id_usuario=?'); $stmt->execute([(int) $match[1], $id]);
        if (!$stmt->rowCount()) api_responder(404, ['erro' => 'Consulta não encontrada.']);
        api_responder(200, ['mensagem' => 'Consulta removida.']);
    }

    // Grupos de apoio
    if ($path === '/groups' && $method === 'GET') {
        $stmt = $pdo->query("SELECT id_grupo, nome_grupo, responsavel, mais_info, foco, data_encontro, horario, link, contato AS telefone_grupo, imagem FROM grupos WHERE status_moderacao='aprovado' ORDER BY id_grupo DESC");
        api_responder(200, ['grupos' => $stmt->fetchAll()]);
    }
    if ($path === '/groups' && $method === 'POST') {
        api_usuario();
        api_aplicar_limite($pdo, 'group-create', (string) $_SESSION['id_usuario'], 5, 3600);
        $nome = trim((string) ($body['nome_grupo'] ?? $body['nome'] ?? ''));
        if ($nome === '' || mb_strlen($nome) > 150) api_responder(422, ['erro' => 'Informe um nome de grupo válido.']);
        $data = trim((string) ($body['data_encontro'] ?? '')); $hora = trim((string) ($body['horario'] ?? ''));
        if ($data !== '') { $dataObj = DateTime::createFromFormat('!Y-m-d', $data); if (!$dataObj || $dataObj->format('Y-m-d') !== $data) api_responder(422, ['erro' => 'Informe uma data válida para o grupo.']); }
        if ($hora !== '') { $horaObj = DateTime::createFromFormat('!H:i', $hora); if (!$horaObj || $horaObj->format('H:i') !== $hora) api_responder(422, ['erro' => 'Informe um horário válido para o grupo.']); }
        $linkGrupo = api_validar_link($body['link'] ?? null);
        if ($linkGrupo !== null && strlen($linkGrupo) > 150) api_responder(422, ['erro' => 'O link do grupo deve ter até 150 caracteres.']);
        $stmt = $pdo->prepare('SELECT 1 FROM grupos WHERE nome_grupo = ? LIMIT 1'); $stmt->execute([$nome]);
        if ($stmt->fetchColumn()) api_responder(409, ['erro' => 'Já existe um grupo com esse nome.']);
        $foto = null;
        if (isset($_FILES['imagem']) && ($_FILES['imagem']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            require_once dirname(__DIR__, 2) . '/processos/upload_helper.php';
            try { $foto = salvar_arquivo_upload($_FILES['imagem'], 'grupos'); } catch (Throwable $e) { api_responder(422, ['erro' => $e->getMessage()]); }
        }
        $stmt = $pdo->prepare("INSERT INTO grupos (id_criador, nome_grupo, responsavel, mais_info, foco, data_encontro, horario, link, contato, imagem, status_moderacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendente')");
        $stmt->execute([(int) $_SESSION['id_usuario'], $nome, trim((string) ($body['responsavel'] ?? '')) ?: null, trim((string) ($body['mais_info'] ?? '')) ?: null, trim((string) ($body['foco'] ?? '')) ?: null, $data !== '' ? $data : null, $hora !== '' ? $hora . ':00' : null, $linkGrupo, trim((string) ($body['contato'] ?? $body['telefone_grupo'] ?? '')) ?: null, $foto]);
        api_responder(201, ['mensagem' => 'Grupo enviado para análise.', 'id_grupo' => (int) $pdo->lastInsertId()]);
    }
    if (preg_match('#^/groups/(\d+)/share$#', $path, $match) && $method === 'POST') {
        $idUsuario = api_usuario();
        api_aplicar_limite($pdo, 'group-share', (string) $idUsuario, 5, 3600);
        $stmt = $pdo->prepare('SELECT g.nome_grupo, g.link, u.nome, u.email FROM grupos g JOIN usuarios u ON u.id_usuario=? WHERE g.id_grupo=? LIMIT 1');
        $stmt->execute([$idUsuario, (int) $match[1]]);
        $dadosGrupo = $stmt->fetch();
        if (!$dadosGrupo) api_responder(404, ['erro' => 'Grupo não encontrado.']);
        if (empty($dadosGrupo['link'])) api_responder(422, ['erro' => 'Este grupo ainda não tem um link disponível.']);
        $mailFrom = getenv('MAIL_FROM') ?: ''; $apiKey = getenv('RESEND_API_KEY') ?: '';
        if ($mailFrom === '' || $apiKey === '') api_responder(503, ['erro' => 'O envio de e-mail ainda não está configurado.']);
        $payload = json_encode(['from' => $mailFrom, 'to' => [$dadosGrupo['email']], 'subject' => 'Link do grupo ' . $dadosGrupo['nome_grupo'] . ' — Vitalize', 'text' => "Olá, {$dadosGrupo['nome']}!\n\nConforme solicitado, segue o link do grupo de apoio {$dadosGrupo['nome_grupo']}:\n{$dadosGrupo['link']}"], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $curl = curl_init('https://api.resend.com/emails');
        curl_setopt_array($curl, [CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 20, CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json'], CURLOPT_POSTFIELDS => $payload, CURLOPT_PROTOCOLS => CURLPROTO_HTTPS]);
        $respostaEmail = curl_exec($curl); $statusEmail = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE); curl_close($curl);
        if ($respostaEmail === false || $statusEmail < 200 || $statusEmail >= 300) { error_log('Vitalize API: falha ao enviar link de grupo por e-mail (HTTP ' . $statusEmail . ').'); api_responder(502, ['erro' => 'Não foi possível enviar o e-mail agora.']); }
        api_responder(200, ['mensagem' => 'Link enviado para o e-mail da sua conta.']);
    }

    // Relatos comunitários
    if ($path === '/stories' && $method === 'GET') {
        $stmt = $pdo->query("SELECT r.id_relato, r.titulo, r.conteudo, r.data_publicacao, r.anonimo, CASE WHEN r.anonimo = 1 THEN NULL ELSE u.nome END AS nome, CASE WHEN r.anonimo = 1 THEN NULL ELSE u.foto_perfil END AS foto_perfil FROM relatos r LEFT JOIN usuarios u ON u.id_usuario = r.id_usuario WHERE r.status_moderacao='aprovado' ORDER BY r.data_publicacao DESC LIMIT 200");
        api_responder(200, ['relatos' => $stmt->fetchAll()]);
    }
    if ($path === '/stories' && $method === 'POST') {
        $id = api_usuario(); $titulo = trim((string) ($body['titulo'] ?? '')); $conteudo = trim((string) ($body['conteudo'] ?? ''));
        api_aplicar_limite($pdo, 'story-create', (string) $id, 5, 3600);
        if ($titulo === '' || mb_strlen($titulo) > 150 || $conteudo === '' || mb_strlen($conteudo) > 10000) api_responder(422, ['erro' => 'Preencha título e conteúdo (até 10.000 caracteres).']);
        if (!filter_var($body['consentimento_publicacao'] ?? false, FILTER_VALIDATE_BOOLEAN)) api_responder(422, ['erro' => 'Confirme que deseja publicar o relato após análise.']);
        $anonimo = filter_var($body['anonimo'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $stmt = $pdo->prepare("INSERT INTO relatos (id_usuario, titulo, conteudo, data_publicacao, anonimo, status_moderacao) VALUES (?, ?, ?, NOW(), ?, 'pendente')");
        $stmt->execute([$id, $titulo, $conteudo, $anonimo]);
        api_responder(201, ['mensagem' => 'Relato enviado para análise.', 'id_relato' => (int) $pdo->lastInsertId()]);
    }

    if ($path === '/admin/moderation' && $method === 'GET') {
        api_exigir_admin($pdo);
        $relatos = $pdo->query("SELECT r.id_relato AS id, r.titulo, r.conteudo, r.data_publicacao AS data, r.anonimo, CASE WHEN r.anonimo=1 THEN 'Anônimo' ELSE u.nome END AS autor FROM relatos r LEFT JOIN usuarios u ON u.id_usuario=r.id_usuario WHERE r.status_moderacao='pendente' ORDER BY r.data_publicacao ASC LIMIT 200")->fetchAll();
        $grupos = $pdo->query("SELECT id_grupo AS id, nome_grupo AS titulo, mais_info AS conteudo, data_encontro AS data, responsavel AS autor FROM grupos WHERE status_moderacao='pendente' ORDER BY id_grupo ASC LIMIT 200")->fetchAll();
        api_responder(200, ['relatos' => $relatos, 'grupos' => $grupos]);
    }
    if (preg_match('#^/admin/moderation/(stories|groups)/(\\d+)$#', $path, $match) && $method === 'PATCH') {
        api_exigir_admin($pdo);
        $status = (string) ($body['status'] ?? '');
        if (!in_array($status, ['aprovado', 'recusado'], true)) api_responder(422, ['erro' => 'Status de moderação inválido.']);
        $tabela = $match[1] === 'stories' ? 'relatos' : 'grupos';
        $coluna = $match[1] === 'stories' ? 'id_relato' : 'id_grupo';
        $stmt = $pdo->prepare("UPDATE {$tabela} SET status_moderacao=? WHERE {$coluna}=? AND status_moderacao='pendente'");
        $stmt->execute([$status, (int) $match[2]]);
        if (!$stmt->rowCount()) api_responder(404, ['erro' => 'Conteúdo pendente não encontrado.']);
        api_responder(200, ['mensagem' => $status === 'aprovado' ? 'Conteúdo aprovado.' : 'Conteúdo recusado.']);
    }

    // Recuperação da senha por código enviado pelo Resend
    if ($path === '/password/forgot' && $method === 'POST') {
        $mensagemRecuperacao = 'Solicitação recebida. Se este e-mail estiver cadastrado, enviaremos um código. Se não recebê-lo, confira o spam e tente novamente mais tarde.';
        $email = strtolower(trim((string) ($body['email'] ?? '')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) api_responder(422, ['erro' => 'Informe um e-mail válido.']);
        api_aplicar_limite($pdo, 'password-forgot', $email, 3, 3600);
        $apiKey = getenv('RESEND_API_KEY') ?: ''; $mailFrom = getenv('MAIL_FROM') ?: '';
        if ($apiKey === '' || $mailFrom === '') { error_log('Vitalize API: configure RESEND_API_KEY e MAIL_FROM.'); api_responder(503, ['erro' => 'O serviço de e-mail de recuperação não está configurado.']); }
        $stmt = $pdo->prepare('SELECT id_usuario, nome, email FROM usuarios WHERE email = ? LIMIT 1'); $stmt->execute([$email]); $usuario = $stmt->fetch();
        if ($usuario) {
            $ult = $pdo->prepare('SELECT solicitado_em FROM codigos_recuperacao WHERE id_usuario=?'); $ult->execute([$usuario['id_usuario']]); $solicitado = $ult->fetchColumn();
            if ($solicitado && strtotime($solicitado . ' UTC') > time() - 60) api_responder(202, ['mensagem' => $mensagemRecuperacao]);
            $codigo = (string) random_int(100000, 999999);
            $hash = password_hash($codigo, PASSWORD_DEFAULT);
            $pdo->prepare('INSERT INTO codigos_recuperacao (id_usuario, codigo_hash, expira_em, tentativas, solicitado_em) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 10 MINUTE), 0, UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE codigo_hash=VALUES(codigo_hash), expira_em=VALUES(expira_em), tentativas=0, solicitado_em=UTC_TIMESTAMP()')->execute([$usuario['id_usuario'], $hash]);
            $payload = json_encode(['from' => $mailFrom, 'to' => [$usuario['email']], 'subject' => 'Código para recuperar sua senha — Vitalize', 'text' => "Olá, {$usuario['nome']}!\n\nSeu código para redefinir a senha é: {$codigo}\n\nEle expira em 10 minutos e pode ser usado uma única vez. Se você não solicitou a recuperação, ignore este e-mail."], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $curl = curl_init('https://api.resend.com/emails'); curl_setopt_array($curl, [CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 20, CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json'], CURLOPT_POSTFIELDS => $payload, CURLOPT_PROTOCOLS => CURLPROTO_HTTPS]);
            $resposta = curl_exec($curl); $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE); curl_close($curl);
            if ($resposta === false || $status < 200 || $status >= 300) {
                $pdo->prepare('DELETE FROM codigos_recuperacao WHERE id_usuario=?')->execute([$usuario['id_usuario']]);
                error_log('Vitalize API: erro Resend ao enviar código de recuperação (HTTP ' . $status . ').');
                api_responder(202, ['mensagem' => $mensagemRecuperacao]);
            }
        }
        api_responder(202, ['mensagem' => $mensagemRecuperacao]);
    }
    if ($path === '/password/reset' && $method === 'POST') {
        $email = strtolower(trim((string) ($body['email'] ?? ''))); $codigo = trim((string) ($body['codigo'] ?? '')); $senha = (string) ($body['senha'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{6}$/D', $codigo)) api_responder(422, ['erro' => 'E-mail ou código inválido.']);
        api_aplicar_limite($pdo, 'password-reset', $email, 10, 3600);
        if (strlen($senha) < 6 || strlen($senha) > 72) api_responder(422, ['erro' => 'A senha precisa ter entre 6 e 72 caracteres.']);
        if (isset($body['confirmar_senha']) && (string) $body['confirmar_senha'] !== $senha) api_responder(422, ['erro' => 'As senhas não coincidem.']);
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('SELECT u.id_usuario, c.codigo_hash, c.expira_em, c.tentativas FROM usuarios u JOIN codigos_recuperacao c ON c.id_usuario=u.id_usuario WHERE u.email=? FOR UPDATE'); $stmt->execute([$email]); $registro = $stmt->fetch();
        if (!$registro || (int) $registro['tentativas'] >= 5 || strtotime($registro['expira_em'] . ' UTC') <= time()) { $pdo->rollBack(); api_responder(422, ['erro' => 'Código inválido ou expirado. Solicite outro.']); }
        if (!password_verify($codigo, $registro['codigo_hash'])) { $pdo->prepare('UPDATE codigos_recuperacao SET tentativas=tentativas+1 WHERE id_usuario=?')->execute([$registro['id_usuario']]); $pdo->commit(); api_responder(422, ['erro' => 'Código inválido ou expirado.']); }
        $pdo->prepare('UPDATE usuarios SET senha=?, versao_sessao=versao_sessao+1 WHERE id_usuario=?')->execute([password_hash($senha, PASSWORD_DEFAULT), $registro['id_usuario']]);
        $pdo->prepare('DELETE FROM codigos_recuperacao WHERE id_usuario=?')->execute([$registro['id_usuario']]); $pdo->commit();
        api_responder(200, ['mensagem' => 'Senha alterada. Entre com sua nova senha.']);
    }

    // Assistente do Vitalize
    if ($path === '/assistant' && $method === 'POST') {
        api_aplicar_limite($pdo, 'assistant', 'public', 10, 60);
        require dirname(__DIR__) . '/assistente_bem_estar.php';
        exit;
    }

    api_responder(404, ['erro' => 'Rota não encontrada.']);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) $pdo->rollBack();
    error_log('Vitalize API: ' . $e->getMessage());
    api_responder(500, ['erro' => 'Não foi possível concluir a solicitação.']);
}
}
}
