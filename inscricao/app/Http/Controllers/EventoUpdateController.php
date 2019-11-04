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
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('Y-m-d');
        if (is_numeric($eventID)) {
            //dd($eventID);
            foreach ($todosEventos as $eventos => $value) {
                if ($eventID == $value->id) {
                    if ($date >= $value->data_inicio_insc   && $date <= $value->data_fim_insc) {
                        //dd($eventID);
                        $userID = Auth::user()->id;
                        $qrcode = "https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=http://weekit.conquista.ifba.edu.br/inscricao/participanteinfo/" . $userID . "/" . $eventID;
                        DB::table('participante')
                            ->where('id', Auth::user()->id)
                            ->update(['edicao_ativa' => $eventID]);
                        DB::insert("INSERT INTO inscricao_eventos(evento_id, participante_id,qrcode, created_at, updated_at) 
                    VALUES ($eventID,$userID,'$qrcode', now(), now())");
                        $check++;
                    } else {
                        return 'As inscrições para esse evento já terminaram.';
                    }
                }
            }
        } else {
            header("refresh: 3;" . route('home'));
            return 'Error';
        }
        if ($check == 0) {
            return "Error 500";
        }
        return redirect('/home');
    }
}