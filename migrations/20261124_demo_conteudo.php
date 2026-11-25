<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/conexao.php';

$schema = $pdo->query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()')->fetchAll(PDO::FETCH_COLUMN);
if (in_array('grupos', $schema, true)) {
    $colunas = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'grupos'")->fetchAll(PDO::FETCH_COLUMN);
    $filtros = ['nome_grupo LIKE ?'];
    $valores = ['Modelo fictício:%'];
    if (in_array('is_demo', $colunas, true)) {
        $filtros[] = 'is_demo = 1';
    }
    if (in_array('codigo_demo', $colunas, true)) {
        $filtros[] = 'codigo_demo LIKE ?';
        $valores[] = 'DEMO-GRP-%';
    }
    $stmt = $pdo->prepare('DELETE FROM grupos WHERE (' . implode(' OR ', $filtros) . ')');
    $stmt->execute($valores);

    $indices = $pdo->query("SELECT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'grupos'")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('grupos_codigo_demo', $indices, true)) {
        $pdo->exec('ALTER TABLE grupos DROP INDEX grupos_codigo_demo');
    }
    foreach (['codigo_demo', 'is_demo'] as $colunaDemo) {
        if (in_array($colunaDemo, $colunas, true)) {
            $pdo->exec('ALTER TABLE grupos DROP COLUMN `' . $colunaDemo . '`');
        }
    }
}

if (in_array('relatos_demo', $schema, true)) {
    $pdo->exec('DROP TABLE relatos_demo');
}

echo "Conteúdo de demonstração removido; os registros da comunidade foram preservados.\n";