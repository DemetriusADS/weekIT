<?php

namespace App;

use App\Http\DefaultModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Evento extends AbstractModel implements DefaultModel
{

    protected     $table            = 'evento';
    public static $base_name_route  = 'evento';
    public static $verbose_name     = 'evento';
    public static $verbose_plural   = 'eventos';
    public static $verbose_genre    = 'M';
    public static $controller       = 'EventoController';
    

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function search(Request $request){

        $request_query      = $request->input('query');
        $page               = $request->input('pagination.page', 1);
        $perpage            = $request->input('pagination.perpage', 10000);
        $columns            = $request->input('columns',  ['*']);
        $sort               = $request->input('sort',  NULL);
        $excluded           = $request->input('excluded',  NULL);

        $query =  DB::table('evento')
           ->select([
                'evento.id as id',
                'evento.nome as nome',
                'evento.sigla as sigla',
                'evento.ano as ano',
                'evento.edicao as edicao',
                 DB::raw('DATE_FORMAT(evento.data_inicio,"%d/%m/%Y") as data_inicio'),
                 DB::raw('DATE_FORMAT(evento.data_fim,"%d/%m/%Y") as data_fim'),
                 DB::raw('DATE_FORMAT(evento.data_inicio_insc,"%d/%m/%Y") as data_inicio_insc'),
                 DB::raw('DATE_FORMAT(evento.data_fim_insc,"%d/%m/%Y") as data_fim_insc'),
            ])->orderBy('nome');

        
        if(isset($request_query['nome'])){
            if(!empty($request_query['nome'])){
                $query->where('evento.nome', 'like', '%'.$request_query['nome'].'%');
            }
        }

        if(isset($request_query['sigla'])){
            if(!empty($request_query['sigla'])){
                $query->where('evento.sigla', 'like', '%'.$request_query['sigla'].'%');
            }
        }

        if(isset($sort)){
            $query->orderBy($sort['field'],$sort['sort']);
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
    public static function dataTablesColumns(){

        $columns = [
            [
                'field' => 'nome',
                'title' => 'Nome',
            ],[
                'field' => 'sigla',
                'title' => 'Sigla',
            ],[
                'field' => 'ano',
                'title' => 'Ano',
            ],[
                'field' => 'edicao',
                'title' => 'Edição',
            ],
        ];

        return response()->json($columns);
    }

    public static function dataTablesSearchForm(){

        return  [
            'fields' =>[
                'nome' => [
                    'type'          => 'text',
                    'placeholder'   => 'Nome',
                ],
/*                'local_id' => [
                    'type'          => 'select',
                    'options'       => Local::select('descricao', 'id')->pluck('descricao', 'id'),
                    'label'         => 'Local',
                    'placeholder'   => 'Local',
                    'required'      => 'required',
                ],*/
            ]
        ];
    }

    public static function fieldsFormCreate(){

        return  [
            'fields' =>[
                [
                    'nome' => [
                        'type'          => 'text',
                        'label'         => 'Nome',
                        'placeholder'   => 'Nome',
                        'required'      => 'required',
                    ],

                    'sigla' => [
                        'type'          => 'text',
                        'label'         => 'Sigla',
                        'placeholder'   => 'Sigla',
                        'required'      => 'required',
                    ],
                ],   
                [
                    'ano' => [
                        'type'          => 'text',
                        'label'         => 'Ano',
                        'placeholder'   => 'Ano',
                        'required'      => 'required',
                    ],
                ],
                [
                    'edicao' => [
                        'type'          => 'number',
                        'label'         => 'Edição',
                        'placeholder'   => 'Edição',
                        'required'      => 'required',
                    ],
                ],
                [
                    'data_inicio' => [
                        'type'          => 'date',
                        'label'         => 'Início',
                        'placeholder'   => 'Início',
                        'required'      => 'required',
                    ],

                    'data_fim' => [
                        'type'          =>  'date',
                        'label'         => 'Término',
                        'placeholder'   => 'Término',
                        'required'      => 'required',
                    ],


                ],
                [
                    'data_inicio_insc' => [
                        'type'          => 'date',
                        'label'         => 'Início Inscrição',
                        'placeholder'   => 'Início Inscrição',
                        'required'      => 'required',
                    ],

                    'data_fim_insc' => [
                        'type'          =>  'date',
                        'label'         => 'Término Inscrição',
                        'placeholder'   => 'Término Inscrição',
                        'required'      => 'required',
                    ],


                ],                
                [
                    'id' => [
                        'type'          => 'hidden',
                    ],                         
                    'created_at' => [
                        'type' => 'hidden',
                    ],   
                    'updated_at' => [
                        'type' => 'hidden',
                    ],                    
                ],
            ]

        ];
    }

    public static function fieldsFormEdit(){
        return  self::fieldsFormCreate();
    }

    public function __toString()
    {
        return $this->nome;
    }

}
