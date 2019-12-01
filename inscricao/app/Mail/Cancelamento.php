<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class Cancelamento extends Mailable
{
      use Queueable, SerializesModels;
      protected $participanteNome;
      protected $atividadeDados;
      /**
       * Create a new message instance.
       *
       * @return void
       */
      public function __construct($userID, $atividadeID)
      {
            $this->participanteNome = DB::table('participante')
                  ->select(
                        'nome'
                  )
                  ->where('id', '=', $userID)
                  ->get()[0]->nome;
            $this->atividadeDados = DB::table('atividade')
                  ->join('local', 'local.id', '=', 'atividade.local_id')
                  ->select(
                        'atividade.tipo as tipo',
                        'atividade.identificador as identificador',
                        'atividade.titulo as titulo',
                        DB::raw('CONCAT(DATE_FORMAT(atividade.hora_inicio,"%H:%i"), " - ", DATE_FORMAT(atividade.hora_fim,"%H:%i")) AS horario'),
                        DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_do_curso'),
                        'local.descricao as local'
                  )->where('atividade.id', '=', $atividadeID)
                  ->get();
      }

      /**
       * Build the message.
       *
       * @return $this
       */
      public function build()
      {

            return $this->from('weekit.vdc@ifba.edu.br')
                  ->view('emails.cancelado')
                  ->with([
                        'nome' => $this->participanteNome,
                        'atividade' => $this->atividadeDados
                  ]);
      }
}