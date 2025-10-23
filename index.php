<?php
$projectRoot = __DIR__;
$flagFile = $projectRoot . DIRECTORY_SEPARATOR . '.installed';
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

if (!file_exists($flagFile)) {
    if (!str_starts_with($requestUri, '/installer')) {
        header('Location: /installer/install.php');
        exit;
    }
} else {
    if (preg_match('#^/(api|auth|dl|health|static)#', $requestUri)) {
        chdir($projectRoot . '/backend-php/public');
        require $projectRoot . '/backend-php/public/index.php';
        exit;
    }
}

$frontendDir = $projectRoot . '/frontend/dist';
$filePath = realpath($frontendDir . $requestUri);

if ($filePath !== false && str_starts_with($filePath, realpath($frontendDir)) && is_file($filePath)) {
    $mime = mime_content_type($filePath) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    readfile($filePath);
    exit;
}

$indexFile = $frontendDir . '/index.html';
if (file_exists($indexFile)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($indexFile);
    exit;
}

echo '<!doctype html><html lang="en"><head><meta charset="utf-8"/><title>AvA Snippet</title>';
echo '<style>body{background:#0a0a0a;color:#fff;font-family:Arial, sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:40px;}</style>';
echo '<body><div><h1>AvA Snippet</h1><p>The installer is complete, but the frontend build was not found.</p>';
echo '<p>Build the frontend locally and upload the contents of <code>frontend/dist</code> to the server.</p>';
echo '</div></body></html>';
