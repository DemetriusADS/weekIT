<?php

namespace App\Http\Controllers;

use App\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class EventoUpdateController extends Controller
{
    protected $model            = '\App\Participante';
    protected $base_name_route  = 'Participante';

    public function update(Request $request)
    {
        $eventID = $request->id;
        $userID = Auth::user()->id;
        DB::table('participante')
            ->where('id', Auth::user()->id)
            ->update(['edicao_ativa' => $eventID]);
        DB::insert("INSERT INTO inscricao_eventos(evento_id, participante_id, created_at, updated_at) 
        VALUES ($eventID,$userID, now(), now())");;
        // armazenaEvento($request, $userID);
        //$newEvent = DB::table('evento')->max('id');
        //Auth::user()->edicao_ativa = DB::table('evento')->max('id');
        //if (Auth::user()->edicao_ativa == DB::table('evento')->max('id'))
        //echo ('success cadastrado com sucesso');

        //echo ('error Ops algo deu errado');
        // DB::table('participante')->where('id', '=', Auth::user()->id)
        //     ->update(['edicao_ativa' => DB::table('evento')->max('id')]);
        // DB::table('participante')
        // ->where('id', Auth::user()->id)
        //  ->update(['edicao_ativa' => $id]);
        return redirect('/home');
    }
    public function armazenaEvento($eventoRequest, $userID)
    {
        DB::insert("INSERT INTO inscricao_eventos(evento_id, participante_id) VALUES ($eventoRequest,$userID)");
    }
}
