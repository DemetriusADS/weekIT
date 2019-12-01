@extends('layouts.app')


@if(Auth::user()->tipo == 'coordenador')
@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
      .table-overflow {
    max-height:700px;
    overflow-y:auto;
}
</style>
<div class="m-content">
      @php
      $maxEventoID = DB::table('evento')->max('id');
      date_default_timezone_set('America/Sao_Paulo');
      $date = date('Y-m-d');
      $data_insc_inicio = DB::table('evento')
      ->select('evento.data_inicio_insc as inicio')
      ->where('evento.id','=',$maxEventoID)
      ->get();
      $data_insc_fim = DB::table('evento')
      ->select('evento.data_fim_insc as fim')
      ->where('evento.id','=',$maxEventoID)
      ->get();
      foreach ($data_insc_inicio as $key => $value) {
      $dataInicio=$value->inicio;
      }
      foreach ($data_insc_fim as $key => $value) {
      $dataFim = $value->fim;
      }

      if ($date >= $dataInicio && $date <= $dataFim) { $verify=DB::table('evento') ->
            join('inscricao_eventos','inscricao_eventos.evento_id','=','evento.id')
            ->join('participante','participante.edicao_ativa','=','evento.id')
            ->select('inscricao_eventos.participante_id as id')
            ->where([
            ['inscricao_eventos.participante_id','=',Auth::user()->id],
            ['inscricao_eventos.evento_id','=',$maxEventoID],
            ])
            ->get();
            // dd($verify->isEmpty());


            if($verify->isEmpty()){
            echo(' <form action="'.route("eventoUpdate", DB::table("evento")->max("id")).'" method="post">
                  <div class="alert alert-dismissible" style="background-color: #fbc8c8;">
                        <p>Você ainda não se Inscreveu na nova ediçao de '. DB::table("evento")->max("ano").'</p>
                        <button type="submit" class="btn btn-danger">Edição '.
                              DB::table("evento")->max("ano").'</button>
                  </div>
            </form>');
            }
            }
            @endphp
</div>
<div class="m-content text-center">
      <hr>
      <div class="row">
            <div class="col-md-6 col-lg-3">
                  <div class="widget-small primary coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                        <div class="info">
                              <h4>Atividades</h4>
                              <p><b>
                                          {{
                            DB::table('atividade')
                                ->where('evento_id','=',DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa)
                                ->count() 
                      }}
                                    </b></p>
                        </div>
                  </div>
            </div>
            <div class="col-md-6 col-lg-3">
                  <div class="widget-small info coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                        <div class="info">
                              <h4>Participantes</h4>
                              <p><b>{{ DB::table('inscricao_eventos')
                  ->where('inscricao_eventos.evento_id','=',Auth::user()->edicao_ativa)
                  ->count() }}</b></p>
                        </div>
                  </div>
            </div>
            <div class="col-md-6 col-lg-3">
                  <div class="widget-small warning coloured-icon"><i class="icon fa fa-edit fa-3x"></i>
                        <div class="info text-center">
                              <span class="text-left" style="font-size: 16px; ">Inscrições totais: </span>
                              <b>
                                          {{
                            DB::table('inscricao')
                                ->leftjoin('atividade', 'atividade.id','=','inscricao.atividade_id')
                                ->leftjoin('evento', 'evento.id','=','atividade.evento_id')
                                ->where('evento_id','=',DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa)
                                ->count() 
                      }}
                                    </b><br>
                                    <span class="text-left" style="font-size: 14px">Inscrições minicursos: </span>
                                   <b> {{ 
                                    DB::table('inscricao')
                                ->join('atividade', 'atividade.id','=','inscricao.atividade_id')
                                ->select('inscricao.id')
                                ->where([
                                      ['atividade.evento_id','=',Auth::user()->edicao_ativa],
                                      ['atividade.tipo','=','minicurso']
                                ])
                                ->count() 
                                     }}</b>
                        </div>
                  </div>
            </div>
            <div class="col-md-6 col-lg-3">
                  <div class="widget-small success coloured-icon"><i class="icon fa fa-money fa-3x"></i>
                        <div class="info">
                              <span style="font-size: 16px; margin-left: -25px !important;">Inscrições pagas:</span>
                              <b>
                                    {{
                            DB::table('inscricao')
                                ->leftjoin('atividade', 'atividade.id','=','inscricao.atividade_id')
                                ->leftjoin('evento', 'evento.id','=','atividade.evento_id')
                                ->where([['atividade.evento_id','=',DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa],['inscricao.status','=','pago'],])
                                ->count() 
                      }}
                              </b>
                              <br>
                              <span style="font-size: 16px; margin-left: -20px !important;">Inscrições isentas:</span>
                              <b>
                                    {{
                            DB::table('inscricao')
                                ->leftjoin('atividade', 'atividade.id','=','inscricao.atividade_id')
                                ->leftjoin('evento', 'evento.id','=','atividade.evento_id')
                                ->where([['atividade.evento_id','=',DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa],['inscricao.status','=','isento'],])
                                ->count() 
                      }}
                              </b>
                        </div>
                  </div>
            </div>
      </div>

</div>
<div class="m-content">
      <div class="m-portlet m-portlet--mobile">
            <div class="container-fluid">
                  <h2 id="titulo">{{$titulo}}</h2>
                  <div class="float-md-left">
                              <div class="ml-md-1 mb-sm-2 mt-4 ">
                                          <h5>Remover Inscrições Não Pagas</h5>
                             <!-- Button trigger modal -->
                             <form action="{{ route('removerNp') }}" method="post" id="formRemove" class="form form-inline">
                              {{ csrf_field() }}
                              <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                                <label class="input-group-text" for="dataSelected">A anteriores a:  </label>
                                          </div>
                              
                       <input class="form form-control" type="date" id='dataSelected' name="dataSelect" required>
                       <div class="input-group-append" id="button-addon4">
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#exampleModalCenter" onclick="getDate()">
                                    Remover
                              </button>
                              </div>
                        </div>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="warning" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="warning">AVISO!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div id="load">
                      <div class="modal-body">
                        Deseja realmente remover todas as inscrições em andamento, feitas até do dia <span class='font-weight-bold text-danger' id='getDataSelected'></span>?
                      </div>
                      <div class="modal-footer">
                           
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" onclick="loading()"class="btn  btn-danger">Confirmar</button>
                            </div>
                      </div>
                    </div>
                  </div>
                </div>
            </form>
            @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <h4 class="alert-heading">Inscrições removidas com sucesso!</h4>
                   Foram removidas <strong>{{Session::get('success')}}</strong> inscrições.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @elseif(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  Não foram achados registros anteriores a <strong>{{ Session::get('error') }}</strong>.
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>
                 </div>
@endif
                              </div>
                              <div class="ml-md-5 mb-sm-2 mt-4 float-md-right">
                                    <h5>Filtrar Status</h5>
                              <div class="btn-group m-btn-group" id="filtrarStatus" role="group" aria-label="...">
                                    <button type="button" id="pago" class="btn  btn-outline-success">Pago</button>
                                    <button type="button" id="gratuito" class="btn  btn-outline-info">Gratuito</button>
                                    <button type="button" id="andamento" class="btn  btn-outline-warning">Em andamento</button>
                                    <button type="button" id="isento" class="btn  btn-outline-info">Isento</button>
                                    <button type="button" class="btn  btn-outline-dark">Todos</button>
                              </div>
                              </div>
                        <div class=" float-md-left mt-4">
                              <h5>Buscar</h5>
                  <form class="form-inline" method="none" onkeypress="return event.keyCode != 13;">
                        <div class="form-group mx-sm-3">
                              <input class="form-control search-field" type="text" id="cpf" placeholder="CPF">
                        </div>
                        <div class="form-group mx-sm-3">
                              <input class="form-control search-field" type="text" id="nome" placeholder="Nome">
                        </div>
                        <button id="bt_pesquisar" class="btn btn-info" onclick="pesquisa()"
                              type="button">Buscar</button>
                  </form>
            </div>
            <br>
    
</div>
<div class="clearfix"></div>
                  <br>
                  <div id="aviso"></div>
                  <div class="table-overflow">
                  <div class="table-responsive">
                 
                        <table class="table table-striped" id='content-gerenciar-incricoes'>
                              <tr>
                                    <th style="width: 130px;">CPF</th>
                                    <th style="width: 230px;">Participante</th>
                                    <th style="width: 280px;">Atividade</th>
                                    <th style="width: 120px;">Data</th>
                                    <th style="width: 140px;">Status</th>
                                    <th style="width: 200px;">Alterar para</th>
                                    <th></th>
                              </tr>
                              @foreach($data as $inscricao)
                              <tr>
                                    <td>{{$inscricao->cpf}}</td>
                                    <td>{{$inscricao->nome}}</td>
                                    <td>{{$inscricao->identificador .' - '. $inscricao->titulo}}</td>
                                    <td>{{$inscricao->data}}</td>
                                    @if($inscricao->status == 'cancelado')
                                    <td data-estado="{{$inscricao->status}}" id="status-{{$inscricao->id}}"><span
                                                class="m-badge m-badge--danger m-badge--wide"
                                                id="status-{{$inscricao->status}}">cancelado</span></td>
                                    @endif

                                    @if($inscricao->status == 'andamento')
                                    <td data-estado="{{$inscricao->status}}" id="status-{{$inscricao->id}}"><span
                                                class="m-badge m-badge--warning m-badge--wide"
                                                id="status-{{$inscricao->status}}">em andamento</span></td>
                                    @endif

                                    @if($inscricao->status == 'pago')
                                    <td data-estado="{{$inscricao->status}}" id="status-{{$inscricao->id}}"><span
                                                class="m-badge m-badge--success m-badge--wide"
                                                id="status-{{$inscricao->status}}">pago</span></td>
                                    @endif

                                    @if($inscricao->status == 'isento')
                                    <td data-estado="{{$inscricao->status}}" id="status-{{$inscricao->id}}"><span class="m-badge m-badge--info m-badge--wide"
                                                id="status-{{$inscricao->id}}">isento</span></td>
                                    @endif
                                    @if($inscricao->status == 'gratuito')
                                    <td data-estado="{{$inscricao->status}}" id="status-{{$inscricao->id}}"><span class="m-badge m-badge--info m-badge--wide"
                                                id="status-{{$inscricao->id}}">gratuito</span></td>
                                    @endif
                                    @if($inscricao->status != 'gratuito')
                                    <td>
                                          <div class="btn-group m-btn-group" id="alterar-status" role="group"
                                                aria-label="...">
                                                <button type="button" class="btn btn-sm  btn-success"
                                                      onclick="alterarStatus({{$inscricao->id}}, 'pago')">Pago</button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                      onclick="alterarStatus({{$inscricao->id}}, 'cancelado')">Cancelado</button>
                                                <button type="button" class="btn btn-sm btn-warning"
                                                      onclick="alterarStatus({{$inscricao->id}}, 'andamento')">Em
                                                      andamento</button>
                                                <button type="button" class="btn btn-sm btn-info"
                                                      onclick="alterarStatus({{$inscricao->id}}, 'isento')">Isento</button>
                                          </div>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if($inscricao->status != 'pago')
                                    <td>
                                         <a href='inscricao/delete/{{ $inscricao->id }}'>
                                           <button class="btn btn-sm btn-danger link" type="button">-</button>
                                          </a>
                                    </td>
                                    @endif
                              </tr>
                              @endforeach
                        </table>
                  </div>
                  </div>
                  <div id="links">{!! $data->links() !!}</div>
            </div>
      </div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" type="text/javascript">
</script>
<script type="text/javascript">
function loading(){
      html='<div class="d-flex justify-content-center"><div class="spinner-grow text-danger" role="status">'+
            '<span class="sr-only">Loading...</span>'+
      '</div>'
      html2 = 'Excluindo'+'<div class="spinner-grow text-danger" role="status">'+
            '<span class="sr-only">Loading...</span>'+
      '</div>'
      document.getElementById('warning').innerHTML = html2;
      document.getElementById("load").innerHTML = "<div class='modal-body'>Aguarde...</div>";
      document.getElementById("formRemove").submit();
}
function getDate(){
      var getDate = document.querySelector("#dataSelected").value;
      document.getElementById("getDataSelected").innerHTML = getDate;
}

  var tds = document.querySelectorAll('table td[data-estado]');
      document.querySelector('#filtrarStatus').addEventListener('click', function(e) {
      var estado = e.target.id;
      for (var i = 0; i < tds.length; i++) {
            var tr = tds[i].closest('tr');
            tr.style.display = estado == tds[i].dataset.estado || !estado ? '' : 'none';
            }
      });
      $('#cpf').mask('999.999.999-99');

      function prepareTableLines(item) {
      var html =
            '<tr>' +
            '<td>' + item.cpf + '</td>' +
            '<td>' + item.nome + '</td>' +
            '<td>' + item.identificador + ' - ' + item.titulo + '</td>' +
            '<td>' + item.data + '</td>';

      if (item.status == 'cancelado')
            html += '<td data-estado="'+item.status+'" id="status-' + item.id + '"><span class="m-badge m-badge--danger m-badge--wide" id="status-' +
            item.id + '" >cancelado</span></td>';

      if (item.status == 'andamento')
            html += '<td data-estado="'+item.status+'" id="status-' + item.id + '"><span class="m-badge m-badge--warning m-badge--wide" id="status-' +
            item.id + '" >em andamento</span></td>';

      if (item.status == 'pago')
            html += '<td data-estado="'+item.status+'" id="status-' + item.id + '"><span class="m-badge m-badge--success m-badge--wide" id="status-' +
            item.id + '" >pago</span></td>';

      if (item.status == 'isento')
            html += '<td data-estado="'+item.status+'" id="status-' + item.id + '"><span class="m-badge m-badge--info m-badge--wide" id="status-' +
            item.id + '" >isento</span></td>';
      if (item.status == 'gratuito')
            html += '<td data-estado="'+item.status+'" id="status-' + item.id + '"><span class="m-badge m-badge--info m-badge--wide" id="status-' +
            item.id + '" >gratuito</span></td>';

      if (item.status != 'gratuito') {

            html += '<td>' +
                  '<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">' +
                  '<button type="button" class="btn btn-sm  btn-success" onclick="alterarStatus(' + item.id +
                  ', \'pago\')">Pago</button>' +
                  '<button type="button" class="btn btn-sm btn-danger" onclick="alterarStatus(' + item.id +
                  ', \'cancelado\')">Cancelado</button>' +
                  '<button type="button" class="btn btn-sm btn-warning" onclick="alterarStatus(' + item.id +
                  ', \'andamento\')">Em andamento</button>' +
                  '<button type="button" class="btn btn-sm btn-info" onclick="alterarStatus(' + item.id +
                  ', \'isento\')">Isento</button>' +
                  '</div>' +
                  '</td>'
      }
      if (item.status != 'pago') {
            html += '<td>' +
                  '<a href="inscricao/delete/' + item.id +'">'+
                  ' <button style="background: #f4516c" class="btn btn-sm btn-danger" type="button">-</button>'
                  +'</a>' +
                  '</td>'
      }
      html += '</tr>';
      return html;
      }


      $("#nome").keydown(function(e) {
            if (e.wich == 13 || e.keyCode == 13) {
                  pesquisa();
            }
      });

      function pesquisa() {
      if (($("#nome").val() != "") && ($("#cpf").val() != "")) {
            var html =
                  '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-warning alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-warning"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Pesquise uma opção por vez!</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
            $('#aviso').html(html);
      } else if ($("#nome").val() != "") {
            pesquisaNome();
      } else if ($("#cpf").val() != "") {
            pesquisaCPF();
      } else if (($("#nome").val() == "") && ($("#cpf").val() == "")) {
            pesquisaNome();
      }
      }

      function pesquisaNome() {
      $("#bt_pesquisar").prop("disabled", true);
      $.ajax({
            type: "POST",
            url: "inscricao/pesquisar-inscricoes-nome",
            data: "nome=" + $("#nome").val(),
            success: function(data) {
                  var numLinhas = 0;
                  $('#titulo').val(data.titulo);
                  var html =
                  '<table class="table table-striped">' +
                        '<tr>' +
                        '<th style="width: 130px;">CPF</th>' +
                        '<th style="width: 230px;">Participante</th>' +
                        '<th style="width: 280px;">Atividade</th>' +
                        '<th style="width: 120px;">Data</th>' +
                        '<th style="width: 140px;">Status</th>' +
                        '<th style="width: 200px;">Alterar para</th>' +
                        '<th></th>'+
                        '</tr>';
                  $.each(data.data.data, function(i, item) {
                        html += prepareTableLines(data.data.data[i]);
                        numLinhas++;
                  });
                   html += '</table>';
                  $('#content-gerenciar-incricoes').html(html);
                  $("#bt_pesquisar").prop("disabled", false);
                  if (numLinhas < 15) {
                        $("#links").hide();
                  } else {
                        $("#links").show(500);
                  }
            }
      });
      }

      function pesquisaCPF() {
      $("#bt_pesquisar").prop("disabled", true);
      $.ajax({
            type: "POST",
            url: "inscricao/pesquisar-inscricoes-cpf",
            data: "cpf=" + $("#cpf").val(),
            success: function(data) {
                  var numLinhas = 0;
                  $('#titulo').val(data.titulo);
                  var html =
                        '<tr>' +
                        '<th style="width: 130px;">CPF</th>' +
                        '<th style="width: 230px;">Participante</th>' +
                        '<th style="width: 280px;">Atividade</th>' +
                        '<th style="width: 120px;">Data</th>' +
                        '<th style="width: 140px;">Status</th>' +
                        '<th style="width: 200px;">Alterar para</th>' +
                        '<th></th>'+
                        '</tr>';
                  $.each(data.data.data, function(i, item) {
                        html += prepareTableLines(data.data.data[i]);
                        numLinhas++;
                  });
                  $('#content-gerenciar-incricoes').html(html);
                  $("#bt_pesquisar").prop("disabled", false);
                  if (numLinhas < 15) {
                        $("#links").hide();
                  } else {
                        $("#links").show(500);
                  }
            }
      });
      }

      function listarInscricoesGerenciar() {
      $.ajax({
            url: 'inscricao/listar-inscricoes-gerenciar',
            type: 'GET',
            data: null,
            success: function(data) {
                  $('#titulo').val(data.titulo);
                  var html =
                        '<tr>' +
                        '<th style="width: 130px;">CPF</th>' +
                        '<th style="width: 230px;">Participante</th>' +
                        '<th style="width: 280px;">Atividade</th>' +
                        '<th style="width: 120px;">Data</th>' +
                        '<th style="width: 140px;">Status</th>' +
                        '<th style="width: 200px;">Alterar para</th>' +
                        '</tr>';
                  $.each(data.data.data, function(i, item) {
                        html += prepareTableLines(data.data.data[i]);
                  });
                  $('#content-gerenciar-incricoes').html(html);
            }
      });
      }

      function alterarStatus(id, status) {
      $.ajax({
            url: 'inscricao/alterar-status',
            type: 'GET',
            data: "id=" + id + "&status=" + status,
            success: function(data) {
                  $('#status-' + data.data.id).text(data.data.status);
                  var cor, status = "";
                  status_pos = data.data.status;
                  if (status_pos == 'pago') {
                        cor = 'success';
                  } else if (status_pos == 'andamento') {
                        cor = 'warning';
                        status_pos = 'em andamento';
                  } else if (status_pos == 'cancelado') {
                        cor = 'danger';
                  } else {
                        cor = 'info';
                  }
                  var html = '<span class="m-badge m-badge--' + cor + ' m-badge--wide" id="status-' +
                        data.data.id + '" >' + status_pos + '</span>';
                  $('#status-' + data.data.id).html(html);
            }
      });
      }
</script>
@endsection
<!-- Sessão de aluno conectado -->
@elseif(Auth::user()->tipo == 'aluno')
@section('content')
<div class="m-content">
      @php
          $maxid = DB::table('evento')->max('id');
          $verify=DB::table('evento') ->
            join('inscricao_eventos','inscricao_eventos.evento_id','=','evento.id')
            ->join('participante','participante.edicao_ativa','=','evento.id')
            ->select('inscricao_eventos.participante_id as id')
            ->where([
            ['inscricao_eventos.participante_id','=',Auth::user()->id],
            ['inscricao_eventos.evento_id','=',$maxid],
            ])
            ->get();
      @endphp
      @if(!($verify->isEmpty()))
       <div class="bs-component">
                    <div class="alert alert-dismissible" style="background-color: #ebfaeb;">
                       
                        <h4 class="block sbold" style="padding-bottom: 10px;">As inscrições na Week-IT 2019 estão abertas. <!--de <b>14 a 27/11/2019</b>.--></h4>
                        
                        <p style="text-align: justify;">Você tem acesso gratuito às palestras, mesa redonda e mostra de trabalhos. <strong> Não se esqueca de se inscrever.</strong></p>
                        <p style="text-align: justify;">A inscrição em cada minicurso é <strong>R$ 10,00</strong> (dez reais).</p>
                       <p style="text-align: justify;">O pagamento das inscrições em minicurso deverá ser feito no IFBA (<strong>ao lado da cantina</strong>), a partir de <strong>Segunda-feira (18/11/2019).</strong></p>
                       <h4> Atenção </h4>
                      
                        <ul>
                        <li><strong>Manhã</strong>: entrar em contato com Ricardo: 77981343096</li>
                        <li><strong>À tarde</strong>: 15:30 às 17hrs</li>
                        <li><strong>À noite</strong>: 19h às 20:30</li>
                        </ul>
                        <p style="padding: 5px 10px;background: #fbc8c8;margin-top: 12px;"><strong>As inscrições não pagas até a data limite serão <strong>canceladas</strong>  e as vagas correspondentes serão liberadas para novas inscrições:</strong></p>
                        <ul>
                        <li><del>Inscrições de <strong>14 a 19/11</strong>, o pagamento deverá ser efetuado até <strong>20/11 (Quarta-feira)</strong>.</del></li>
                        <li>Inscrições de <strong> 20 a 22/11</strong>, o pagamento deverá ser efetuado até <strong>23/11 (Sábado)</strong>.</li>
                        <li>Inscrições após<strong> 22/11</strong> devem ser pagas no credenciamento do evento <strong>(25/11)</strong>.</li>
                        </ul>
                        <p style="padding: 5px 10px;background: #fbc8c8;margin-top: 12px;"> <b>Aviso</b> - Por favor, certifique-se que seu nome completo pois este será impresso em seu(s) certificado(s). Procure um coordenador.
                        </p>                                        
                    </div>
                </div>
     
      <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">
                  <div id="aviso2"></div>
                  <div id="atividades-abertas"></div>

            </div>
      </div>
      @else
      @php
          $dataInicio = DB::table('evento')->select('data_inicio_insc')->where('id', '=', $maxid)->get()[0]->data_inicio_insc;
          $dataFim = DB::table('evento')->select('data_fim_insc')->where('id', '=', $maxid)->get()[0]->data_fim_insc;
      @endphp
      @if($dataInicio > date('Y-m-d'))
      @php
          echo date('Y-m-d');
      @endphp
      <h1>As Inscrições para a Week IT 2019 estarão abertas a partir do dia 14/11. Aguarde!</h1>
      <br>
      @elseif($dataFim < date('Y-m-d'))
      <h1>As Inscrições para o evento já se encerraram.</h1>
      @else
      <form action="{{route('eventoUpdate', DB::table('evento')->max('id'))}}" method="post">
                        <div class="alert alert-dismissible bg-success">                        
                        <p style="color: white">Se inscreva aqui na Ediçao @php echo DB::table('evento')->max('ano')@endphp</p>
                        <button type="submit"  class="btn btn-danger">Inscrever</button>
                       </div>
                    </form>
                    <div class="alert alert-dismissible bg-warning">
                  
                              <p style="text-align: justify;">A inscrição para o evento é gratuita e dá acesso às palestras, mesa redonda e
                                    mostra de trabalhos.</p>
                        </div>
                    @endif
      @endif
      
      
</div>
@endsection
@section('scripts')
<script type="text/javascript">
function inscricoesAbertas() {
      var d = new Date();
      dataHora = (d.toLocaleString());
      $.ajax({
            url: 'inscricao/atividades-inscricao',
            type: 'GET',
            data: null,
            success: function(data) {
                  var html =
                        '<h2>' + data.titulo + '</h2>' +
                        '<br>' +
                        '<div class="table-responsive-md"><table class="table table-striped">' +
                        '<tr>'

                        +
                        '<th>Titulo</th>' +
                        '<th>Data</th>' +
                        '<th>Descrição</th>' +
                        '<th>Max</th>' +
                        '<th>Inscritos</th>' +
                        '<th>Preço</th>' +
                        '<th>CH</th>' +
                        '<th>Ação</th>' +
                        '</tr>';
                  $.each(data.atividades, function(x, item) {
                        if (!data.atividades[x].ja_inscrito && data.atividades[x]
                              .liberar_inscricao) {
                              html += '<tr>' +
                                    '<td>' + data.atividades[x].identificador + ' - ' +
                                    data.atividades[x].titulo + '</td>' +
                                    '<td>' + data.atividades[x].data_inicio + ' de ' +
                                    data.atividades[x].hora_inicio + ' até ' + data
                                    .atividades[x].hora_fim + '</td>' +
                                    '<td>' + data.atividades[x].descricao + '</td>' +
                                    '<td>' + data.atividades[x].maximo_participantes +
                                    '</td>' +
                                    '<td>' + data.atividades[x].inscritos + '</td>' +
                                    '<td>R$ ' + data.atividades[x].preco + '</td>' +
                                    '<td>' + data.atividades[x].carga_horaria + 'hrs' +
                                    '</td>' +
                                    '<td id="acao2-' + data.atividades[x].id + '">' +
                                    '<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="..."><button class="btn btn-info" onclick="inscrverAtividade(' +
                                    data.atividades[x].id + ')">Inscrever</button></td>'
                        } else if (!data.atividades[x].ja_inscrito && !data.atividades[x]
                              .liberar_inscricao) {
                              html += '<tr>';
                              if(data.atividades[x].maximo_participantes == data.atividades[x].inscritos){
                                   html+= '<td><del>' + data.atividades[x].identificador + ' - ' +
                                    data.atividades[x].titulo + '</del><span class="text-danger font-weight-bold"> Esgotado! </span></td>' ;
                              }else{
                                    html+= '<td>' + data.atividades[x].identificador + ' - ' +
                                    data.atividades[x].titulo + '</td>' ;
                              }
                                    html+='<td>' + data.atividades[x].data_inicio + ' de ' +
                                    data.atividades[x].hora_inicio + ' até ' + data
                                    .atividades[x].hora_fim + '</td>' +
                                    '<td>' + data.atividades[x].descricao + '</td>' +
                                    '<td>' + data.atividades[x].maximo_participantes +
                                    '</td>' +
                                    '<td>' + data.atividades[x].inscritos + '</td>' +
                                    '<td>R$ ' + data.atividades[x].preco + '</td>' +
                                    '<td>' + data.atividades[x].carga_horaria + 'hrs' +
                                    '</td>' +
                                    '<td id="acao"><button class="btn btn-metal disabled">Inscrever</button></td>'
                        }
                        html += '</tr>';
                  });

                  html += '</table></div>';
                  $('#atividades-abertas').html(html);
            }
      })
}
inscricoesAbertas();

function inscrverAtividade(id) {
      $.ajax({
            url: 'inscricao/realizar-inscricao',
            type: 'GET',
            data: "atividade_id=" + id,
            success: function(data) {
                  if (data.resposta == 1) {
                        var html =
                              '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-success"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Inscrito com sucesso!</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                        $('#aviso2').html(html);
                        $('#acao2-' + id).html(
                              '<span style="color: green; font-weight: bold;">Inscrito</span>');
                  } else {
                        var html =
                              '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-danger"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Você não pode se inscrever nessa atividade pois já tem uma inscrição em outra atividade no mesmo horário.</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                        $('#aviso2').html(html);
                        $('#acao2-' + id).html(
                              '<span style="color: red; font-weight: bold;">Choque de horarios</span>'
                        );
                  }
                  inscricoesAbertas();
            }
      });
}
window.adicionarInscricao = adicionarInscricao;
</script>
@endsection

<!-- Sessão de aluno financeiro -->
@elseif(Auth::user()->tipo == 'financeiro')
@section('content')
@include('layouts.gerenciar-inscricoes')
@endsection

<!-- Sessão de aluno monitor -->
@elseif(Auth::user()->tipo == 'monitor')
@section('content')


      <div>
@include('layouts.gerenciar-presenca')
      </div>
@endsection
@endif