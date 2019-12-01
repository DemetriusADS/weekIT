<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CancelamentoEvent;
use App\Mail\Cancelamento;

class CancelamentoListener
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
       * @param CancelamentoEvent $event
       * @return void
       */
      public function handle(CancelamentoEvent $event)
      {
            \Mail::to($event)
                  ->send(new Cancelamento($event->userID, $event->atividadeID));
      }
}