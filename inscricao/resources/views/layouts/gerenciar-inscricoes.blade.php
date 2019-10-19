@extends('layouts.app')

@section('content')
        <div class="m-content">
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
                  <p><b>{{ DB::table('participante')->count() }}</b></p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="widget-small warning coloured-icon"><i class="icon fa fa-edit fa-3x"></i>
                <div class="info">
                  <h4>Inscrições</h4>
                    <p><b>
                      {{ 
                            DB::table('inscricao')
                                ->leftjoin('atividade', 'atividade.id','=','inscricao.atividade_id')
                                ->leftjoin('evento', 'evento.id','=','atividade.evento_id')
                                ->where('evento_id','=',DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa)
                                ->count() 
                      }}
                    </b></p>                    
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
                    </b><br>
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
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                <h2 id="titulo">{{$titulo}}</h2>
                <form class="form-inline" method="none" onkeypress="return event.keyCode != 13;">
                  <div class="form-group mx-sm-3">
                    <input class="form-control search-field" type="text" id="cpf" placeholder="CPF">
                  </div>                    
                  <div class="form-group mx-sm-3">
                    <input class="form-control search-field" type="text" id="nome" placeholder="Nome">
                  </div>
                    <button id="bt_pesquisar" class="btn btn-info" onclick="pesquisa()" type="button">Buscar</button>
                </form> 
                    <br>
                <div id="aviso"></div> 
                <div id="content-gerenciar-incricoes">
                <table class="table table-striped">
                    <tr>
                        <th style="width: 130px;">CPF</th>
                        <th style="width: 230px;">Participante</th>
                        <th style="width: 280px;">Atividade</th>
                        <th style="width: 120px;">Data</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 200px;">Alterar para</th>
                    </tr>
                    @foreach($data as $inscricao)
                    <tr>
                        <td>{{$inscricao->cpf}}</td>
                        <td>{{$inscricao->nome}}</td>
                        <td>{{$inscricao->identificador .' - '. $inscricao->titulo}}</td>
                        <td>{{$inscricao->data}}</td>

                            @if($inscricao->status == 'cancelado')
                               <td id="status-{{$inscricao->id}}"><span class="m-badge m-badge--danger m-badge--wide" id="status-{{$inscricao->id}}" >cancelado</span></td>
                            @endif

                            @if($inscricao->status == 'andamento')
                                <td id="status-{{$inscricao->id}}"><span class="m-badge m-badge--warning m-badge--wide" id="status-{{$inscricao->id}}" >em andamento</span></td>
                            @endif

                            @if($inscricao->status == 'pago')
                               <td id="status-{{$inscricao->id}}"><span class="m-badge m-badge--success m-badge--wide" id="status-{{$inscricao->id}}" >pago</span></td>
                            @endif

                            @if($inscricao->status == 'isento')
                               <td id="status-{{$inscricao->id}}"><span class="m-badge m-badge--info m-badge--wide" id="status-{{$inscricao->id}}" >isento</span></td>
                            @endif                


                        <td>
                            <div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="..."> 
                                <button type="button" class="btn btn-sm  btn-success" onclick="alterarStatus({{$inscricao->id}}, 'pago')">Pago</button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="alterarStatus({{$inscricao->id}}, 'cancelado')">Cancelado</button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="alterarStatus({{$inscricao->id}}, 'andamento')">Em andamento</button>
                                <button type="button" class="btn btn-sm btn-info" onclick="alterarStatus({{$inscricao->id}}, 'isento')">Isento</button>
                            </div>                             
                        </td>
                    </tr>
                    @endforeach
                </table>                    
                </div>
                <div id="links">{!! $data->links() !!}</div>                
            </div>
       </div>             
    </div>    
