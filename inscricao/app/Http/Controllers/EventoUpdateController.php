<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class EventoUpdateController extends Controller
{
    protected $model            = '\App\Participante';
    protected $base_name_route  = 'Participante';

    public function update(Request $request)
    {
        $todosEventos = DB::table('evento')->get();
        $eventID = $request->id;
        // dd($eventID);
        $check = 0;
        if (is_numeric($eventID)) {
            //dd($eventID);
            foreach ($todosEventos as $eventos => $value) {
                if ($eventID == $value->id) {
                    //dd($eventID);
                    $userID = Auth::user()->id;
                    $qrcode = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http://localhost/participanteinfo/" . $userID . "/" . $eventID;
                    DB::table('participante')
                        ->where('id', Auth::user()->id)
                        ->update(['edicao_ativa' => $eventID]);
                    DB::insert("INSERT INTO inscricao_eventos(evento_id, participante_id,qrcode, created_at, updated_at) 
                    VALUES ($eventID,$userID,'$qrcode', now(), now())");;
                    $check++;
                }
            }
        } else {
            header("refresh: 3;" . route('home'));
            return 'Error';
        }
        if ($check == 0) {
            return "Error 500";
        }
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