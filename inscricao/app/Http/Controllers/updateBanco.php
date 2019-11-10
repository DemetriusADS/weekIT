<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use Auth;
use Illuminate\Support\Arr;

class updateBanco extends Controller
{
      public function updateDB()
      {
            /*DB::table('participante')
                  ->where('instituicao', '=', 'ifba')
                  ->orWhere('instituicao', '=', 'IFBA - VCA')
                  ->orWhere('instituicao', '=', 'IFBA')
                  ->orWhere('instituicao', '=', 'IFBA Instituto Federal da Bahia')
                  ->orWhere('instituicao', '=', 'Institu')
                  ->orWhere('instituicao', '=', 'Ifiba')
                  ->orWhere('instituicao', '=', 'Instituto Federal (IFBA)')
                  ->orWhere('instituicao', '=', 'Instituto federal de educação, ciência e tecnologia da Bahia')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educação Ciência e Tecnologia da Bahia')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educação Ciência e Tecnologia')
                  ->orWhere('instituicao', '=', 'ifba vitoria da conquista')
                  ->orWhere('instituicao', '=', 'Instituto Federal da Bahia')
                  ->orWhere('instituicao', '=', 'Instituto Federal da Bahia - IFBA')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educação , Ciência e Tecnologia da Bahia')
                  ->orWhere('instituicao', '=', 'INSTITUTO FEDERAL DE EDUCAÇÃO , CIÊNCIA E TECNOLOGIA DA BAHIA')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educa&ccedil;&atilde;o, Ci&ecirc;ncia e Tecnologia da Bahia (IFBA)')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educação, Ciência e Tecnologia Baiano.')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educação, Ciência e Tecnologia da Bahia (IFBA)')
                  ->orWhere('instituicao', '=', 'Instituto Federal de Educa&ccedil;&atilde;o, Ci&ecirc;ncia e Tecnologia da Bahia - IFBA')

                  ->update(['instituicao' => 'Sim']);*/

            //LIBERAR DEPOIS

            /* DB::table('participante')
                  ->where('instituicao', '!=', 'Sim')
                  ->orWhere('instituicao', '=', Null)
                  ->update(['instituicao' => 'Não']);*/
            echo (' <form action="' . route("updateDB") . '" method="GET">
                  <div class="alert alert-dismissible bg-info" "> 
                  <button type="submit" class="btn btn-info">Atualizar</button>
                 </div>
              </form>');
            $participantes = DB::table('inscricao_eventos')
                  /*->where('edicao_ativa', '=', '2')->orWhere('edicao_ativa', '=', '1')*/->get();
            dd($participantes);
            foreach ($participantes as $key => $value) {
                  //dd($value->edicao_ativa);
                  DB::insert("INSERT INTO inscricao_eventos(participante_id, evento_id) VALUES ($value->id,$value->edicao_ativa)");
            }
      }
}