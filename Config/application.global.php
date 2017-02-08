<?php
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