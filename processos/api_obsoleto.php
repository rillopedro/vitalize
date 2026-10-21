<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
http_response_code(410);
echo json_encode([
    'erro' => 'Este endpoint antigo foi substituído pela API REST.',
    'documentacao' => '../api/README.md'
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
