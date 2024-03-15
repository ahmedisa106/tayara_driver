<?php

$system_url_local = 'http://localhost:8000';

$system_url_production = 'https://tayara-app.com';

return [
    'local' => env('SYSTEM_URL', $system_url_local)
];
