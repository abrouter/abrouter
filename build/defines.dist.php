<?php

define('DOMAIN', 'abrouter.com');
define('TEMPLATE_VARS', [
BUILD_MODE_LOCAL => [
'INSECURE_PORT' => 930,
'SECURE_PORT' => 931,
'DOMAIN' => 'localhost:930',
'API_RULE' => 'PathPrefix(`/api/v1/`)',
'FRONT_RULE' => 'PathPrefix(`/`)',
'TLS_CHALLENGE' => 'false',
'TOML_CONFIG' => '#',
'HTTPS_REDIRECT_RULES' => '',
'APP_PORT' => 933,
'TRAEFIK_PORT' => 8085,
'TLS' => 'true',
],
BUILD_MODE_PROD => [
'INSECURE_PORT' => 80,
'SECURE_PORT' => 443,
'API_RULE' => '(Host(`'. DOMAIN .'`) || Host(`abr-traefik`)) && PathPrefix(`/api/v1/`)',
'FRONT_RULE' => '(Host(`'. DOMAIN .'`) || Host(`abr-traefik`)) &&  PathPrefix(`/`)',
'DOMAIN' => DOMAIN,
'TLS_CHALLENGE' => 'true',
'HTTPS_REDIRECT_RULES' => '      - "--entrypoints.web.http.redirections.entryPoint.to=websecure"
- "--entrypoints.web.http.redirections.entryPoint.scheme=https"
- "--entrypoints.web.http.redirections.entrypoint.permanent=true"',
'TRAEFIK_PORT' => 8085,
'APP_PORT' => 933,
'TOML_CONFIG' => '      - "--providers.file.filename=/traefik/config.toml"',
'TLS' => 'true',
],
]);

