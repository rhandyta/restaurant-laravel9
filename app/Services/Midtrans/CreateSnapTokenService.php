<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

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
