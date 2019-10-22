<?php

namespace App\Http\Controllers;


use App\Exports\AtividadesPlanilha;
use App\Exports\ParticipantesPlanilha;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function atividadesPlanilha()
    {
        return Excel::download(new AtividadesPlanilha, 'atividade.xlsx');
    }
    public function participantesPlanilha()
    {
        return Excel::download(new ParticipantesPlanilha, 'participantes.xlsx');
    }
}
