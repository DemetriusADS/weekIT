<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Http\Request;
use DB;

class CancelamentoEvent
{
      use Dispatchable, InteractsWithSockets, SerializesModels;

      public $userID;
      public $email;
      public $atividadeID;
      public $atividadeDados;
      /**
       * Create a new event instance.
       *
       *
       * @return void
       */
      public function __construct($userID, $email, $atividadeID)
      {
            $this->userID = $userID;
            // $atividadeID = $atividade_id;
            $this->email = $email;
            $this->atividadeID = $atividadeID;
      }

      /**
       * Get the channels the event should broadcast on.
       *
       * @return \Illuminate\Broadcasting\Channel|array
       */
      public function broadcastOn()
      {
            return new PrivateChannel('channel-name');
      }
}
