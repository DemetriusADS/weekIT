<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscricaoController extends AbstractController
{
    /**
     * @var $model \App\Inscricao
     */
    protected $model            = '\App\Inscricao';
    protected $base_name_route  = 'inscricao';

    public function index()
    {

        return view('inscricao.index', ['model' => $this->model]);
    }
    /**
     * @param InscricaoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(InscricaoRequest $request)
    {
        $flag_choque_horario = $this::hasChoqueHorario($request->input('atividade_id'), $request->input('participante_id'));
        
        $inscritos = DB::table('inscricao')
            ->select(DB::raw('COUNT(inscricao.atividade_id) as inscritos'), 'atividade.maximo_participantes')
            ->join('atividade','atividade.id','=','inscricao.atividade_id')
            ->where([['inscricao.atividade_id','=',$request->input('atividade_id')],['inscricao.status','<>','cancelado'],])
            ->first();        
        
        // Verifica se existe vaga para a atividade pretendida
        $hasVagas = ($inscritos->inscritos < $inscritos->maximo_participantes);
        
        $route  = redirect()->route($this->model::$base_name_route.'.create');       
        
        if ((!$flag_choque_horario) && ($hasVagas)){
            $input = $request->all();
            $ip = $request->ip();
            $user_agent = $request->server('HTTP_USER_AGENT');

            $entity = $this->model::insert($input, $ip, $user_agent);


            $route = redirect()->route($this->base_name_route.'.show', ['id' => $entity->id]);

            if(!is_null($entity)){
                return $route->with('success', $entity. ' cadastrado com sucesso');
            }            
        }

        if (!$hasVagas){
            return $route->with('error', 'Esta atividade já atingiu o limite de vagas.');   
        } else if ($flag_choque_horario){
            return $route->with('error', 'Você não pode se inscrever nesse evento pois já tem uma inscrição em outro    evento no mesmo horário.');   
        }
        
        return $route->with('error', 'Ops algo deu errado');
    }    
    
    function hasChoqueHorario($atividadeId, $participante_id, $atualizando = false){
        if ($participante_id == null) {
            $participante_id = \Auth::user()->id;
        }
        $flag_choque_horario = false;
        if ($atualizando) {
            $datas_inscrito = DB::table('inscricao')
                ->join('atividade','atividade.id','=','inscricao.atividade_id')
                ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'),'atividade.hora_inicio','atividade.hora_fim')
                ->where([['inscricao.participante_id','=', $participante_id],['atividade.evento_id','=',DB::table('participante')
                          ->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',\Auth::user()->id)->get()[0]->edicao_ativa],['atividade.id','<>',$atividadeId],])->get();            
        } else {
            $datas_inscrito = DB::table('inscricao')
                ->join('atividade','atividade.id','=','inscricao.atividade_id')
                ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'),'atividade.hora_inicio','atividade.hora_fim')
                ->where([['inscricao.participante_id','=', $participante_id],['atividade.evento_id','=',DB::table('participante')
                          ->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',\Auth::user()->id)->get()[0]->edicao_ativa],])->get();            
        }

        
        $data_pretendida = DB::table('atividade')
            ->select(DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  data_inicio'),'atividade.hora_inicio','atividade.hora_fim')
            ->where('atividade.id','=',$atividadeId)->get();
        
        foreach ($datas_inscrito as $data_inscrito) {
            foreach ($data_pretendida as $data_inscrever){
                if (date_create($data_inscrito->data_inicio) == date_create($data_inscrever->data_inicio)){
                    if ($this::intervaloEntreDatas($data_inscrito->hora_inicio, $data_inscrito->hora_fim, 
                        $data_inscrever->hora_inicio, $data_inscrever->hora_fim)){
                        $flag_choque_horario = true;     
                    }
                }    
            }
        } 
        
        return $flag_choque_horario; 
    }
    
    function intervaloEntreDatas($inicio, $fim, $agoraInicio, $agoraFim) {
        $inicioTimestamp = strtotime($inicio);
        $fimTimestamp = strtotime($fim);
        $agoraInicioTimestamp = strtotime($agoraInicio);
        $agoraFimTimestamp = strtotime($agoraFim);
        if ($agoraInicioTimestamp > $inicioTimestamp){
            return (($agoraInicioTimestamp >= $inicioTimestamp) && ($agoraInicioTimestamp <= $fimTimestamp));    
        } else {
            return (($inicioTimestamp >= $agoraInicioTimestamp) && ($inicioTimestamp <= $agoraFimTimestamp));    
        }

    }
    
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
    */
    public function update(InscricaoRequest $request)
    {
        $flag_choque_horario = $this::hasChoqueHorario($request->input('atividade_id'), $request->input('participante_id'), true);
        $entity = $this->model::find($request->input('id'));
        $route  = redirect()->route($this->model::$base_name_route.'.edit', ['id' => $request->input('id')] );

        if (!$flag_choque_horario){
            $input = $request->all();            
            $input = $request->except('data');               
            if($entity->update($input))
                return $route->with('success', $entity.'  atualizado com sucesso');            
        }

        if ($flag_choque_horario){
            return $route->with('error', 'Você não pode se inscrever nessa atividade pois já tem uma inscrição em outra atividade no mesmo horário.');   
        }        
        
        return $route->with('warning', 'Ops, algo deu errado');
    }

    public function alterarStatus(Request $request)
    {
        $entity = $this->model::find($request->input('id'));
        $route  = redirect()->route($this->model::$base_name_route.'.edit', ['id' => $request->input('id')] );

        if($entity->update($request->all()))
            return response()
                ->json(['data' => $entity]);

        return response()
            ->json(['warning' => 'Ops, algo deu errado']);

    }
    
    public function fazerInscricao(Request $request){
        $flag_choque_horario = $this::hasChoqueHorario($request->input('atividade_id'), \Auth::user()->id);
        if (!$flag_choque_horario){
            $request->request->add(['status' => "andamento"]);
            $request->request->add(['participante_id' => \Auth::user()->id]);
            $request->request->add(['atividade_id' => $request->input('atividade_id')]);

            $input = $request->all();
            $ip = $request->ip();
            $user_agent = $request->server('HTTP_USER_AGENT');

            $entity = $this->model::insert($input, $ip, $user_agent);
            if(!is_null($entity)){
                return response()
                    ->json(['resposta' => 1]);
            }
        }
        
        return response()
            ->json(['resposta' => 'Você não pode se inscrever nessa atividade pois já tem uma inscrição em outra atividade no mesmo horário.']);                                
    }
    
    public function removerInscricao(Request $request, $ip = null, $user_agent = null){
        
        $id = $request->get('id');
        $entity = $this->model::find($id);

        if($this->model::destroy($id, $ip, $user_agent)){
            return response()
                ->json(['resposta' => 1]);
        }
      
    }
}
