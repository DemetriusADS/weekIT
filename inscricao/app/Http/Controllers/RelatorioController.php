<?php

namespace App\Http\Controllers;

use Auth;
use DB;

use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    private $ordem;
    private $atividade;

    public function __construct()
    {
        $this->ordem = "participantes";
        $this->atividade = null;
    }
    public function atividades()
    {
        if ($this->checkAuth()) {
            $getData = $this->getDb('atividade');
            return view('relatorios.relatorioAtividades', compact('getData'));
        }
    }
    public function setAtividadeTipo(Request $request)
    {
        $this->atividade = $request->id;
        return $this->atividades();
    }
    public function setOrdem(Request $request)
    {
        if (!empty($request->id)) {
            $this->ordem = $request->id;
            return $this->atividades();
        } else {
            return redirect()->route("relatorio.atividade");
        }
    }

    private function getDb($table)
    {

        if (!empty($this->atividade)) {
            if (!($this->ordem == "participantes")) {
                $getDB = DB::table($table)
                    ->Join('inscricao', 'inscricao.atividade_id', '=', $table . '.id')
                    ->select(
                        $table . '.id as atividadeID',
                        $table . '.identificador',
                        $table . '.titulo',
                        $table . '.maximo_participantes as max_participantes',
                        DB::raw(' count(inscricao.id) as participantes')
                    )
                    ->where([
                        [$table . '.evento_id', '=', Auth::user()->edicao_ativa],
                        ['atividade.tipo', '=', $this->atividade]
                    ])
                    ->groupBy("atividade.id")
                    ->orderBy($this->ordem)
                    ->get();
            } else {
                $getDB = DB::table($table)
                    ->Join('inscricao', 'inscricao.atividade_id', '=', $table . '.id')
                    ->select(
                        $table . '.id as atividadeID',
                        $table . '.identificador',
                        $table . '.titulo',
                        $table . '.maximo_participantes as max_participantes',
                        DB::raw(' count(inscricao.id) as participantes')
                    )
                    ->where([
                        [$table . '.evento_id', '=', Auth::user()->edicao_ativa],
                        ['atividade.tipo', '=', $this->atividade]
                    ])
                    ->groupBy("atividade.id")
                    ->orderBy($this->ordem)
                    ->get();
            }
        } else {
            if (!($this->ordem == "participantes")) {
                $getDB = DB::table($table)
                    ->Join('inscricao', 'inscricao.atividade_id', '=', $table . '.id')
                    ->select(
                        $table . '.id as atividadeID',
                        $table . '.identificador',
                        $table . '.maximo_participantes as max_participantes',
                        $table . '.titulo',
                        DB::raw(' count(inscricao.id) as participantes')
                    )
                    ->where($table . '.evento_id', '=', Auth::user()->edicao_ativa)
                    ->groupBy("atividade.id")
                    ->orderBy($this->ordem)
                    ->get();
            } else {
                $getDB = DB::table($table)
                    ->Join('inscricao', 'inscricao.atividade_id', '=', $table . '.id')
                    ->select(
                        $table . '.id as atividadeID',
                        $table . '.identificador',
                        $table . '.maximo_participantes as max_participantes',
                        $table . '.titulo',
                        DB::raw(' count(inscricao.id) as participantes')
                    )
                    ->where($table . '.evento_id', '=', Auth::user()->edicao_ativa)
                    ->groupBy("atividade.id")
                    ->orderBy($this->ordem, "DESC")
                    ->get();
            }
        }
        //dd($getDB);
        return $getDB;
    }
    private function checkAuth()
    {
        return (Auth::user()->tipo == 'coordenador');
    }
}