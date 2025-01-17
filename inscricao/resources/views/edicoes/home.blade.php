@extends('layouts.app')


@if(Auth::user()->tipo == 'coordenador')
@section('content')
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
                } else if ($("#cpf").val() != ""){
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
@elseif(Auth::user()->tipo == 'aluno')
@section('content')
    <div class="m-content">
        @if(DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa == DB::table('evento')->max('id'))
            <div class="bs-component">
                <div class="alert alert-dismissible" style="background-color: #ebfaeb;">
                    <h4 class="block sbold" style="padding-bottom: 10px;">As inscrições na Week-IT 2019 estão abertas de <b>12 a 30/11/2018</b>.</h4>
                    

                    <p style="text-align: justify;">A inscrição no evento é gratuita e dá acesso às palestras, mesa redonda e mostra de trabalhos. A inscrição em cada minicurso é <strong>R$ 10,00</strong> (dez reais).</p>
                    <p style="text-align: justify;">O pagamento das inscrições em minicurso deverá ser feito no IFBA (<strong>ao lado da cantina</strong>), nos seguintes dias e horários:</p>
                    <ul>
                    <li><strong>Manhã</strong>: toda quarta e sexta das 10:00 às 11:00 horas</li>
                    <li><strong>À tarde</strong>: segunda, quinta e sexta das 15:30 às 17 horas</li>
                    <li><strong>À noite</strong>: todos os dias das 19:00 às 21:00 horas</li>
                    </ul>
                    <p style="padding: 5px 10px;background: #fbc8c8;margin-top: 12px;"><ystrong>As inscrições não pagas até a data limite serão canceladas e as vagas correspondentes serão liberadas para novas inscrições:</strong></p>
                    <ul>
                    <li><del>Inscrições de 12 a 18/11/2018, o pagamento deverá ser efetuado até 21/11 (quarta-feira)</del></li>
                    <li><del>Inscrições de 19 a 25/11/2018, o pagamento deverá ser efetuado até 28/11 (quarta-feira)</del></li>
                    <li><del>Inscrições de 26/11/2018 a 30/11/2018, o pagamento deverá ser efetuado até 30/11 (sexta-feira)<del></li>
                    <li>Inscrições após 30/11 devem ser pagas no credenciamento do evento</li>
                    </ul>
                    <p style="padding: 5px 10px;background: #fbc8c8;margin-top: 12px;"> <b>Aviso</b> - Por favor, verifique seu cadastro e certifique-se de atualizar seu nome completo pois este será impresso em seu(s) certificado(s).
                    </p>                                         
                </div>
            </div>
            @else
            <div class="alert alert-dismissible" style="background-color: #ebfaeb;">
                <p>Você ainda não se Inscreveu nao nova ediçao</p>
            </div>
        @endif
        <div id="aviso"></div>
       <div id="content-incricoes"></div> 
        
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">

                <div id="content-minhas-incricoes"></div> 
                
            </div>
        </div>        
    </div>

    @section('scripts')
        <script type="text/javascript">       

            function listarInscricoesParticipante(){
                $.ajax({
                    url: 'inscricao/minhas-inscricoes',
                    type: 'GET',
                    data: null,
                    success: function(data) {
                        var html =
                            '<h2>'+data.titulo +'</h2>'
                                +'<br>'
                            +'<table class="table table-striped">'
                                +'<tr>'
                                    +'<th>Titulo</th>'
                                    +'<th>Data</th>'
                                    +'<th>Local</th>'
                                    +'<th>CH</th>'
                                    +'<th style="width: 140px;">Status</th>'
                                    +'<th>Ação</th>'
                            +'</tr>';
                        $.each(data.inscricoes, function(x, item){
                            html += 
                            '<tr>'
                                +'<td>'+data.inscricoes[x].identificador +' - '+ data.inscricoes[x].titulo +'</td>'               
                                +'<td>'+data.inscricoes[x].data_inicio+' de '+data.inscricoes[x].hora_inicio +' até '+ data.inscricoes[x].hora_fim +'</td>'               
                                +'<td>'+data.inscricoes[x].descricao +'</td>'               
                                +'<td>'+data.inscricoes[x].carga_horaria + 'hrs'+'</td>';                 

                                if (!data.inscricoes[x].encerrada) {
                                    if(data.inscricoes[x].status == 'cancelado')
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--danger m-badge--wide" id="status-'+data.inscricoes[x].id+'" >cancelado</span></td>';

                                    if(data.inscricoes[x].status == 'andamento')
                                        html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--warning m-badge--wide" id="status-'+data.inscricoes[x].id +'" >em andamento</span></td>';

                                    if(data.inscricoes[x].status == 'pago')
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--success m-badge--wide" id="status-'+data.inscricoes[x].id +'" >pago</span></td>';

                                    if(data.inscricoes[x].status == 'isento')
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--info m-badge--wide" id="status-'+data.inscricoes[x].id +'" >isento</span></td>';
                                } else {
                                    if (data.inscricoes[x].presente == 1) {
                                        html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--success m-badge--wide" id="status-'+data.inscricoes[x].id +'" >presente</span></td>';
                                    } else if (data.inscricoes[x].presente == 0) {
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--danger m-badge--wide" id="status-'+data.inscricoes[x].id+'" >ausente</span></td>';
                                    }                                    
                                }

                                if(!data.inscricoes[x].encerrada && ((data.inscricoes[x].status == 'isento') || (data.inscricoes[x].status == 'andamento'))) {
                                    html += '<td id="acao-'+data.inscricoes[x].id +'"><div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">'
                                    +'<button type="button" class="btn btn-sm  btn-danger" onclick="removerInscricao('+data.inscricoes[x].id +')">Remover</button>'
                                    +'</div></td>';
                                } else {
                                    html += '<td></td>';
                                }
                            html += '</tr>';
                        });
                        
                        html += '</table>';
                        $('#content-minhas-incricoes').html(html);
                    }
                });                
            }
            listarInscricoesParticipante();            
            
            function listarAtividadesParaInscricao(){
                $.ajax({
                    url: 'inscricao/atividades-inscricao',
                    type: 'GET',
                    data: null,
                    success: function(data) {
                        var html = "";
                        $.each(data.atividades, function(x, atividade){
                            html += 
                                '<div class="m-portlet m-portlet--mobile">'
                                        +'<div class="m-portlet__body"><h2>Minicursos do dia '+ atividade.data +'</h2>'
                                    +'<br>'
                                    +'<table class="table table-striped">'
                                +'<tr>'
                                    +'<th>Titulo</th>'
                                    +'<th>Local</th>'
                                    +'<th>Horário</th>'
                                    +'<th>Data</th>'
                                    +'<th>CH</th>'
                                    +'<th>Inscritos</th>'
                                    +'<th>Ação</th>'
                                +'</tr>';                             
                            
                            $.each(atividade.atividades, function(y, item){
                                html += 
                                    '<tr>'
                                    +'<td>'+atividade.atividades[y].identificador+' - '+ atividade.atividades[y].titulo+'</td>'               
                                    +'<td>'+atividade.atividades[y].descricao+'</td>'               
                                    +'<td>'+atividade.atividades[y].hora_inicio+' às '+atividade.atividades[y].hora_fim+'</td>'
                                    +'<td>'+atividade.atividades[y].data_inicio+'</td>'
                                    +'<td>'+atividade.atividades[y].carga_horaria + 'hrs'+'</td>'                                    
                                    +'<td>'+atividade.atividades[y].maximo_participantes+'/'+atividade.atividades[y].inscritos+'</td>';

                                    if(!atividade.atividades[y].ja_inscrito) {                            
                                            if (atividade.atividades[y].inscritos < atividade.atividades[y].maximo_participantes){
                                                if (atividade.atividades[y].liberar_inscricao){
                                                    html += '<td id="acao-'+atividade.atividades[y].id+'">'
                                                            +'<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">' 
                                                            +'<button type="button" class="btn btn-sm  btn-success" onclick="fazerInscricao('+atividade.atividades[y].id+')">Inscrever</button>'
                                                            +'</div>'                             
                                                        +'</td>';                
                                                } else {
                                                    if (atividade.atividades[y].liberar_breve){
                                                        html += '<td><span style="color: blue; font-weight: bold;">breve</span></td>';
                                                    } else if (atividade.atividades[y].liberar_encerrado) {
                                                        html += '<td><span style="color: orange; font-weight: bold;">encerrada</span></td>';
                                                    }

                                                }

                                            } else {
                                                if (atividade.atividades[y].liberar_esgotado){
                                                    html += '<td><span style="color: red; font-weight: bold;">esgotado</span></td>';
                                                } else {
                                                    if (atividade.atividades[y].liberar_breve){
                                                        html += '<td><span style="color: blue; font-weight: bold;">breve</span></td>';
                                                    } else if (atividade.atividades[y].liberar_encerrado) {
                                                        html += '<td><span style="color: orange; font-weight: bold;">encerrada</span></td>';
                                                    }
                                                }

                                            }
                                    } else {
                                        html += '<td><span style="color: green; font-weight: bold;">inscrito!</span></td>';    
                                    }
                                 html += '</tr>';                                  
                                
                            });
                            html += '</table></div></div>';
                        });
                        $('#content-incricoes').html(html);
                    }
                    
                });                
                
            }
            listarAtividadesParaInscricao();
            
            function fazerInscricao(atividade_id){
                $.ajax({
                    url: 'inscricao/realizar-inscricao',
                    type: 'GET',
                    data: "atividade_id="+atividade_id,
                    success: function(data) {
                        if (data.resposta == 1){
                            $('#acao-'+atividade_id).html('<span style="color: green; font-weight: bold;">inscrito!</span>');
                            $('#aviso').html("");
                        } else {
                            var html = 
                                '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-danger"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Você não pode se inscrever nesse evento pois já tem uma inscrição em outro    evento no mesmo horário !</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                            $('#aviso').html(html);
                        }                        
                        listarInscricoesParticipante();
                    }
                });
            }
            
            function removerInscricao(id){
                $.ajax({
                    url: 'inscricao/remover-inscricao',
                    type: 'GET',
                    data: "id="+id,
                    success: function(data) {
                        if (data.resposta == 1){                       
                            var html = 
                                '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-success"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Inscrição removida com sucesso !</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                            $('#aviso').html(html);
                            $('#acao-'+id).html('<span style="color: orange; font-weight: bold;">removida</span>');                            
                        }
                        listarInscricoesParticipante();
                        listarAtividadesParaInscricao();
                    }
                });
            }
        </script>
    @endsection        
@endsection
@elseif(Auth::user()->tipo == 'financeiro')
    @section('content')
        @include('layouts.gerenciar-inscricoes')
    @endsection
@elseif(Auth::user()->tipo == 'monitor')
    @section('content')
        @include('layouts.gerenciar-presenca')
    @endsection
@endif