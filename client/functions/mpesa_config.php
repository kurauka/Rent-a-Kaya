<?php
// M-PESA Daraja configuration (sandbox)
// Move secrets to .env for safety; this file reads from env vars and falls back to safe defaults.
require_once __DIR__ . '/../../admin/functions/env.php';
return [
  'consumer_key' => getenv('M_PESA_CONSUMER_KEY') ?: '',
  'consumer_secret' => getenv('M_PESA_CONSUMER_SECRET') ?: '',
  'shortcode' => getenv('M_PESA_SHORTCODE') ?: '',
  'passkey' => getenv('M_PESA_PASSKEY') ?: '',
  // Optional: set a full callback URL here; if empty, code will build it dynamically.
  'callback_url' => getenv('M_PESA_CALLBACK_URL') ?: '',
];
?>