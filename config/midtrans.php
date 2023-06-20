<?php

return [
  'merchant_id' => env("MIDTRANS_MERCHAT_ID"),
  'client_key' => env("CLIENT_KEY_MIDTRANS"),
  'server_key' => env("API_KEY_MIDTRANS"),

  'is_production' => false,
  'is_sanitized' => true,
  'is_3ds' => true,
];
