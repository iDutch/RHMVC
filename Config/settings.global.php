<?php
//Application dirs
define('LOG_DIR', __DIR__ . '/../Application/Logs/');
define('PARTIAL_DIR', __DIR__ . '/../Application/Partials/');
define('MODEL_DIR', __DIR__ . '/../Application/Models/');
define('VIEW_DIR', __DIR__ . '/../Application/Views/');
define('CONTROLLER_DIR', __DIR__ . '/../Application/Controllers/');
define('LAYOUT_DIR', __DIR__ . '/../Application/Layout/');

//Static assets dirs
define('CSS_DIR', __DIR__ . '/../htdocs/static/css/');
define('JS_DIR', __DIR__ . '/../htdocs/static/js/');

//Config dir
define('CONFIG_DIR', __DIR__ . '/');

//Websocket server
define('WSS_URL', 'ws://192.168.1.120:8080');

//Login cookie
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', 'rhmvc.local');
define('COOKIE_TTL', (3600 * 24 * 30));

//Mail configuration
define('FROM_NAME', '');
define('FROM_ADDRESS', '');
define('USE_SMTP', true);
define('SMTP_HOST', '');
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_ENCRYPTION', 'tls');
define('SMTP_PORT', '587');