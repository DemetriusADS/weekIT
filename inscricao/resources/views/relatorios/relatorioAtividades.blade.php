@extends('layouts.app')
@section('content')
<style>
            .table-overflow {
          max-height:700px;
          overflow-y:auto;
      }
      </style>
    <div>
      <!-- estrutura para botões etc...-->
      <div class="accordion" id="accordionExample">
                  <table class="table table-bordered">
                              <tr>
                                    <th class="w-25">Identificador</th>
                                    <th class="w-50">Titulo</th>
                                    <th>Total Inscritos</th>
                                    <th>Inscrições Pagas</th>
                                    <th>Inscrições Isentas</th>
                              </tr>
                        </table>
      @foreach ($getData as $key => $value)
      @php
           $count = 0;
           $countP = 0;
           $countI = 0;
      @endphp
            <div class="card" >
              <div class="card-header" style="background: white !important" id="heading{{ $value->atividadeID }}">
                <h2 class="mb-0">
                  <button class="btn btn-outline-success w-100" type="button" data-toggle="collapse" data-target="#collapse{{ $value->atividadeID }}" aria-expanded="true" aria-controls="collapseOne">
                    <table class="table table-borderless">                         
                              
                               <tr>
                                   <td class="w-auto">{{ $value->identificador }}</td>
                                   <td class="w-50">{{ $value->titulo }}</td>
                                   @php
                                    $count = DB::table('inscricao')
                                    ->select('inscricao.id')
                                    ->where('inscricao.atividade_id','=',$value->atividadeID)
                                    ->count();
                                    $countP = DB::table('inscricao')
                                    ->select('inscricao.id')
                                    ->where([
                                          ['inscricao.atividade_id','=',$value->atividadeID],
                                          ['inscricao.status','=','pago']
                                          ])
                                    ->count();
                                    $countI =  DB::table('inscricao')
                                    ->select('inscricao.id')
                                    ->where([
                                          ['inscricao.atividade_id','=',$value->atividadeID],
                                          ['inscricao.status','=','isento']
                                          ])
                                    ->count();
                                   @endphp
                                   <td>{{ $count }}</td>
                                   <td>{{ $countP }}</td>
                                   <td>{{ $countI }}</td>
                              </tr>
                              
                         
                    </table>
                  </button>
                </h2>
              </div>
          
              <div id="collapse{{ $value->atividadeID }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                        <table class="table table-bordered table-overflow">
                              @php
                                  $participante = null;
                                  $participante = DB::table('inscricao')
                                  ->join('participante', 'participante.id', '=', 'inscricao.participante_id')
                                    ->select(
                                          'participante.id as id',
                                          'participante.nome as nome',
                                          'participante.cpf as cpf',
                                          'inscricao.presente',
                                          'inscricao.status',
                                          'inscricao.atividade_id as atividadeID'
                                      )
                                      ->where('inscricao.atividade_id','=',$value->atividadeID)
                                      ->get();
                              @endphp
                                    <tr>
                                          <th>CPF</th>
                                          <th>Participante</th>
                                          <th>Status</th>
                                          <th>Presença</th>
                                    </tr>
                                    
                      @foreach ($participante as $item)
                                    <tr>
                                          <td>{{ $item->cpf }}</td>
                                          <td>{{ $item->nome }}</td>
                                          <td>{{ $item->status }}
                                                @if ($item->status == 'andamento')
                                                    <td></td>
                                                @else
                                                    @if ($item->status == 1)
                                                        <td>Presente</td>
                                                    @else
                                                        <td>Ausente</td>
                                                    @endif
                                                @endif
                                    </tr>
                      @endforeach
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
    </div>
   
@endsection