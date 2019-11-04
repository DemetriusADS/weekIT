<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
use DB;

class AtividadesPlanilha implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('atividade')
            ->select(
                'atividade.titulo as titulo',
                'atividade.carga_horaria as CH',
                'atividade.data_inicio',
                'atividade.data_fim',
                'atividade.tipo as tipo'
            )->where('atividade.evento_id', '=', Auth::user()->edicao_ativa)
            ->get();
    }
    public function headings(): array
    {
        return [
            'Nome da Atividade',
            'Carga Horaria',
            'Tipo',
            'Data Inicio',
            'Data Fim',
        ];
    }
    public function map($atividade): array
    {
        return [
            $atividade->titulo,
            $atividade->CH,
            $atividade->tipo,
            $atividade->data_inicio,
            $atividade->data_fim,
        ];
    }
}
