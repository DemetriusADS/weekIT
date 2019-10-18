<?php

namespace App;

use App\Http\DefaultModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Palestrante extends AbstractModel implements DefaultModel
{

    protected     $table            = 'palestrante';
    public static $base_name_route  = 'palestrante';
    public static $verbose_name     = 'palestrante';
    public static $verbose_plural   = 'palestrantes';
    public static $verbose_genre    = 'M';
    public static $controller       = 'PalestranteController';


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function search(Request $request)
    {

        $request_query      = $request->input('query');
        $page               = $request->input('pagination.page', 1);
        $perpage            = $request->input('pagination.perpage', 10000);
        $columns            = $request->input('columns',  ['*']);
        $sort               = $request->input('sort',  NULL);
        $excluded           = $request->input('excluded',  NULL);

        $query =  DB::table('palestrante')
            ->select([
                'palestrante.id as id',
                'palestrante.descricao as descricao',
                'palestrante.evento_id as evento_id',
                DB::raw('DATE_FORMAT(palestrante.created_at,"%d/%m/%Y %H:%i:%s") as created_at'),
                DB::raw('DATE_FORMAT(palestrante.updated_at,"%d/%m/%Y %H:%i:%s") as updated_at'),
            ])->where('palestrante.evento_id', '=', DB::table('participante')
                ->join('evento', 'evento.id', '=', 'participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id', '=', Auth::user()->id)->get()[0]->edicao_ativa)->orderBy('descricao');

        if (isset($request_query['descricao'])) {
            if (!empty($request_query['descricao'])) {
                $query->where('palestrante.descricao', 'like', '%' . $request_query['descricao'] . '%');
            }
        }

        if (isset($sort)) {
            $query->orderBy($sort['field'], $sort['sort']);
        }


        $paginator  = $query->paginate($perpage, $columns, 'page', $page);

        return response()->json([
            'meta' => [
                'page'      =>  $paginator->currentPage(),
                'pages'     => $paginator->lastPage(),
                'perpage'   => $paginator->perPage(),
                'total'     => $paginator->total(),
            ],
            'data' => $paginator->items()
        ]);
    }
    public static function verbose_name()
    {
        $verbose_name = 'palestrante';
        return response()->json($verbose_name);
    }


    /**
     * Colunas a serem exibidas na tabela que lista os registros
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function dataTablesColumns()
    {

        $columns = [
            [
                'field' => 'id',
                'title' => 'ID',
            ], [
                'field' => 'descricao',
                'title' => 'Descrição',
            ],
        ];

        return response()->json($columns);
    }

    public static function dataTablesSearchForm()
    {

        return  [
            'fields' => [
                'data' => [
                    'type'          => 'text',
                    'placeholder'   => 'Descrição',
                ],
            ]
        ];
    }

    public static function fieldsFormCreate()
    {
        $atividades = DB::table('atividade')
            ->select(
                'atividade.id as atividadeID',
                'atividade.titulo as titulo'
            )->where('atividade.evento_id', '=', Auth::user()->edicao_ativa)->get();
        //dd($atividades);
        $lista[] = null;
        foreach ($atividades as $value) {
            array_push($lista, [$value->atividadeID => $value->titulo]);
        };
        return  [
            'fields' => [
                [
                    'descricao' => [
                        'type'          => 'text',
                        'label'         => 'Descrição',
                        'placeholder'   => 'Descrição',
                        'required'      => 'required',
                    ],
                    'atividade' => [
                        'type' => 'select',
                        'options' => $lista,
                        'label' => 'Atividade',
                        'required' => 'required',
                    ],
                ],

                [
                    'id' => [
                        'type'          => 'hidden',
                    ],
                ],
            ]

        ];
    }

    public static function fieldsFormEdit()
    {
        return  self::fieldsFormCreate();
    }

    public function __toString()
    {
        return $this->descricao;
    }
}