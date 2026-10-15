<?php
/**
 * CONFIGURAÇÃO GLOBAL - CONTROLE DE ACESSO VIA WHATSAPP (EVOLUTION API)
 */

// Carrega variáveis do arquivo .env se existir
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv(sprintf('%s=%s', $key, $value));
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// URL base da sua API do WhatsApp (Evolution API)
define('EVOLUTION_API_URL', getenv('EVOLUTION_API_URL') ?: 'https://evo2.studiopro.com.br');

// Chave global da API (apikey)
define('EVOLUTION_API_KEY', getenv('EVOLUTION_API_KEY') ?: '');

// Nome da Instância do WhatsApp criada na Evolution API
define('EVOLUTION_INSTANCE', getenv('EVOLUTION_INSTANCE') ?: 'LeoMaciel');

// Caminho do arquivo de persistência local das sessões
define('SESSIONS_FILE', __DIR__ . '/sessions.php');

// Configurações de Origem de Acesso (CORS) e segurança
define('ALLOWED_ORIGINS', [
    'https://leomaciel.com',
    'http://localhost:5000',
    'http://127.0.0.1:5000',
    'http://localhost',
    'http://127.0.0.1'
]);
