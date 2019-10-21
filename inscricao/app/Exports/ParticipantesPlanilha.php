<?php

namespace App\Exports;

use App\Participante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
use DB;

class ParticipantesPlanilha implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('participante')
            ->select(
                'participante.nome',
                'participante.email',
                'participante.cpf'
            )
            ->where([
                ['participante.edicao_ativa', '=', Auth::user()->edicao_ativa],
                ['participante.tipo', '=', 'coordenador']
            ])
            ->get();
    }
    public function headings(): array
    {
        return [
            'CPF',
            'Nome',
            'Email',
        ];
    }
    public function map($participante): array
    {
        return [
            $participante->cpf,
            $participante->nome,
            $participante->email,
        ];
    }
}