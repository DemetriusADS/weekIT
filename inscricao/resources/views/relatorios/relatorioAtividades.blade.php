@extends('layouts.app')
@section('content')
<style>
            .table-overflow {
          max-height:700px;
          overflow-y:auto;
      }
      </style>
       <h5>Ordenar por</h5>
      <div class="btn-group m-btn-group mb-3" id="filtrarRelatorio" role="group" aria-label="...">
          <a href="{{ route("setOrdem.atividade","identificador") }}">  <button type="button" id="pago" class="btn btn-sm  btn-success">Identificador</button></a>
          <a href="{{ route("setOrdem.atividade","titulo") }}">  <button type="button" id="gratuito" class="btn btn-sm  btn-success">Titulo</button></a>
          <a href="{{ route("setOrdem.atividade","participantes") }}">  <button type="button" id="andamento" class="btn btn-sm  btn-success">Inscrições</button></a>
          <a href="{{ route("setAtividadeTipo","minicurso") }}">  <button type="button" id="isento" class="btn btn-sm  btn-success">Minicursos</button></a>
          <a href="{{ route("setOrdem.atividade") }}">  <button type="button" class="btn btn-sm  btn-metal">Limpar</button></a>
       </div>
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
                  @if($value->participantes != $value->max_participantes)
                    <button class="btn btn-outline-success w-100" type="button" data-toggle="collapse" data-target="#collapse{{ $value->atividadeID }}" aria-expanded="true" aria-controls="collapseOne">
                  @else
                    <button class="btn btn-outline-danger w-100" type="button" data-toggle="collapse" data-target="#collapse{{ $value->atividadeID }}" aria-expanded="true" aria-controls="collapseOne">
                  @endif
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
                                   <td id="count">{{ $count }}</td>
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
@section('scripts')
    <script type="text/javascript">
            var table = document.getElementById("count");
            console.log(table);
    </script>
@endsection