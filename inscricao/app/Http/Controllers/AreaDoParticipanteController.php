<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class AreaDoParticipanteController extends AbstractController
{
    /**
     * @var $model \App\Local
     */
    protected $model            = '\App\Local';
    protected $base_name_route  = 'local';

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
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
    public function update(Request $request)
    {
        $entity = $this->model::find($request->input('id'));
        $route  = redirect()->route($this->model::$base_name_route . '.edit', ['id' => $request->input('id')]);

        if ($entity->update($request->all()))
            return $route->with('success', $entity . '  atualizado com sucesso');

        return $route->with('warning', 'Ops, algo deu errado');
    }

    public function areaInscricao()
    {
        $inscrito = DB::table('inscricao')
            ->select(
                'inscricao.atividade_id as atividadeInscrito',
                'inscricao.participante_id as participanteID'
            )->where([
                ['inscricao.participante_id', '=', Auth::user()->id],
            ])->get();
        $atividades = DB::table('atividade')
            // ->join('inscricao', 'inscricao.atividade_id', '=', 'atividade.id')
            ->select(
                'atividade.id as id',
                DB::raw('CONCAT(atividade.identificador," - ",atividade.titulo) AS atividade_titulo'),
                DB::raw('CONCAT(DATE_FORMAT(atividade.hora_inicio,"%H:%i"), " - ", DATE_FORMAT(atividade.hora_fim,"%H:%i")) AS horario'),
                DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  dataInicio'),
                DB::raw('DATE_FORMAT(atividade.data_inicio,"%d/%m/%Y") as  dataFim')
            )
            ->where([
                // ['inscricao.participante_id', '!=', Auth::user()->id],
                ['atividade.evento_id', '=', Auth::user()->edicao_ativa],
            ])->get();

        dd($inscrito);
    }
}