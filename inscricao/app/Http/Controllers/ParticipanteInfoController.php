<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;


class ParticipanteInfoController extends Controller
{

    private $totalPage = 10;
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function presencaCoordernador(Request $request)
    {
        $eventoID = Auth::user()->edicao_ativa;
        $participanteID = $request->input('participante_id');
        $atividadeID = $request->get('atividadeID');
        //dd($participanteID, $atividadeID);
        $this->setPresenca($participanteID, $atividadeID, $eventoID);
        return $this->getViewParticipante($participanteID, $eventoID);
    }
    public function show(Request $request, $participanteId, $eventoId)
    {

        //$this->getViewParticipante($participanteId, $eventoId);
        $data = $this->getDados($participanteId, $eventoId);
        $dadosPessoais = $this->getDadosPessoais($participanteId, $eventoId);


        // dd($dataE);
        //return view('participante.participanteInfo', compact('data', 'dadosPessoais'));
        //dd($dadosPessoais, $data);
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('d/m/Y H:i');
        $time = date('H:i');
        //$date += $time;
        // dd($date);
        //dd(Auth::check());
        if (!is_null($data[0])) {
            if (Auth::check()) {
                $occour = 0;
                $userLoggedID = Auth::user();
                if ($userLoggedID->tipo == 'monitor') {
                    $atividadeHasMonitor = DB::table('atividade_has_monitor')
                        ->join('atividade', 'atividade.id', '=', 'atividade_has_monitor.atividade_id')
                        ->select(
                            'atividade_has_monitor.atividade_id as AtividadesIn'
                        )
                        ->where([
                            ['atividade_has_monitor.monitor_id', '=', $userLoggedID->id]
                        ])
                        ->paginate($this->totalPage);
                    //dd($data[0]);
                    foreach ($data as $arrayIndex => $atividadeID) {
                        foreach ($atividadeHasMonitor as $arrayIndex2 => $atividadeID2) {
                            $dataInicio = $atividadeID->DataInicio . " " . $atividadeID->HoraInicio;
                            $dataFim = $atividadeID->DataFim . " " . $atividadeID->HoraFim;
                            //dd($date, $atividadeID->DataFim);
                            if ($atividadeID->AtividadeID == $atividadeID2->AtividadesIn) {
                                if ($date >= $dataInicio) {
                                    if ($date <= $dataFim) {
                                        $this->setPresenca($participanteId, $atividadeID->AtividadeID, $eventoId);
                                        $occour++;
                                    } else {
                                        return 'Esta atividade. já encerrou. Por favor, procure um coordenador';
                                        $occour++;
                                    }
                                } else {
                                    return 'O Evento ainda não Iniciou';
                                    $occour++;
                                }
                            }
                        }
                    }
                    if ($occour == 0) {
                        echo ('<br>Você não é monitor de nenhuma das atividades desse participante');
                    } // dd($occour);
                    //dd($teste);
                } elseif ($userLoggedID->tipo == 'coordenador') {
                    foreach ($data as $arrayIndex => $atividadeID) {
                        $dataInicio = $atividadeID->DataInicio . " " . $atividadeID->HoraInicio;
                        if ($date >= $dataInicio) {
                            return view('participante.coordenadorAccess', compact('data', 'userLoggedID', 'dadosPessoais'));
                            $occour++;
                        }
                    }
                }
            } else {
                echo 'Monitor não logado';
            }
            return $this->getViewParticipante($participanteId, $eventoId);
        } else {
            return 'Participante não existe ou não cadastrado';
        }
    }

    function getViewParticipante($participanteID, $eventoID)
    {
        $data = $this->getDados($participanteID, $eventoID);
        $dadosPessoais = $this->getDadosPessoais($participanteID, $eventoID);
        //dd($data, $dadosPessoais);
        return view('participante.participanteInfo', compact('data', 'dadosPessoais'));
    }

    function getDadosPessoais($participanteID, $eventoID)
    {
        $totalPage = 10;

        return DB::table('inscricao')
            ->join('inscricao_eventos', 'inscricao_eventos.participante_id', '=', 'inscricao.participante_id')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->join('evento', 'evento.id', '=', 'atividade.evento_id')
            ->select(
                'participante.tipo',
                'participante.id',
                'participante.nome as Nome',
                'participante.curso as Curso',
                'participante.instituicao as Instituição',
                'evento.nome as Evento',
                'evento.ano as EventoAno'
            )->where([
                ['participante.id', 'like',  $participanteID],
                ['evento.id', '=', $eventoID],
            ])
            ->get();
    }

    function getDados($participanteID, $eventoID)
    {
        $totalPage = 10;

        return DB::table('inscricao')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->join('evento', 'evento.id', '=', 'atividade.evento_id')
            ->select(
                'atividade.id as AtividadeID',
                'atividade.identificador as AtividadeCod',
                'atividade.titulo as Atividade',
                'inscricao.status as Status',
                'inscricao.presente as presente',
                DB::raw('DATE_FORMAT(inscricao.data,"%d/%m/%Y %H:%i") as data'),
                DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as DataInicio'),
                DB::raw('DATE_FORMAT(atividade.hora_inicio,"%H:%i") as HoraInicio'),
                DB::raw('DATE_FORMAT(atividade.data_fim,"%d/%m/%Y") as DataFim'),
                DB::raw('DATE_FORMAT(atividade.hora_fim,"%H:%i") as HoraFim')

            )

            ->where([
                ['participante.id', 'like',  $participanteID],
                ['atividade.evento_id', '=', $eventoID],
            ])
            ->orderBy('inscricao.data', 'DESC')
            ->get();
    }

    function setPresenca($participanteID, $atividadeID, $eventoID)
    {
        $data = $this->getDados($participanteID, $eventoID);
        $dadosPessoais = $this->getDadosPessoais($participanteID, $eventoID);
        if (!is_null($data[0])) {
            foreach ($data as $arrayIndex => $atividadeId) {
                if ($atividadeId->AtividadeID == $atividadeID) {
                    if ($atividadeId->Status == 'pago' || $atividadeId->Status == 'isento' || $atividadeId->Status == 'gratuito') {
                        if ($atividadeId->presente == 0) {
                            $input = DB::update("UPDATE inscricao SET presente = 1 
                    WHERE participante_id = $participanteID and atividade_id = $atividadeID");
                            if (!is_null($input)) {
                                echo ("<br><h3>Presença Confirmada</h3><br>");
                                header("refresh: 3;" . route('home'));
                            }
                        } else {
                            echo ("<br><h3>O Partificante já possui presença nesta atividade");
                            header("refresh: 3;" . route('home'));
                        }
                    } else {
                        echo ("<br><h3>Pagamento não Identificado");
                        return $this->getViewParticipante($participanteID, $eventoID);
                    }
                }
            }
        } else {
            echo ("<br><h3>Verifique se você está no evento mais recente");
            header("refresh: 3;" . route('home'));
        }
    }
}