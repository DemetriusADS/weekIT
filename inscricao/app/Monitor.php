<?php

namespace App;

use App\Http\DefaultModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Monitor extends AbstractModel implements DefaultModel
{

    protected     $table            = 'monitor';
    public static $base_name_route  = 'monitor';
    public static $verbose_name     = 'monitor';
    public static $verbose_plural   = 'monitores';
    public static $verbose_genre    = 'M';
    public static $controller       = 'MonitorController';


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

        $query =  DB::table('monitor')
            ->select([
                'monitor.id as id',
                'monitor.participante_id as participante_id',
                'participante.nome as nome',
                DB::raw('DATE_FORMAT(monitor.created_at,"%d/%m/%Y %H:%i:%s") as created_at'),
                DB::raw('DATE_FORMAT(monitor.updated_at,"%d/%m/%Y %H:%i:%s") as updated_at'),
            ])->orderBy('nome');

        $query->join('participante', 'participante.id', '=', 'monitor.participante_id');

        if (isset($request_query['nome'])) {
            if (!empty($request_query['descricao'])) {
                $query->where('participante.nome', 'like', '%' . $request_query['nome'] . '%');
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
            ],
            [
                'field' => 'nome',
                'title' => 'Nome',
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
                    'placeholder'   => 'Nome',
                ],
            ]
        ];
    }

    public static function fieldsFormCreate()
    {

        return  [
            'fields' => [
                [
                    'participante_id' => [
                        'type'        => 'select',
                        'options'     => DB::table('participante')->select(['nome', 'id'])->orderBy('nome')->get()->pluck('nome', 'id'),
                        'label'       => 'Participante',
                        'placeholder' => 'Participante',
                        'required'    => 'required',
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
        return "Monitor: " . DB::table('participante')->select('nome')->where('id', '=', $this->participante_id)->get()[0]->nome;
    }
    public static function verbose_name()
    {
        $verbose_name = 'monitor';
        return response()->json($verbose_name);
    }
}
