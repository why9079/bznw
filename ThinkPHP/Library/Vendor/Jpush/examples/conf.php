<?php
require __DIR__ . '/../autoload.php';

use JPush\Client as JPush;

$app_key = getenv('app_key');
$master_secret = getenv('master_secret');
$registration_id = getenv('registration_id');

$client = new JPush("9cbc718ce6b4ff7e0f1850a9", "07d7433588b13890044ef342");
