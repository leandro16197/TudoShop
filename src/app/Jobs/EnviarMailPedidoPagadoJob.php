<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Mail\PedidoAprobadoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EnviarMailPedidoPagadoJob implements ShouldQueue
{
    use Queueable;

    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function handle()
    {
        Mail::to($this->pedido->user->email)
            ->send(new PedidoAprobadoMail($this->pedido));
    }
}