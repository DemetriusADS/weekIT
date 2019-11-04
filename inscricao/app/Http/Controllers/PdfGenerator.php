<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use Auth;
use Illuminate\Support\Arr;

class PdfGenerator extends Controller
{
    private $getList;

    public function __construct()
    {
        $getList = session('getList');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getList = session('getList');
        return view('pdf_view.getnames', compact('getList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $getList = session('getList');
        $id['id'] = $request->input('id');
        if (is_array($request->input('id'))) {
            foreach ($id['id'] as $key => $value) {
                $getList[] = $value;
            }
            //dd($getList);
        } else {
            $getList[] = $id['id'];
            //dd($getList);
            //dd(is_array($getList));
        }
        session(['getList' => $getList]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrar(Request $request)
    {
        // dd($id);
        $nome = $request->nome;
        //dd($nome);
        $participantes = DB::table('inscricao_eventos')
            ->join('participante', 'participante.id', '=', 'inscricao_eventos.participante_id')
            ->join('evento', 'evento.id', '=', 'inscricao_eventos.evento_id')
            ->select(
                'participante_id as id',
                'participante.nome as nome'
            )
            ->where([
                ['inscricao_eventos.evento_id', '=', Auth::user()->edicao_ativa],
                ['participante.nome', 'like', "{$nome}%"]
            ])
            ->get();
        //dd($participantes);
        if (!is_null($participantes)) {
            return redirect()->back()->with('list', json_encode($participantes));
        } else {
            return redirect()->back()->with('list', []);
        }

        // return view('pdf_view.getnames', compact('participantes'));
    }
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleta($id)
    {
        $getList = session('getList');
        $ids = array_column($getList, 'id');
        $index = array_search($id, $ids);
        array_splice($getList, $index, 1);
        session(['getList' => $getList]);
        return redirect()->back();
    }
    public function getInformations($idVerificador = null)
    {

        $eventoRecente = DB::table('evento')->get()->max('id');
        $userID = Auth::user();
        if ($userID->tipo == 'coordenador') {
            if ($userID->edicao_ativa == $eventoRecente) {
                if (!is_null($idVerificador)) {
                    $getList = session()->get('getList');
                    $participantesData = DB::table('inscricao_eventos')
                        ->join('participante', 'participante.id', '=', 'inscricao_eventos.participante_id')
                        ->join('evento', 'evento.id', '=', 'inscricao_eventos.evento_id')
                        ->select(
                            'participante_id as id',
                            'participante.nome as nome',
                            'participante.nome_cracha as Nome_Cracha',
                            'participante.cpf as CPF',
                            'inscricao_eventos.qrcode as QRCODE'
                        )
                        ->whereIn(
                            'participante_id',
                            data_get($getList, [])
                        )
                        ->where('inscricao_eventos.evento_id', '=', $eventoRecente)
                        ->get();
                    //}                           

                    //dd($participantesData);
                    $getList = null;
                    session(['getList' => $getList]);
                    //dd($getList);

                } else {
                    // dd($userID->tipo);
                    //pegar as variaveis do banco de participantes que estão cadastrados no evento
                    $participantesData = DB::table('inscricao_eventos')
                        ->join('participante', 'participante.id', '=', 'inscricao_eventos.participante_id')
                        ->join('evento', 'evento.id', '=', 'inscricao_eventos.evento_id')
                        ->select(
                            'participante.nome as nome',
                            'participante.nome_cracha as Nome_Cracha',
                            'participante.cpf as CPF',
                            'inscricao_eventos.qrcode as QRCODE'
                        )
                        ->where([
                            ['inscricao_eventos.evento_id', '=', $eventoRecente],
                        ])
                        ->get();

                    //bnkbnnnndd($participantesData);
                    //$participantesData[0]->CPF = preg_replace("/\D/", "", $participantesData[0]->CPF);
                    //echo DNS1D::getBarcodeHTML($participantesData[0]->CPF, "CODABAR");

                    //dd($participantesData);
                }
                foreach ($participantesData as $key => $value) {
                    $value->CPF = preg_replace("/\D/", "", $value->CPF);
                }
                //dd($participantesData);
                return $this->pdfGenerator($participantesData);
            } else {
                return 'Só é possivel gerar uma lista do evento mais recente';
            }
        } else {
            return 'Você não possui permissao para esta operação. Procure um coordenador.';
        }
    }
    public function crachaAtividade($id)
    {
        //dd($id);
        $participantesData = DB::table('inscricao_eventos')
            ->join('participante', 'participante.id', '=', 'inscricao_eventos.participante_id')
            ->join('inscricao', 'inscricao.participante_id', '=', 'participante.id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')

            ->select(
                'participante.nome as nome',
                'participante.nome_cracha as Nome_Cracha',
                'participante.cpf as CPF',
                'inscricao_eventos.qrcode as QRCODE'
            )
            ->where([
                ['atividade.id', '=', $id],
            ])
            ->get();
        // dd($participantesData);
        if (!is_null($participantesData)) {
            foreach ($participantesData as $key => $value) {
                $value->CPF = preg_replace("/\D/", "", $value->CPF);
            }
            //dd($participantesData);
            return $this->pdfGenerator($participantesData);
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
        $pdf = PDF::loadView('pdf_view.crachas', compact('participantesData'))->setOption('margin-bottom', 20);
        return $pdf->stream('grachas.pdf');
        //return PDF::loadFile('http://www.github.com')->inline('github.pdf');
    }
}