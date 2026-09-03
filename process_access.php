<?php
/**
 * PROCESS ACCESS - VALIDAÇÃO E DISPARO DE MENSAGEM VIA EVOLUTION API
 */

require_once __DIR__ . '/config.php';

// Configuração de Cabeçalhos CORS e Segurança
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, ALLOWED_ORIGINS)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Permite localmente se não houver cabeçalho de origem (ex: chamadas diretas)
    header("Access-Control-Allow-Origin: *");
}
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Trata requisição Preflight do CORS (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Aceita apenas requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

// Obtém o payload JSON enviado pelo JavaScript
$inputData = json_decode(file_get_contents('php://input'), true);

$hash = isset($inputData['hash']) ? trim($inputData['hash']) : '';
$phoneInput = isset($inputData['phone']) ? trim($inputData['phone']) : '';

if (empty($hash) || empty($phoneInput)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos. Por favor, forneça o hash e o telefone.']);
    exit;
}

// 1. SANITIZAÇÃO E PADRONIZAÇÃO DO TELEFONE
// Remove todos os caracteres que não sejam números
$phone = preg_replace('/\D/', '', $phoneInput);

// Validação simples de tamanho de telefone brasileiro (com DDD)
// Se tiver 10 ou 11 dígitos, adiciona o DDI do Brasil (55)
if (strlen($phone) === 10 || strlen($phone) === 11) {
    $phone = '55' . $phone;
}

// Se o telefone ficar muito curto ou muito longo, rejeita antes de chamar a API
if (strlen($phone) < 10 || strlen($phone) > 15) {
    echo json_encode(['success' => false, 'message' => 'Número de telefone inválido. Insira o DDD + Número.']);
    exit;
}

// 2. COMUNICAÇÃO COM A EVOLUTION API - VALIDAR SE O NÚMERO EXISTE NO WHATSAPP
$checkUrl = rtrim(EVOLUTION_API_URL, '/') . '/chat/whatsappNumbers/' . EVOLUTION_INSTANCE;
$headers = [
    'Content-Type: application/json',
    'apikey: ' . EVOLUTION_API_KEY
];

$checkPayload = json_encode([
    'numbers' => [$phone]
]);

$ch = curl_init($checkUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $checkPayload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$checkResponse = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
    
    $msg = 'Não foi possível conectar ao servidor de validação no momento. Tente novamente mais tarde.';
    if ($isLocal) {
        $msg = '[DEBUG LOCAL] cURL Error: ' . $curlError . ' ao acessar ' . $checkUrl;
    }
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

if ($httpStatus !== 200 && $httpStatus !== 201) {
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
    
    $msg = 'Não foi possível conectar ao servidor de validação no momento. Tente novamente mais tarde.';
    if ($isLocal) {
        $msg = '[DEBUG LOCAL] HTTP Status ' . $httpStatus . ' da API. Resposta Bruta: ' . $checkResponse;
    }
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$checkData = json_decode($checkResponse, true);
$numberExists = false;

// A API retorna um array de objetos. Verificamos se o primeiro elemento existe e é válido.
if (is_array($checkData) && isset($checkData[0])) {
    if (isset($checkData[0]['exists']) && $checkData[0]['exists'] === true) {
        $numberExists = true;
        // Pega o JID formatado retornado pela API (caso queira salvar com o JID exato)
        if (isset($checkData[0]['jid'])) {
            // Remove o sufixo @s.whatsapp.net para manter apenas o número se preferido, ou mantemos o número limpo
        }
    }
}

if (!$numberExists) {
    echo json_encode(['success' => false, 'message' => 'O número digitado não possui uma conta de WhatsApp ativa.']);
    exit;
}

// 3. PERSISTÊNCIA - SALVA A ASSOCIAÇÃO DO HASH COM O TELEFONE
$sessions = [];
if (file_exists(SESSIONS_FILE)) {
    $sessionsContent = file_get_contents(SESSIONS_FILE);
    // Remove a diretiva de segurança do PHP (bloco PHP die)
    $jsonContent = preg_replace('/^<\?php.*?\?>/s', '', $sessionsContent);
    $sessions = json_decode(trim($jsonContent), true);
    if (!is_array($sessions)) {
        $sessions = [];
    }
}

// Obtém o IP real do cliente (considerando proxies como Cloudflare, etc)
$clientIp = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $clientIp = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $clientIp = trim($ips[0]);
}

// Adiciona ou atualiza a sessão do hash
$newSession = [
    'hash' => $hash,
    'phone' => $phone,
    'ip' => $clientIp,
    'timestamp' => time()
];

// Remove registros antigos do mesmo hash ou telefone se já existirem para manter o JSON otimizado
$sessions = array_filter($sessions, function($sess) use ($hash, $phone) {
    return $sess['hash'] !== $hash && $sess['phone'] !== $phone;
});

$sessions[] = $newSession;
// Grava adicionando o prefixo die() do PHP para proteção
$outputContent = "<?php die('Acesso restrito'); ?>\n" . json_encode(array_values($sessions), JSON_PRETTY_PRINT);
file_put_contents(SESSIONS_FILE, $outputContent);

// Função auxiliar para enviar mensagens de texto via Evolution API
function sendWhatsappMessage($phone, $text, $sendUrl, $headers) {
    $sendPayload = json_encode([
        'number' => $phone,
        'text' => $text
    ]);

    $ch = curl_init($sendUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $sendPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $sendResponse = curl_exec($ch);
    $sendHttpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrorSend = curl_error($ch);
    curl_close($ch);

    return [
        'status' => $sendHttpStatus,
        'error' => $curlErrorSend,
        'response' => $sendResponse
    ];
}

// 4. DISPARAR AS MENSAGENS COM O CÓDIGO OTP DE ACESSO
$sendUrl = rtrim(EVOLUTION_API_URL, '/') . '/message/sendText/' . EVOLUTION_INSTANCE;

$msg1 = "Olá! 👋\nSeu código de acesso exclusivo para visualizar o meu currículo online é:";
$msg2 = "*{$hash}*";
$msg3 = "Insira este código na tela de bloqueio do site para liberar a visualização instantaneamente.";

// Envia mensagem 1
$res1 = sendWhatsappMessage($phone, $msg1, $sendUrl, $headers);

if ($res1['status'] === 200 || $res1['status'] === 201) {
    // Espera 1 segundo
    sleep(1);
    
    // Envia mensagem 2 (Código)
    $res2 = sendWhatsappMessage($phone, $msg2, $sendUrl, $headers);
    
    if ($res2['status'] === 200 || $res2['status'] === 201) {
        // Espera 0.7 segundos
        usleep(700000);
        
        // Envia mensagem 3
        $res3 = sendWhatsappMessage($phone, $msg3, $sendUrl, $headers);
        
        if ($res3['status'] === 200 || $res3['status'] === 201) {
            echo json_encode(['success' => true, 'message' => 'Link de acesso enviado com sucesso para o seu WhatsApp!']);
            exit;
        } else {
            $sendHttpStatus = $res3['status'];
            $curlErrorSend = $res3['error'];
            $sendResponse = $res3['response'];
        }
    } else {
        $sendHttpStatus = $res2['status'];
        $curlErrorSend = $res2['error'];
        $sendResponse = $res2['response'];
    }
} else {
    $sendHttpStatus = $res1['status'];
    $curlErrorSend = $res1['error'];
    $sendResponse = $res1['response'];
}

// Se chegou aqui, ocorreu uma falha no envio de alguma das mensagens
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);

$msg = 'O número é válido, mas ocorreu um erro ao tentar enviar a mensagem. Por favor, tente novamente.';
if ($isLocal) {
    if ($curlErrorSend) {
        $msg = '[DEBUG LOCAL] cURL Error no Envio: ' . $curlErrorSend;
    } else {
        $msg = '[DEBUG LOCAL] HTTP Status no Envio (' . $sendHttpStatus . '). Resposta Bruta: ' . $sendResponse;
    }
}
echo json_encode(['success' => false, 'message' => $msg]);
