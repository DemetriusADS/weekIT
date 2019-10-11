<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends AbstractController
{
    /**
     * @var $model \App\Monitor
     */
    protected $model            = '\App\Monitor';
    protected $base_name_route  = 'monitor';

    public function home()
    {
        $monitores = DB::table('monitor')->select('monitor.participante_id', 'participante.nome')
            ->join('participante', 'monitor.participante_id', '=', 'participante.id')
            ->orderBy('participante.nome')
            ->get();

        $atividades = DB::table('atividade')->select('atividade.id', 'atividade.identificador', 'atividade.titulo')
            ->whereNotIn('atividade.id', function ($q) {
                $q->select('atividade_has_monitor.atividade_id')
                    ->from('atividade_has_monitor')
                    ->where('atividade_has_monitor.monitor_id', '=', \Auth::user()->id);
            })
            ->where('atividade.evento_id', '=', DB::table('participante')
                ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')
                ->select('participante.edicao_ativa')
                ->where('participante.id', '=', \Auth::user()->id)->get()[0]->edicao_ativa)
            ->orderBy('atividade.identificador')
            ->get();

        return view('layouts.gerenciar-monitor', compact('monitores', 'atividades'));
    }

    /**
     * @param MonitorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MonitorRequest $request)
    {
        $participante_id = $request->input('participante_id');
        $input = $request->all();
        $ip = $request->ip();
        $user_agent = $request->server('HTTP_USER_AGENT');

        $entity = $this->model::insert($input, $ip, $user_agent);


        $route = redirect()->route($this->base_name_route . '.show', ['id' => $entity->id]);

        if (!is_null($entity)) {
            DB::update("UPDATE participante SET tipo = 'monitor' WHERE id = $participante_id");
            return $route->with('success', $entity . ' cadastrado com sucesso');
        }

        return $route->with('error', 'Ops algo deu errado');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MonitorRequest $request)
    {
        $entity = $this->model::find($request->input('id'));
        $route  = redirect()->route($this->model::$base_name_route . '.edit', ['id' => $request->input('id')]);

        if ($entity->update($request->all()))
            return $route->with('success', $entity . '  atualizado com sucesso');

        return $route->with('warning', 'Ops, algo deu errado');
    }


    /**
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,  $ip = null, $user_agent = null)
    {
        $participante_id = DB::table('monitor')->select('participante_id')
            ->where('id', '=', $request->input('id'))->get()[0]->participante_id;
        DB::delete("DELETE from atividade_has_monitor WHERE participante_id = $participante_id");
        $id = $request->get('id');
        $entity = $this->model::find($id);
        $route  = redirect()->route($this->base_name_route . '.index');


        if ($this->model::destroy($id, $ip, $user_agent)) {
            DB::update("UPDATE participante SET tipo = 'aluno' WHERE id = $participante_id");
            return $route->with('success', $entity . ' excluído com sucesso');
        }

        return $route->with('warning', $entity . ' não pode ser excluído');
    }

    public function carregarMonitorias()
    {
        $monitorias = DB::table('atividade_has_monitor')
            ->select(
                'atividade_has_monitor.monitor_id',
                'atividade_has_monitor.atividade_id',
                'participante.nome',
                'atividade.identificador',
                'atividade.titulo',
                DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as data'),
                'atividade.hora_inicio',
                'atividade.hora_fim'
            )
            ->join('participante', 'participante.id', '=', 'atividade_has_monitor.monitor_id')
            ->join('atividade', 'atividade.id', '=', 'atividade_has_monitor.atividade_id')
            ->orderBy('participante.nome')
            ->get();
        if (!is_null($monitorias)) {
            return response()
                ->json(['monitorias' => $monitorias]);
        }
    }

    public function vincularMonitor(Request $request)
    {
        $atividade_id = $request->input('atividade.id');
        $monitor_id = $request->input('monitor.id');
        $input = DB::insert("INSERT INTO atividade_has_monitor(atividade_id, monitor_id) VALUES ($atividade_id,$monitor_id)");
        if (!is_null($input)) {
            return response()
                ->json(['resposta' => 1]);
        }
    }

    public function removerMonitoria(Request $request)
    {
        $atividade_id = $request->input('atividade_id');
        $monitor_id = $request->input('monitor_id');
        $resposta = DB::delete("DELETE FROM atividade_has_monitor WHERE atividade_id = $atividade_id AND monitor_id = $monitor_id");
        return response()
            ->json(['resposta' => $resposta]);
    }

    public function carregarAtividades(Request $request)
    {
        $data = DB::table('atividade')->select('atividade.id', 'atividade.identificador', 'atividade.titulo', 'atividade.data_fim')
            ->whereNotIn('atividade.id', function ($q) use ($request) {
                $q->select('atividade_has_monitor.atividade_id')
                    ->from('atividade_has_monitor')
                    ->where('atividade_has_monitor.monitor_id', '=', $request->input('participante_id'));
            })
            ->where('atividade.evento_id', '=', DB::table('participante')
                ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')
                ->select('participante.edicao_ativa')
                ->where('participante.id', '=', \Auth::user()->id)->get()[0]->edicao_ativa)
            ->orderBy('atividade.identificador')
            ->get();
        $atividades1 = array();
        $atividades2 = array();
        foreach ($data as $atividade => $value) {
            if (!self::hasChoqueHorarioMonitoria($value->id, $request->input('participante_id'))) {
                $atividades1[] = $value;
            }
        }

        foreach ($atividades1 as $atividade => $value) {
            if (!self::hasChoqueHorarioInscricao($value->id, $request->input('participante_id'))) {
                $atividades2[] = $value;
            }
        }

        if (!is_null($atividades2)) {
            return response()
                ->json(['data' => $atividades2]);
        }
    }

    function hasChoqueHorarioMonitoria($atividadeId, $participante_id)
    {
        if ($participante_id == null) {
            $participante_id = \Auth::user()->id;
        }
        $flag_choque_horario = false;

        $datas_monitorias = DB::table('atividade_has_monitor')
            ->join('atividade', 'atividade.id', '=', 'atividade_has_monitor.atividade_id')
            ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'), 'atividade.hora_inicio', 'atividade.hora_fim')
            ->where([['atividade_has_monitor.monitor_id', '=', $participante_id], ['atividade.evento_id', '=', DB::table('participante')
                ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id', '=', \Auth::user()->id)->get()[0]->edicao_ativa],])->get();


        $data_pretendida = DB::table('atividade')
            ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'), 'atividade.hora_inicio', 'atividade.hora_fim')
            ->where('atividade.id', '=', $atividadeId)->get();

        foreach ($datas_monitorias as $data_monitoria) {
            foreach ($data_pretendida as $data_inscrever) {
                if (date_create($data_monitoria->data_inicio) == date_create($data_inscrever->data_inicio)) {
                    if ($this::intervaloEntreDatas(
                        $data_monitoria->hora_inicio,
                        $data_monitoria->hora_fim,
                        $data_inscrever->hora_inicio,
                        $data_inscrever->hora_fim
                    )) {
                        $flag_choque_horario = true;
                    }
                }
            }
        }

        return $flag_choque_horario;
    }

    function hasChoqueHorarioInscricao($atividadeId, $participante_id)
    {
        if ($participante_id == null) {
            $participante_id = \Auth::user()->id;
        }
        $flag_choque_horario = false;

        $datas_inscrito = DB::table('inscricao')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'), 'atividade.hora_inicio', 'atividade.hora_fim')
            ->where([['inscricao.participante_id', '=', $participante_id], ['atividade.evento_id', '=', DB::table('participante')
                ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id', '=', \Auth::user()->id)->get()[0]->edicao_ativa], ['inscricao.status', '<>', 'gratuito'],])->get();


        $data_pretendida = DB::table('atividade')
            ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'), 'atividade.hora_inicio', 'atividade.hora_fim')
            ->where('atividade.id', '=', $atividadeId)->get();

        foreach ($datas_inscrito as $data_inscrito) {
            foreach ($data_pretendida as $data_inscrever) {
                if (date_create($data_inscrito->data_inicio) == date_create($data_inscrever->data_inicio)) {
                    if ($this::intervaloEntreDatas(
                        $data_inscrito->hora_inicio,
                        $data_inscrito->hora_fim,
                        $data_inscrever->hora_inicio,
                        $data_inscrever->hora_fim
                    )) {
                        $flag_choque_horario = true;
                    }
                }
            }
        }

        return $flag_choque_horario;
    }

    function intervaloEntreDatas($inicio, $fim, $agoraInicio, $agoraFim)
    {
        $inicioTimestamp = strtotime($inicio);
        $fimTimestamp = strtotime($fim);
        $agoraInicioTimestamp = strtotime($agoraInicio);
        $agoraFimTimestamp = strtotime($agoraFim);
        if ($agoraInicioTimestamp > $inicioTimestamp) {
            return (($agoraInicioTimestamp >= $inicioTimestamp) && ($agoraInicioTimestamp <= $fimTimestamp));
        } else {
            return (($inicioTimestamp >= $agoraInicioTimestamp) && ($inicioTimestamp <= $agoraFimTimestamp));
        }
    }
}