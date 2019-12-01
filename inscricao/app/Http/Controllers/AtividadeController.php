<?php


namespace App\Http\Controllers;

use App\Http\Requests\AtividadeRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\CancelamentoEvent;
use Illuminate\Support\Facades\DB;
use PDF;

class AtividadeController extends AbstractController
{
    /**
     * @var $model \App\Atividade
     */
    protected $model            = '\App\Atividade';
    protected $base_name_route  = 'atividade';

    public function home()
    {
        return view('layouts.gerenciar-presenca');
    }

    public function sorteio()
    {
        return view('layouts.sorteio');
    }

    /**
     * @param AtividadeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AtividadeRequest $request)
    {
        $request['evento_id'] = DB::table('participante')
            ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')
            ->select('participante.edicao_ativa')
            ->where('participante.id', '=', \Auth::user()->id)->get()[0]->edicao_ativa;

        $input = $request->all();
        $ip = $request->ip();
        $user_agent = $request->server('HTTP_USER_AGENT');

        $entity = $this->model::insert($input, $ip, $user_agent);


        $route = redirect()->route($this->base_name_route . '.show', ['id' => $entity->id]);

        if (!is_null($entity)) {
            return $route->with('success', $entity . ' cadastrado com sucesso');
        }

        return $route->with('error', 'Ops algo deu errado');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AtividadeRequest $request)
    {
        $entity = $this->model::find($request->input('id'));
        $route  = redirect()->route($this->model::$base_name_route . '.edit', ['id' => $request->input('id')]);

        if ($entity->update($request->all()))
            return $route->with('success', $entity . '  atualizado com sucesso');

        return $route->with('warning', 'Ops, algo deu errado');
    }

    public function carregarAtividades()
    {
        if (\Auth::user()->tipo != 'coordenador') {
            $atividades = DB::table('atividade_has_monitor')
                ->select(
                    'atividade.id',
                    'atividade.identificador',
                    'atividade.titulo',
                    'atividade.tipo',
                    DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data_inicio')
                )
                ->join('atividade', 'atividade.id', '=', 'atividade_has_monitor.atividade_id')
                ->where([
                    ['atividade_has_monitor.monitor_id', '=', \Auth::user()->id],
                    ['atividade.data_fim', '>=', date_create(date("Y-m-d"))],
                ])
                ->orderBy('atividade.identificador')
                ->get();
        } else {
            $atividades = DB::table('atividade')
                ->select(
                    'atividade.id',
                    'atividade.identificador',
                    'atividade.titulo',
                    'atividade.tipo',
                    DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data_inicio')
                )
                ->where('atividade.evento_id', '=', \Auth::user()->edicao_ativa)
                ->orderBy('atividade.identificador')
                ->get();
        }

        if (!is_null($atividades)) {
            return response()
                ->json(['atividades' => $atividades]);
        }
    }

    public function carregarParticipantes(Request $request)
    {
        $atividade_tipo = DB::table('atividade')
            ->select('atividade.tipo')
            ->where('atividade.id', '=', $request->input('atividade_id'))
            ->get();
        if ($atividade_tipo[0]->tipo == "minicurso") {
            $participantes = DB::table('inscricao')
                ->select(
                    'participante.id',
                    'participante.cpf',
                    'participante.nome',
                    'inscricao.id as inscricao_id',
                    'inscricao.presente',
                    'atividade.data_inicio as data',
                    'inscricao.status'
                )
                ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
                ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
                ->where('inscricao.atividade_id', '=', $request->input('atividade_id'))
                ->orderBy('participante.nome')
                ->get();
            //dd($participantes);
            if (!is_null($participantes)) {
                return response()
                    ->json(['participantes' => $participantes, 'tipo' => $atividade_tipo[0]->tipo]);
            }
        } else {
            return response()
                ->json(['participantes' => 1, 'tipo' => $atividade_tipo[0]->tipo]);
        }
    }

    public function setarPresenca(Request $request)
    {
        $id = $request->input('inscricao_id');
        $presente = $request->input('presente');
        $input = DB::update("UPDATE inscricao SET presente = $presente, updated_at = now() WHERE id = $id");
        if (!is_null($input)) {
            return response()
                ->json(['resposta' => $input]);
        }
    }

    public function buscaParticipante(Request $request)
    {
        $participante = DB::table('participante')
            ->select('participante.id', 'participante.cpf', 'participante.nome')
            ->where('participante.cpf', '=', self::Mask("###.###.###-##", $request->input('cpf')))
            ->get();

        if (!is_null($participante)) { //
            $presente = 0;
            if (count($participante) > 0) {
                $has_presenca = DB::table('inscricao')
                    ->select('inscricao.presente')
                    ->where([
                        ['inscricao.atividade_id', '=', $request->input('atividade_id')],
                        ['inscricao.participante_id', '=', $participante[0]->id],
                    ])
                    ->get();
                if (count($has_presenca)) {
                    $presente = $has_presenca[0]->presente;
                }
            }
            return response()
                ->json(['participante' => $participante, 'presente' => $presente]);
        }
    }

    function Mask($mask, $str)
    {

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }

    public function setarPresencaCode(Request $request)
    {
        $participante_id = $request->input('participante_id');
        $atividade_id = $request->input('atividade_id');
        $presente_is_set = DB::table('inscricao')
            ->select('inscricao.presente')
            ->where([
                ['inscricao.participante_id', '=', $participante_id],
                ['inscricao.atividade_id', '=', $atividade_id]
            ])->get()[0]->presente;
        $atividade_tipo = DB::table('atividade')
            ->select('atividade.tipo')
            ->where('atividade.id', '=', $atividade_id)->get()[0]->tipo;

        if ($atividade_tipo == 'minicurso') {
            return response()
                ->json(['resposta' => self::setaPresencaMinicurso($participante_id, $atividade_id)]);
        } else {
            if ($presente_is_set == 0) {
                $input = DB::update("UPDATE inscricao SET presente = 1, updated_at = now() WHERE participante_id = $participante_id and atividade_id = 
                    $atividade_id");
                return response()
                    ->json(['resposta' => $input]);
            }
        }
    }

    function setaPresencaMinicurso($participante_id, $atividade_id)
    {
        $has_inscricao = DB::table('inscricao')
            ->select('id')
            ->where([['participante_id', '=', $participante_id], ['atividade_id', '=', $atividade_id],])->count();
        if ($has_inscricao == 0) {
            return -1; // Não inscrito na atividade
        } else {
            $status = DB::table('inscricao')
                ->select('status')
                ->where([['participante_id', '=', $participante_id], ['atividade_id', '=', $atividade_id],])->get()[0]->status;
            if ($status == 'pago' || $status == 'isento') {
                return DB::update("UPDATE inscricao set presente = 1 WHERE participante_id = $participante_id and atividade_id = $atividade_id");
            } else {
                return -2; // Inscrito, porém não pago
            }
        }
    }

    public function realizarSorteio(Request $request)
    {
        $lista = $request->input('lista');
        if ($lista != null) {
            $codigos = explode(",", $lista);
            $participantes = DB::table('inscricao')
                ->select('participante_id')
                ->where('atividade_id', '=', $request->input('atividade_id'))
                ->whereNotIn('participante_id', $codigos)
                ->get();
        } else {
            $participantes = DB::table('inscricao')
                ->select('participante_id')
                ->where('atividade_id', '=', $request->input('atividade_id'))
                ->get();
        }

        $sorteado = rand(0, count($participantes) - 1);

        $ganhador = DB::table('participante')
            ->select('id', 'nome', 'email')
            ->where('id', '=', $participantes[$sorteado]->participante_id)
            ->get();

        return response()
            ->json(['ganhador' => $ganhador]);
    }

    public function cancelarAtividade(Request $request)
    {

        if (\Auth::user()->tipo == 'coordenador') {
            $atividade = DB::table('atividade')
                ->join('inscricao', 'inscricao.atividade_id', '=', 'atividade.id')
                ->select('atividade.id')
                ->where('atividade.id', '=', $request->id)
                ->get();
            if (!is_null($atividade)) {
                $inscritos = DB::table('inscricao')
                    ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
                    ->select(
                        'inscricao.id',
                        'inscricao.participante_id as participante_id',
                        'participante.email as email'
                    )
                    ->where('inscricao.atividade_id', '=', $request->id)
                    ->get();
                DB::table('atividade')
                    ->where('atividade.id', '=', $request->id)
                    ->update(['maximo_participantes' => 0]);
                foreach ($inscritos as $key => $value) {
                    $update = DB::table('inscricao')
                        ->where('inscricao.id', '=', $value->id)
                        ->update(['status' => 'cancelado']);
                    event(new CancelamentoEvent($value->participante_id, $value->email, $request->id));
                }
                return redirect('/home');
            }
        }
    }

    public function gerarRelatorio(Request $request)
    {
        //dd($request->id);
        $atividadeLista = DB::table('atividade')
            ->join('inscricao', 'inscricao.atividade_id', '=', 'atividade.id')
            ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
            ->select(
                'participante.nome as nomeAluno',
                'inscricao.status as status',
                'participante.telefone1',
                'participante.email'
            )
            ->where([
                ['atividade.id', '=', $request->id]
            ])
            ->orderBy('nomeAluno')
            ->get();
        //dd($atividadeLista);
        if (DB::table('atividade')->select('atividade.tipo')->where('atividade.id', '=', $request->id)->get()[0]->tipo == 'minicurso') {
            $atividadeInfo = DB::table('atividade')
                ->join('atividade_has_palestrante', 'atividade_has_palestrante.atividade_id', '=', 'atividade.id')
                ->join('palestrante', 'palestrante.id', '=', 'atividade_has_palestrante.palestrante_id')
                ->join('atividade_has_monitor', 'atividade_has_monitor.atividade_id', '=', 'atividade.id')
                ->join('participante', 'participante.id', '=', 'atividade_has_monitor.monitor_id')
                ->join('local', 'local.id', '=', 'atividade.local_id')
                ->select(
                    'atividade.tipo as tipo',
                    'atividade.titulo as titulo',
                    'participante.nome as monitor',
                    DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data'),
                    DB::raw('DATE_FORMAT(atividade.hora_inicio,"%H:%i") as horaInicio'),
                    DB::raw('DATE_FORMAT(atividade.hora_fim,"%H:%i") as horaFim'),
                    'local.descricao as local',
                    'palestrante.descricao as nomePalestrante'
                )
                ->where('atividade.id', '=', $request->id)
                ->get();
        } else {
            $atividadeInfo = DB::table('atividade')
                ->join('atividade_has_palestrante', 'atividade_has_palestrante.atividade_id', '=', 'atividade.id')
                ->join('palestrante', 'palestrante.id', '=', 'atividade_has_palestrante.palestrante_id')
                ->join('local', 'local.id', '=', 'atividade.local_id')
                ->select(
                    'atividade.tipo as tipo',
                    'atividade.titulo as titulo',
                    DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data'),
                    DB::raw('DATE_FORMAT(atividade.hora_inicio,"%H:%i") as horaInicio'),
                    DB::raw('DATE_FORMAT(atividade.hora_fim,"%H:%i") as horaFim'),
                    'local.descricao as local',
                    'palestrante.descricao as nomePalestrante'
                )
                ->where('atividade.id', '=', $request->id)
                ->get();
        }
        //dd($atividadeInfo);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('pdf_view.listasPresenca', compact('atividadeLista', 'atividadeInfo'));
        return $pdf->stream('listaPresenca' . $request->id . '.pdf');
        return view('pdf_view.listasPresenca', compact('atividadeLista', 'atividadeInfo'));
    }
}