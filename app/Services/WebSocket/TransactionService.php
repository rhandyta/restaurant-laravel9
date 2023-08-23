<?php

namespace App\Services\WebSocket;

use App\Events\OrderEvent;
use App\Models\User;

class TransactionService {


    protected $transaction;

    public function __construct($transaction) 
    {
        $this->transaction = $transaction;
    }


    public function sendEventOrder()
    {
        $userRoles = User::whereIn('roles', ['cashier', 'manager'])->get();
        foreach($userRoles as $user) {
            broadcast(new OrderEvent($this->transaction, $user));
        }
    }

}