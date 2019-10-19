<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Inscricao;
use DB;

class HomeController extends Controller
{
    private $totalPage = 15;
    private $idAnoEvento;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titulo = "Últimas inscrições";
        $data = DB::table('inscricao')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->select(
                'participante.cpf',
                'participante.nome',
                'atividade.identificador',
                'atividade.titulo',
                'inscricao.id',
                'inscricao.status',
                DB::raw('DATE_FORMAT(inscricao.data,"%d/%m às %H:%i") as data')
            )
            ->where([
                ['atividade.evento_id', '=', $this->getEdicaoAtiva()],
                //['atividade.tipo', '=', 'minicurso'],
            ])
            ->orderBy('inscricao.data', 'DESC')
            ->paginate($this->totalPage);
        if (!is_null($data)) {
            return view('home', compact('data', 'titulo'));
        }
    }

    private function getEdicaoAtiva()
    {
        return DB::table('participante')
            ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')
            ->select('participante.edicao_ativa')
            ->where('participante.id', '=', Auth::user()->id)
            ->get()[0]->edicao_ativa;
    }

    public function listarAtividadesParaInscricao()
    {
        $titulo = "Atividades para inscrição";
        $data = DB::table('atividade')
            ->select([
                'atividade.id', 'atividade.identificador', 'atividade.titulo',
                'local.descricao',
                DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data_inicio'),
                'atividade.data_fim', 'atividade.carga_horaria',
                'atividade.hora_inicio', 'atividade.hora_fim', 'maximo_participantes',
                'evento.data_inicio_insc', 'evento.data_fim_insc',
                'atividade.preco as preco'
            ])
            ->join('local', 'atividade.local_id', '=', 'local.id')
            ->join('evento', 'atividade.evento_id', '=', 'evento.id')
            ->where([
                ['atividade.evento_id', '=', $this->getEdicaoAtiva()],
                // ['atividade.tipo', '=', 'minicurso'],
            ])
            ->orderBy('atividade.data_inicio', 'asc')
            ->orderBy('atividade.hora_inicio')
            ->paginate(20);

        //$atividadeList = array();
        foreach ($data as $entry) {


            $inscritos = DB::table('inscricao')
                ->select('id')
                ->where([['inscricao.atividade_id', '=', $entry->id], ['inscricao.status', '<>', 'cancelado'],])
                ->count();

            $ja_inscrito = (DB::table('atividade')
                ->join('inscricao', 'inscricao.atividade_id', '=', 'atividade.id')
                ->where([
                    ['inscricao.atividade_id', '=', $entry->id],
                    ['inscricao.participante_id', '=', Auth::user()->id],
                    ['inscricao.status', '<>', 'cancelado']
                ])
                ->count() > 0);

            $liberarInscricao = false;
            $liberarBreve = false;
            $liberarEncerrado = false;
            $liberarEsgotato = false;

            if ($inscritos < $entry->maximo_participantes) {
                if (
                    date_create(date("Y-m-d")) >=  date_create($entry->data_inicio_insc)
                    && date_create(date("Y-m-d")) <= date_create($entry->data_fim_insc)
                ) {
                    // Para encerrar inscrição de minicursos já realizados, 
                    // porém dentro do periodo geral de inscrição
                    if (date_create(date("Y-m-d")) > date_create($entry->data_fim)) {
                        $liberarEncerrado = true;
                    } else {
                        $liberarInscricao = true;
                    }
                } else {
                    $liberarInscricao = false;
                    if (date_create(date("Y-m-d")) < date_create($entry->data_inicio_insc)) {
                        $liberarBreve = true;
                    } else if (date_create(date("Y-m-d")) > date_create($entry->data_fim_insc)) {
                        $liberarEncerrado = true;
                    }
                }
            } else {
                if (
                    date_create(date("Y-m-d")) >=  date_create($entry->data_inicio_insc)
                    && date_create(date("Y-m-d")) <= date_create($entry->data_fim_insc)
                ) {
                    if (date_create(date("Y-m-d")) > date_create($entry->data_fim)) {
                        $liberarEncerrado = true;
                    } else {
                        $liberarEsgotato = true;
                    }
                } else {
                    if (date_create(date("Y-m-d")) < date_create($entry->data_inicio_insc)) {
                        $liberarBreve = true;
                    } else if (date_create(date("Y-m-d")) > date_create($entry->data_fim_insc)) {
                        $liberarEncerrado = true;
                    }
                }
            }

            $atividades[] = array(
                'id' => $entry->id,
                'identificador' => $entry->identificador,
                'titulo' => $entry->titulo,
                'descricao' => $entry->descricao,
                'data_inicio' => $entry->data_inicio,
                'hora_inicio' => $entry->hora_inicio,
                'hora_fim' => $entry->hora_fim,
                'maximo_participantes' => $entry->maximo_participantes,
                'carga_horaria' => $entry->carga_horaria,
                //'data_inicio_insc' => $entry->data_inicio_insc,
                //'data_fim_insc' => $entry->data_fim_insc,
                'inscritos' => $inscritos,
                'preco' => $entry->preco,
                'liberar_inscricao' => $liberarInscricao,
                'liberar_breve' => $liberarBreve,
                'liberar_encerrado' => $liberarEncerrado,
                'liberar_esgotado' => $liberarEsgotato,
                'ja_inscrito' => $ja_inscrito
            );
        }
        //dd($atividades);


        if (!is_null($data)) {
            return response()
                ->json(['atividades' => $atividades, 'titulo' => $titulo]);
        }
    }

    public function listarInscricoesParticipante()
    {
        $titulo = "Minhas inscrições";
        $data = DB::table('inscricao')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->join('local', 'local.id', '=', 'atividade.local_id')
            ->select(
                'atividade.identificador',
                'atividade.titulo',
                'inscricao.id',
                'inscricao.status',
                'local.descricao',
                'atividade.carga_horaria',
                'atividade.hora_inicio',
                'atividade.hora_fim',
                'inscricao.presente',
                DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data_inicio'),
                'atividade.data_fim'
            )
            ->where([
                ['atividade.evento_id', '=', $this->getEdicaoAtiva()],
                ['inscricao.participante_id', '=', Auth::user()->id],
            ])
            ->orderBy('atividade.data_fim', 'DESC')
            ->paginate($this->totalPage);

        $inscricoes = array();
        foreach ($data as $entry) {
            if (date_create(date("Y-m-d")) > date_create($entry->data_fim)) {
                $encerrada = 1;
            } else {
                $encerrada = 0;
            }
            $inscricoes[] = array(
                'identificador' => $entry->identificador,
                'titulo' => $entry->titulo,
                'id' => $entry->id,
                'status' => $entry->status,
                'descricao' => $entry->descricao,
                'carga_horaria' => $entry->carga_horaria,
                'hora_inicio' => $entry->hora_inicio,
                'hora_fim' => $entry->hora_fim,
                'data_inicio' => $entry->data_inicio,
                'presente' => $entry->presente,
                'encerrada' => $encerrada
            );
        }

        if (!is_null($inscricoes)) {
            return response()
                ->json(['inscricoes' => $inscricoes, 'titulo' => $titulo]);
        }
    }

    //Area de "Ultimas inscrições" login coordenador
    public function listarInscricoesGerenciar()
    {
        $titulo = "Últimas inscrições";
        $data = DB::table('inscricao')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->select(
                'participante.cpf',
                'participante.nome',
                'atividade.identificador',
                'atividade.titulo',
                'inscricao.id',
                'inscricao.status',
                DB::raw('DATE_FORMAT(inscricao.data,"%d/%m/%Y %H:%i") as data')
            )
            ->where([
                ['atividade.evento_id', '=', $this->getEdicaoAtiva()],
                ['atividade.tipo', '=', 'minicurso'],
            ])
            ->orderBy('inscricao.data', 'DESC')
            ->paginate($this->totalPage);
        if (!is_null($data)) {
            return response()
                ->json(['data' => $data, 'titulo' => $titulo]);
        }
    }

    public function pesquisaNome(Request $request)
    {
        $titulo = "Últimas inscrições";
        $data = DB::table('inscricao')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->select(
                'participante.cpf',
                'participante.nome',
                'atividade.identificador',
                'atividade.titulo',
                'inscricao.id',
                'inscricao.status',
                DB::raw('DATE_FORMAT(inscricao.data,"%d/%m/%Y %H:%i") as data')
            )
            ->where([
                ['participante.nome', 'like', '%' . $request->input('nome') . '%'],
                ['atividade.evento_id', '=', $this->getEdicaoAtiva()],
                ['atividade.tipo', '=', 'minicurso'],
            ])
            ->orderBy('inscricao.data', 'DESC')
            ->paginate($this->totalPage);
        if (!is_null($data)) {
            return response()
                ->json(['data' => $data, 'titulo' => $titulo]);
        }
    }

    public function pesquisaCPF(Request $request)
    {
        $titulo = "Últimas inscrições";
        $data = DB::table('inscricao')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->select(
                'participante.cpf',
                'participante.nome',
                'atividade.identificador',
                'atividade.titulo',
                'inscricao.id',
                'inscricao.status',
                DB::raw('DATE_FORMAT(inscricao.data,"%d/%m/%Y %H:%i") as data')
            )
            ->where([
                ['participante.cpf', 'like', '%' . $request->input('cpf') . '%'],
                ['atividade.evento_id', '=', $this->getEdicaoAtiva()],
                ['atividade.tipo', '=', 'minicurso'],
            ])
            ->orderBy('inscricao.data', 'DESC')
            ->paginate($this->totalPage);
        if (!is_null($data)) {
            return response()
                ->json(['data' => $data, 'titulo' => $titulo]);
        }
    }

    public function alteraAnoSistema($id)
    {
        DB::table('participante')
            ->where('id', Auth::user()->id)
            ->update(['edicao_ativa' => $id]);
        return redirect('/home');
    }
}