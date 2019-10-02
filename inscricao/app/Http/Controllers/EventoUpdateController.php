<?php

namespace App\Http\Controllers;

use App\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoUpdateController extends Controller
{
    protected $model            = '\App\Participante';
    protected $base_name_route  = 'Participante';

    public function update()
    {
        //$newEvent = DB::table('evento')->max('id');
        //Auth::user()->edicao_ativa = DB::table('evento')->max('id');
        //if (Auth::user()->edicao_ativa == DB::table('evento')->max('id'))
        //echo ('success cadastrado com sucesso');

        //echo ('error Ops algo deu errado');
        DB::table('participante')->where('id', '=', Auth::user()->id)
            ->update(['edicao_ativa' => DB::table('evento')->max('id')]);
        // DB::table('participante')
        // ->where('id', Auth::user()->id)
        //  ->update(['edicao_ativa' => $id]);
    }
}
