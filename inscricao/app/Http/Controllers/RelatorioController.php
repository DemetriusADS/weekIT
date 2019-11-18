<?php

namespace App\Http\Controllers;

use Auth;
use DB;

use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function atividades()
    {
        if ($this->checkAuth()) {
            $getData = $this->getDb('atividade');
            return view('relatorios.relatorioAtividades', compact('getData'));
        }
    }
    private function getDb($table)
    {
        $getDB = DB::table($table)
            ->Join('inscricao', 'inscricao.atividade_id', '=', $table . '.id')
            ->select(
                $table . '.id as atividadeID',
                $table . '.identificador',
                $table . '.titulo',
                DB::raw(' count(inscricao.id) as participantes')
            )
            ->where($table . '.evento_id', '=', Auth::user()->edicao_ativa)
            ->orderBy('identificador')
            ->get();
        dd($getDB);
        return $getDB;
    }
    private function checkAuth()
    {
        return (Auth::user()->tipo == 'coordenador');
    }
}