<?php

namespace App;

use App\Http\DefaultModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class Participante extends AbstractModel implements DefaultModel
{

    protected     $table            = 'participante';
    public static $base_name_route  = 'participante';
    public static $verbose_name     = 'participante';
    public static $verbose_plural   = 'Participantes';
    public static $verbose_genre    = 'M';
    public static $controller       = 'ParticipanteController';


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function verbose_name()
    {
        $verbose_name = 'participante';
        return response()->json($verbose_name);
    }
    public static function search(Request $request)
    {

        $request_query      = $request->input('query');
        $page               = $request->input('pagination.page', 1);
        $perpage            = $request->input('pagination.perpage', 10000);
        $columns            = $request->input('columns',  ['*']);
        $sort               = $request->input('sort',  NULL);
        $excluded           = $request->input('excluded',  NULL);

        $query =  DB::table('participante')
            ->select([
                'participante.id as id',
                'participante.nome as nome',
                'participante.nome_cracha as nome_cracha',
                'participante.cpf as cpf',
                'participante.instituicao as instituicao',
                'participante.sexo as sexo',
                'participante.tipo as tipo',
                'participante.telefone1 as telefone1',
                'participante.telefone2 as telefone2',
                'participante.campus as campus',
                'participante.curso as curso',
                'participante.email as email',
                DB::raw('DATE_FORMAT(participante.nascimento,"%d/%m/%Y %H:%i:%s") as nascimento'),
                DB::raw('DATE_FORMAT(participante.created_at,"%d/%m/%Y %H:%i:%s") as created_at'),
                DB::raw('DATE_FORMAT(participante.updated_at,"%d/%m/%Y %H:%i:%s") as updated_at'),
            ])->orderBy('nome');

        if (isset($request_query['cpf'])) {
            if (!empty($request_query['cpf'])) {
                $query->where('participante.cpf', 'like', '%' . $request_query['cpf'] . '%');
            }
        }

        if (isset($request_query['nome'])) {
            if (!empty($request_query['nome'])) {
                $query->where('participante.nome', 'like', '%' . $request_query['nome'] . '%');
            }
        }

        if (isset($sort)) {
            $query->orderBy($sort['field'], $sort['sort']);
        }


        $paginator  = $query->paginate($perpage, $columns, 'page', $page);

        return response()->json([
            'meta' => [
                'page'      => $paginator->currentPage(),
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
                'field' => 'cpf',
                'title' => 'CPF',
            ],
            [
                'field' => 'nome',
                'title' => 'Nome',
            ],
            [
                'field' => 'email',
                'title' => 'Email',
            ],

        ];

        return response()->json($columns);
    }

    public static function dataTablesSearchForm()
    {

        return  [
            'fields' => [
                'cpf' => [
                    'type'          => 'text',
                    'placeholder'   => 'CPF',
                    'class'   => 'input-cpf',
                ],
                'nome' => [
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
                    'nome' => [
                        'type'          => 'text',
                        'label'         => 'Nome Completo',
                        'placeholder'   => 'Nome para certificado',
                        'required'      => 'required',
                    ],
                    'nascimento' => [
                        'type'          => 'date',
                        'label'         => 'Nascimento',
                        'placeholder'   => 'Nascimento',
                        'required'      => 'required',
                    ],

                ],
                [
                    'cpf' => [
                        'type'          => 'text',
                        'label'         => 'CPF',
                        'placeholder'   => 'CPF',
                        'class'         => 'cpf_inputmask',
                        'id'            => 'cpf',
                        'required'      => 'required',
                    ],
                    'sexo' => [
                        'type'          =>  'select',
                        'options' => [
                            'masculino' => 'masculino',
                            'feminino' => 'feminino'
                        ],
                        'label'         => 'Sexo',
                        'placeholder'   => 'Tipo',
                        'required'      => 'required',
                    ],
                ],
                [
                    'telefone1' => [
                        'type'          => 'text',
                        'label'         => 'Telefone 1',
                        'placeholder'   => 'Telefone 1',
                        'required'      => 'required',
                    ],

                    'telefone2' => [
                        'type'          => 'text',
                        'label'         => 'Telefone 2',
                        'placeholder'   => 'Telefone 2',
                    ],
                ],

                [
                    'instituicao' => [
                        'type'          =>  'select',
                        'options' => [
                            'Sim' => 'Não',
                            'Não' => 'Não',

                        ],
                        'label'         => 'É aluno do IFBA?',
                        'placeholder'   => 'É aluno do IFBA?',
                        'required'      => 'required',
                    ],
                ],

                [
                    'curso' => [
                        'type'          => 'text',
                        'label'         => 'Curso',
                        'placeholder'   => 'Curso',
                    ],
                    'nome_cracha' => [
                        'type'          => 'text',
                        'label'         => 'Nome para crachá',
                        'placeholder'   => 'Nome e sobrenome apenas',
                        'required'      => 'required',
                    ],
                ],

                [
                    'email' => [
                        'type'          => 'text',
                        'label'         => 'Email',
                        'placeholder'   => 'Email',
                        'required'      => 'required',
                    ],

                    'password' => [
                        'type'          => 'password',
                        'label'         => 'Senha',
                        'placeholder'   => 'Senha',
                    ],
                ],

                [
                    'tipo' => [
                        'type'          =>  'select',
                        'options' => [
                            'aluno' => 'aluno',
                            'professor' => 'professor',
                            'coordenador' => 'coordenador',
                            'monitor' => 'monitor',
                            'financeiro' => 'financeiro'
                        ],
                        'label'         => 'Tipo',
                        'placeholder'   => 'Tipo',
                        'required'      => 'required',
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

    public static function fieldsFormCreatePublico()
    {

        return  [
            'fields' => [
                [
                    'nome' => [
                        'type'          => 'text',
                        'label'         => 'Nome Completo',
                        'placeholder'   => 'Nome para certificado',
                        'required'      => 'required',
                    ],
                    'nascimento' => [
                        'type'          => 'date',
                        'label'         => 'Nascimento',
                        'placeholder'   => 'Nascimento',
                        'required'      => 'required',
                    ],

                ],
                [
                    'cpf' => [
                        'type'          => 'text',
                        'label'         => 'CPF',
                        'placeholder'   => 'CPF',
                        'class'         => 'cpf_inputmask',
                        'id'            => 'cpf',
                        'required'      => 'required',
                    ],

                    'sexo' => [
                        'type'          => 'select',
                        'label'         => 'Sexo',
                        'placeholder'   => 'Sexo',
                        'options' => [
                            'masculino' => 'masculino',
                            'feminino' => 'feminino'
                        ],
                        'required'      => 'required',
                    ],
                ],
                [
                    'telefone1' => [
                        'type'          => 'number',
                        'label'         => 'Telefone 1',
                        'placeholder'   => 'Telefone 1',
                        'required'      => 'required',
                    ],

                    'telefone2' => [
                        'type'          => 'number',
                        'label'         => 'Telefone 2',
                        'placeholder'   => 'Telefone 2',
                    ],
                ],

                [
                    'instituicao' => [
                        'type'          =>  'select',
                        'options' => [
                            'Sim' => 'Sim',
                            'Não' => 'Não',

                        ],
                        'label'         => 'É aluno do IFBA?',
                        'required'      => 'required',
                    ],
                    'nome_cracha' => [
                        'type'          => 'text',
                        'label'         => 'Nome para crachá',
                        'placeholder'   => 'Nome e sobrenome apenas',
                        'required'      => 'required',
                    ],

                ],

                [
                    'curso' => [
                        'type'          => 'text',
                        'label'         => 'Curso',
                        'placeholder'   => 'Curso',
                    ],

                ],

                [
                    'email' => [
                        'type'          => 'text',
                        'label'         => 'Email',
                        'placeholder'   => 'Email',
                        'required'      => 'required',
                    ],

                    'password' => [
                        'type'          => 'password',
                        'label'         => 'Senha',
                        'placeholder'   => 'Senha',
                        'required'      => 'required',
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
        return $this->nome;
    }
}