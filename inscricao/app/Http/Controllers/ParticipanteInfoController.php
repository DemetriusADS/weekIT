<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ParticipanteInfoController extends Controller
{
    private $totalPage = 15;
    public function show(Request $request, $participanteId, $eventoId)
    {
        $data = DB::table('inscricao')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->join('inscricao_eventos', 'inscricao_eventos.participante_id', '=', 'inscricao.participante_id')
            ->join('evento', 'evento.id', '=', 'atividade.evento_id')
            ->select(
                'participante.tipo',
                'participante.id',
                'participante.nome as Nome',
                'participante.curso as Curso',
                'participante.instituicao as Instituição',
                'evento.nome as Evento',
                'evento.id as EventoID',
                'evento.ano as EventoAno',
                'atividade.identificador as AtividadeID',
                'atividade.titulo as Atividade',
                'inscricao.status as Status',
                DB::raw('DATE_FORMAT(inscricao.data,"%d/%m/%Y %H:%i") as data')
            )
            ->where([
                ['participante.id', 'like',  $participanteId],
                ['atividade.evento_id', '=', $eventoId],
            ])
            ->orderBy('inscricao.data', 'DESC')
            ->paginate($this->totalPage);
            $oi = 'ola';
        if (!is_null($data)) {
            return view('participante.participanteInfo')
            ->with('data', $data[0]);
        } else {
            return response()->json(['data' => 'vazio']);
        }
    }
}