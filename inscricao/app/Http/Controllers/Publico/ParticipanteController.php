<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\AbstractController;
use App\Http\Requests\ParticipanteRequest;
use App\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipanteController extends AbstractController
{
    /**
     * @var $model \App\Participante
     */
    protected $model            = '\App\Participante';
    protected $base_name_route  = 'Participante';

    public function create()
    {
        return view('publico.participante.cadastrar', ['model' => $this->model]);
    }
    /**
     * @param \App\Http\Requests\Publico\ParticipanteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\App\Http\Requests\Publico\ParticipanteRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->get('password'));
        $input['roles_id'] = Role::where('slug', 'aluno')->first()->id;
        $input['cadastrado_em'] = date_create(date('Y-m-d'));
        $input['edicao_ativa'] = DB::table('evento')->max('id');

        $entity = $this->model::insert($input);

        $route = redirect()->route('publico.participante.create');

        if(!is_null($entity)){
            return $route->with('success','Seu cadastro foi efetuado com sucesso');
        }

        return $route->with('error', 'Ops algo deu errado');
    }

}
