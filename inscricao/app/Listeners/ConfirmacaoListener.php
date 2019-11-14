<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ConfirmacaoEvent;
use App\Mail\Confirmacoes;

class ConfirmacaoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param ConfirmacaoEvent $event
     * @return void
     */
    public function handle(ConfirmacaoEvent $event)
    {
        \Mail::to($event)
            ->send(new Confirmacoes($event->userID, $event->atividadeID, $event->status));
    }
}
