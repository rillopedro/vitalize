<?php

declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function responder(int $status, array $dados): never
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['erro' => 'Método não permitido.']);
}

if (empty($_SESSION['id_usuario'])) {
    responder(401, ['erro' => 'Faça login para gerenciar sua agenda.']);
}

require_once __DIR__ . '/../conexao.php';

$idUsuario = (int) $_SESSION['id_usuario'];
$acao = $_POST['acao'] ?? '';
$idConsulta = filter_input(INPUT_POST, 'id_consulta', FILTER_VALIDATE_INT) ?: null;

function buscarConsultas(PDO $pdo, int $idUsuario): array
{
    $stmt = $pdo->prepare(
        'SELECT id_consulta, tipo, especialidade, medico, nome_local, data, horario
         FROM consultas
         WHERE id_usuario = :id_usuario
         ORDER BY data ASC, horario ASC, id_consulta ASC'
    );
    $stmt->execute([':id_usuario' => $idUsuario]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    if ($acao === 'excluir') {
        if ($idConsulta === null) {
            responder(422, ['erro' => 'Consulta inválida.']);
        }

        $stmt = $pdo->prepare(
            'DELETE FROM consultas
             WHERE id_consulta = :id_consulta AND id_usuario = :id_usuario'
        );
        $stmt->execute([
            ':id_consulta' => $idConsulta,
            ':id_usuario' => $idUsuario
        ]);

        if ($stmt->rowCount() === 0) {
            responder(404, ['erro' => 'Consulta não encontrada.']);
        }

        responder(200, ['consultas' => buscarConsultas($pdo, $idUsuario)]);
    }

    if ($acao !== 'salvar') {
        responder(422, ['erro' => 'Ação inválida.']);
    }

    $tipo = trim((string) ($_POST['tipo'] ?? ''));
    $especialidade = trim((string) ($_POST['especialidade'] ?? ''));
    $medico = trim((string) ($_POST['medico'] ?? ''));
    $nomeLocal = trim((string) ($_POST['nome_local'] ?? ''));
    $data = trim((string) ($_POST['data'] ?? ''));
    $horario = trim((string) ($_POST['horario'] ?? ''));

    if (!in_array($tipo, ['Consulta', 'Exame'], true)) {
        responder(422, ['erro' => 'Informe um tipo válido.']);
    }

    $dataValida = DateTime::createFromFormat('!Y-m-d', $data);
    $horarioValido = DateTime::createFromFormat('!H:i', $horario);
    if (!$dataValida || $dataValida->format('Y-m-d') !== $data || !$horarioValido || $horarioValido->format('H:i') !== $horario) {
        responder(422, ['erro' => 'Informe uma data e um horário válidos.']);
    }

    if ($especialidade === '') {
        responder(422, ['erro' => 'Informe a especialidade.']);
    }

    $parametros = [
        ':tipo' => $tipo,
        ':especialidade' => $especialidade,
        ':medico' => $medico !== '' ? $medico : null,
        ':nome_local' => $nomeLocal !== '' ? $nomeLocal : null,
        ':data' => $data,
        ':horario' => $horario . ':00',
        ':id_usuario' => $idUsuario
    ];

    if ($idConsulta !== null) {
        $stmt = $pdo->prepare(
            'UPDATE consultas
             SET tipo = :tipo, especialidade = :especialidade, medico = :medico,
                 nome_local = :nome_local, data = :data, horario = :horario
             WHERE id_consulta = :id_consulta AND id_usuario = :id_usuario'
        );
        $parametros[':id_consulta'] = $idConsulta;
        $stmt->execute($parametros);

        if ($stmt->rowCount() === 0) {
            $existe = $pdo->prepare(
                'SELECT id_consulta FROM consultas
                 WHERE id_consulta = :id_consulta AND id_usuario = :id_usuario'
            );
            $existe->execute([
                ':id_consulta' => $idConsulta,
                ':id_usuario' => $idUsuario
            ]);
            if (!$existe->fetchColumn()) {
                responder(404, ['erro' => 'Consulta não encontrada.']);
            }
        }
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO consultas
                (id_usuario, tipo, especialidade, medico, nome_local, data, horario)
             VALUES
                (:id_usuario, :tipo, :especialidade, :medico, :nome_local, :data, :horario)'
        );
        $stmt->execute($parametros);
    }

    responder(200, ['consultas' => buscarConsultas($pdo, $idUsuario)]);
} catch (Throwable $e) {
    error_log('Erro na agenda: ' . $e->getMessage());
    responder(500, ['erro' => 'Não foi possível atualizar a agenda.']);
}
