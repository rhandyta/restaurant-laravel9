<?php

namespace App\Services\Midtrans;

class Midtrans
{
  protected $serverKey;
  protected $isProduction;
  protected $isSanitized;
  protected $is3ds;

  public function __construct()
  {
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.isProduction');
    \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is3ds');
  }
}
