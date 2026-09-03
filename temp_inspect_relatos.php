<?php
require 'conexao.php';
$stmt = $pdo->query('SHOW COLUMNS FROM relatos');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . '|' . $row['Type'] . PHP_EOL;
}
