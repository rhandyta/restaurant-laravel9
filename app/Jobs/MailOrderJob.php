<?php

namespace App\Jobs;

use App\Mail\MailOrderTransaction;
use App\Mail\MailTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $transaction;
    protected $auth;
    protected $status_transaction;

    public function __construct($transaction, $auth, $status_transaction)
    {
        $this->transaction = $transaction;
        $this->auth = $auth;
        $this->status_transaction = $status_transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->status_transaction) {
            case 'settlement':
                Mail::to(isset($this->auth->email) ? $this->auth->email : $this->transaction->email)->send(new MailTransaction($this->transaction));
                break;
            case 'deny':
                Mail::to(isset($this->auth->email) ? $this->auth->email : $this->transaction->email)->send(new MailOrderTransaction($this->transaction));
                break;
            case 'expire':
                Mail::to(isset($this->auth->email) ? $this->auth->email : $this->transaction->email)->send(new MailOrderTransaction($this->transaction));
                break;
            case 'cancel':
                Mail::to(isset($this->auth->email) ? $this->auth->email : $this->transaction->email)->send(new MailOrderTransaction($this->transaction));
                break;
            default:
                Mail::to(isset($this->auth->email) ? $this->auth->email : $this->transaction->email)->send(new MailOrderTransaction($this->transaction));
        }
    }
}
