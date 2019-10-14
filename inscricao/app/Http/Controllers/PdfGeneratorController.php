<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use Auth;
use DNS1D;
use DNS2D;
use Eloquent;

class PdfGeneratorController extends Controller
{
    public function getInformations()
    {

        $eventoRecente = DB::table('evento')->get()->max('id');
        $userID = Auth::user();
        //dd($userID->tipo);
        if ($userID->tipo == 'coordenador') {
            if ($userID->edicao_ativa == $eventoRecente) {
                //pegar as variaveis do banco de participantes que estão cadastrados no evento
                $participantesData = DB::table('inscricao_eventos')
                    ->join('participante', 'participante.id', '=', 'inscricao_eventos.participante_id')
                    ->join('evento', 'evento.id', '=', 'inscricao_eventos.evento_id')
                    ->select(
                        'participante.nome_cracha as Nome_Cracha',
                        'participante.cpf as CPF',
                        'inscricao_eventos.qrcode as QRCODE'
                    )
                    ->where([
                        ['inscricao_eventos.evento_id', '=', $eventoRecente],
                    ])
                    ->get();
                foreach ($participantesData as $key => $value) {
                    $value->CPF = preg_replace("/\D/", "", $value->CPF);
                }
                //dd($participantesData);
                //$participantesData[0]->CPF = preg_replace("/\D/", "", $participantesData[0]->CPF);
                //echo DNS1D::getBarcodeHTML($participantesData[0]->CPF, "CODABAR");
                return $this->pdfGenerator($participantesData);
                //dd($participantesData);
            } else {
                return 'Só é possivel gerar uma lista do evento mais recente';
            }
        } else {
            return 'Você não possui permissao para esta operação. Procure um coordenador.';
        }
    }
    function pdfGenerator($participantesData)
    {
        //PDF::fake();
        /*
            A view está funcioando.
            --tem q colocar os foreach para o array e gerar o pdf
            --testar com mais de um cadastro no evento.
        */
        //dd($participantesData);
        //return view('pdf_view.crachas', compact('participantesData'));
        $pdf = PDF::loadView('pdf_view.crachas', compact('participantesData'));
        return $pdf->stream('teste.pdf');
        //return PDF::loadFile('http://www.github.com')->inline('github.pdf');
    }
}