@endsection
    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" type="text/javascript"></script>
        <script type="text/javascript">       
            $('#cpf').mask('999.999.999-99');          
            function prepareTableLines(item){
                var html = 
                    '<tr>'
                        +'<td>'+item.cpf+'</td>'
                        +'<td>'+item.nome+'</td>'
                        +'<td>'+item.identificador +' - '+ item.titulo+'</td>'
                        +'<td>'+item.data+'</td>';

                            if(item.status == 'cancelado')
                                html += '<td id="status-'+item.id+'"><span class="m-badge m-badge--danger m-badge--wide" id="status-'+item.id+'" >cancelado</span></td>';

                            if(item.status == 'andamento')
                                html += '<td id="status-'+item.id+'"><span class="m-badge m-badge--warning m-badge--wide" id="status-'+item.id+'" >em andamento</span></td>';

                            if(item.status == 'pago')
                                html += '<td id="status-'+item.id+'"><span class="m-badge m-badge--success m-badge--wide" id="status-'+item.id+'" >pago</span></td>';

                            if(item.status == 'isento')
                                html += '<td id="status-'+item.id+'"><span class="m-badge m-badge--info m-badge--wide" id="status-'+item.id+'" >isento</span></td>';


                    html += '<td>'
                            +'<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">' 
                                +'<button type="button" class="btn btn-sm  btn-success" onclick="alterarStatus('+item.id+', \'pago\')">Pago</button>'
                                +'<button type="button" class="btn btn-sm btn-danger" onclick="alterarStatus('+item.id+', \'cancelado\')">Cancelado</button>'
                                +'<button type="button" class="btn btn-sm btn-warning" onclick="alterarStatus('+item.id+', \'andamento\')">Em andamento</button>'
                                +'<button type="button" class="btn btn-sm btn-info" onclick="alterarStatus('+item.id+', \'isento\')">Isento</button>'
                            +'</div>'                             
                        +'</td>'
                    +'</tr>';
                return html;
            }
            
   
            $("#nome").keydown(function(e){
                if (e.wich == 13 || e.keyCode == 13) {            
                    pesquisa();  
                } 
            });
            
            function pesquisa(){
                if (($("#nome").val() != "") && ($("#cpf").val() != "")){
                    var html = 
                        '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-warning alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-warning"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Pesquise uma opção por vez!</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                    $('#aviso').html(html);                    
                } else if ($("#nome").val() != ""){
                    pesquisaNome();    
                } else if ($("#cpf").val()){
                    pesquisaCPF();       
                } else if (($("#nome").val() == "") && ($("#cpf").val() == "")) {
                    pesquisaNome();     
                }
            }            
            
            function pesquisaNome(){
                $("#bt_pesquisar").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "inscricao/pesquisar-inscricoes-nome",
                    data: "nome="+ $("#nome").val(),
                    success: function(data) {
                        var numLinhas = 0;
                        $('#titulo').val(data.titulo);
                        var html = 
                            '<table class="table table-striped">'
                            +'<tr>'
                                +'<th style="width: 130px;">CPF</th>'
                                +'<th style="width: 230px;">Participante</th>'
                                +'<th style="width: 280px;">Atividade</th>'
                                +'<th style="width: 120px;">Data</th>'
                                +'<th style="width: 140px;">Status</th>'
                                +'<th style="width: 200px;">Alterar para</th>'
                            +'</tr>';
                        $.each(data.data.data, function(i, item) {
                            html += prepareTableLines(data.data.data[i]);
                            numLinhas++;
                        });
                        html += '</table>';
                        $('#content-gerenciar-incricoes').html(html);
                        $("#bt_pesquisar").prop("disabled", false);
                        if (numLinhas < 15){
                            $("#links").hide();    
                        } else {
                            $("#links").show(500);    
                        }
                    }
                });
            }
        
            function pesquisaCPF(){
                $("#bt_pesquisar").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "inscricao/pesquisar-inscricoes-cpf",
                    data: "cpf="+ $("#cpf").val(),
                    success: function(data) {
                        var numLinhas = 0;
                        $('#titulo').val(data.titulo);
                        var html = 
                            '<table class="table table-striped">'
                            +'<tr>'
                                +'<th style="width: 130px;">CPF</th>'
                                +'<th style="width: 230px;">Participante</th>'
                                +'<th style="width: 280px;">Atividade</th>'
                                +'<th style="width: 120px;">Data</th>'
                                +'<th style="width: 140px;">Status</th>'
                                +'<th style="width: 200px;">Alterar para</th>'
                            +'</tr>';
                        $.each(data.data.data, function(i, item) {
                            html += prepareTableLines(data.data.data[i]);
                            numLinhas++;
                        });
                        html += '</table>';
                        $('#content-gerenciar-incricoes').html(html);
                        $("#bt_pesquisar").prop("disabled", false);
                        if (numLinhas < 15){
                            $("#links").hide();    
                        } else {
                            $("#links").show(500);    
                        }
                    }
                });
            }       
        
            function listarInscricoesGerenciar(){
                $.ajax({
                    url: 'inscricao/listar-inscricoes-gerenciar',
                    type: 'GET',
                    data: null,
                    success: function(data) {
                        $('#titulo').val(data.titulo);
                        var html = 
                            '<table class="table table-striped">'
                            +'<tr>'
                                +'<th style="width: 130px;">CPF</th>'
                                +'<th style="width: 230px;">Participante</th>'
                                +'<th style="width: 280px;">Atividade</th>'
                                +'<th style="width: 120px;">Data</th>'
                                +'<th style="width: 140px;">Status</th>'
                                +'<th style="width: 200px;">Alterar para</th>'
                            +'</tr>';
                        $.each(data.data.data, function(i, item) {
                            html += prepareTableLines(data.data.data[i]);
                        });
                        html += '</table>';
                        $('#content-gerenciar-incricoes').html(html);
                    }
                });
            }                     
                    
            function alterarStatus(id, status){               
                $.ajax({
                    url: 'inscricao/alterar-status',
                    type: 'GET',
                    data: "id="+id+"&status="+status,
                    success: function(data) {
                        $('#status-'+data.data.id).text(data.data.status);
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
                        var html = '<span class="m-badge m-badge--'+cor+' m-badge--wide" id="status-'+data.data.id+'" >'+status_pos+'</span>';
                        $('#status-'+data.data.id).html(html);
                    }
                });
            }
        </script>
    @endsection