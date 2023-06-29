<?php

namespace App\Services\Midtrans;

class CreateSnapTokenService extends Midtrans
{

  protected $transaction;
  public function __construct($transaction)
  {
    parent::__construct();
    $this->transaction = $transaction;
  }

  public function getSnapToken()
  {

    $response = \Midtrans\CoreApi::charge($this->transaction);

    return $response;
  }
}
