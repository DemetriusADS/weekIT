<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipanteRequest;
use Carbon\Carbon;
use Auth;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipanteController extends AbstractController
{
    /**
     * @var $model \App\Participante
     */
    protected $model            = '\App\Participante';
    protected $base_name_route  = 'participante';

    /**
     * @param ParticipanteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ParticipanteRequest $request)
    {
        if (Auth::check()) {
            if (Auth::user()->tipo == "coordenador") {
                $input = $request->all();
                $input['password'] = Hash::make($request->get('password'));
                $input['roles_id'] = Role::where('slug', 'aluno')->first()->id;
                $input['cadastrado_em'] = date('Y-m-d');
                $input['edicao_ativa'] = Auth::user()->edicao_ativa; //DB::table('evento')->max('id');
                $ip = $request->ip();
                $user_agent = $request->server('HTTP_USER_AGENT');
                //dd($input['roles_id']);
                $entity = $this->model::insert($input, $ip, $user_agent);
                $participanteCriado = DB::table('participante')
                    ->select(
                        'participante.id as id',
                        'participante.edicao_ativa as evento'
                    )->where('id', \DB::raw("(select max(`id`) from participante)"))->get();
                // dd($participanteCriado[0]->id);
                $eventoID = $participanteCriado[0]->evento;
                $participanteID = $participanteCriado[0]->id;
                $qrcode = "https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=http://localhost/participanteinfo/" . $participanteCriado[0]->id . "/" . $participanteCriado[0]->evento;
                DB::insert("INSERT INTO inscricao_eventos(evento_id, participante_id,qrcode, created_at, updated_at) 
                VALUES ($eventoID,$participanteID,'$qrcode', now(), now())");;
            }
        } else {

            $input = $request->all();
            $ip = $request->ip();
            $user_agent = $request->server('HTTP_USER_AGENT');

            $entity = $this->model::insert($input, $ip, $user_agent);
        }

        $route = redirect()->route($this->base_name_route . '.show', ['id' => $entity->id]);

        if (!is_null($entity)) {
            return $route->with('success', $entity . ' cadastrado com sucesso');
        }

        return $route->with('error', 'Ops algo deu errado');
    }

    /**
     * @param ParticipanteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ParticipanteRequest $request)
    {
        // dd($request->id);
        $entity = $this->model::find($request->id);
        //dd($entity);

        $route  = redirect()->route($this->model::$base_name_route . '.edit', ['id' => $request->id]);
        $input = $request->all();
        if ($request->input('password') == null) {
            $input = $request->except('password');
        } else {
            $input['password'] = Hash::make($request->get('password'));
        }

        if ($entity->update($input))
            return $route->with('success', $entity . '  atualizado com sucesso');

        return $route->with('warning', 'Ops, algo deu errado');
    }
}