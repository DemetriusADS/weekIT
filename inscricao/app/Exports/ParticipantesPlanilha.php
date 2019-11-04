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
            ->join('inscricao', 'inscricao.participante_id', '=', 'participante.id')
            ->join('atividade', 'atividade.id', '=', 'inscricao.atividade_id')
            ->select(
                'participante.nome',
                'participante.email',
                'participante.cpf',
                'atividade.titulo as ativTitulo'
            )
            ->where([
                ['participante.edicao_ativa', '=', Auth::user()->edicao_ativa],
                ['inscricao.presente', '=', '1']
                //['participante.tipo', '=', 'coordenador']
            ])
            ->orderBy('nome')
            ->get();
    }
    public function headings(): array
    {
        return [
            'CPF',
            'Nome',
            'Email',
            'Atividade'
        ];
    }
    public function map($participante): array
    {
        return [
            $participante->cpf,
            $participante->nome,
            $participante->email,
            $participante->ativTitulo
        ];
    }
}