<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class Confirmacoes extends Mailable
{
    use Queueable, SerializesModels;
    protected $participanteNome;
    protected $atividadeDados;
    protected $status;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userID, $atividadeID, $status)
    {
        $this->status = $status;
        $this->participanteNome = DB::table('participante')
            ->select(
                'nome'
            )
            ->where('id', '=', $userID)
            ->get()[0]->nome;
        $this->atividadeDados = DB::table('atividade')
            ->join('local', 'local.id', '=', 'atividade.local_id')
            ->select(
                'atividade.identificador as identificador',
                'atividade.titulo as titulo'
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
            ->view('emails.confirmacao')
            ->with([
                'nome' => $this->participanteNome,
                'atividade' => $this->atividadeDados,
                'status' => $this->status
            ]);
    }
}