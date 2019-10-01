@extends('layouts.app')

@section('content')
    <div class="m-content">
        @if(DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa == DB::table('evento')->max('id'))
            <div class="bs-component">
                <div class="alert alert-dismissible" style="background-color: #ebfaeb;">
                    <h4 class="block sbold" style="padding-bottom: 10px;">As inscrições na Week IT 2018 ocorrerão de 12/11/2018 a 30/11/2018</h4>
                    <p class="block" style="padding-bottom: 10px;">* A Inscrição no Evento será gratuita (acesso às  palestras, submissão e mostra de trabalhos) </p>
                    <p class="block" style="padding-bottom: 10px;">* Inscrição em minicursos R$ 10,00 (cada) </p>
                    <p class="block">* O pagamento das inscrições deverá ser feito no IFBA, nos dias: </p>
                    <p style="padding: 5px 10px;">13 a 17/11 das 15:00 às 17:00h / das 19:30 às 21:30h - para inscrições realizadas até o dia 16/11<br/>
                        20 a 24/11 das 15:00 às 17:00h / das 19:30 às 21:30h - para inscrições realizadas até o dia 24/11<br/>
                    </p>

                      <p style="padding: 5px 10px;background: #fbc8c8;margin-top: 12px;"> As inscrições não pagas até a data limite serão canceladas e as vagas correspondentes serão liberadas para novas inscrições.
                    </p>

                     <p style="padding: 5px 10px;background: #fbc8c8; margin-top: 12px; "> O pagamento deverá ser feito na entrada do auditório principal.
                     </p>


                </div>
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
@endsection
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
                                    +'<th style="width: 140px;">Status</th>'
                                    +'<th>Ação</th>'
                            +'</tr>';
                        $.each(data.inscricoes.data, function(x, item){
                            html += 
                            '<tr>'
                                +'<td>'+data.inscricoes.data[x].identificador +' - '+ data.inscricoes.data[x].titulo +'</td>'               
                                +'<td>'+data.inscricoes.data[x].data_inicio+' de '+data.inscricoes.data[x].hora_inicio +' até '+ data.inscricoes.data[x].hora_fim +'</td>'               
                                +'<td>'+data.inscricoes.data[x].descricao +'</td>';               

                                if(data.inscricoes.data[x].status == 'cancelado')
                                   html += '<td id="status-'+data.inscricoes.data[x].id +'"><span class="m-badge m-badge--danger m-badge--wide" id="status-'+data.inscricoes.data[x].id+'" >cancelado</span></td>';

                                if(data.inscricoes.data[x].status == 'andamento')
                                    html += '<td id="status-'+data.inscricoes.data[x].id +'"><span class="m-badge m-badge--warning m-badge--wide" id="status-'+data.inscricoes.data[x].id +'" >em andamento</span></td>';

                                if(data.inscricoes.data[x].status == 'pago')
                                   html += '<td id="status-'+data.inscricoes.data[x].id +'"><span class="m-badge m-badge--success m-badge--wide" id="status-'+data.inscricoes.data[x].id +'" >pago</span></td>';

                                if(data.inscricoes.data[x].status == 'isento')
                                   html += '<td id="status-'+data.inscricoes.data[x].id +'"><span class="m-badge m-badge--info m-badge--wide" id="status-'+data.inscricoes.data[x].id +'" >isento</span></td>';

                                if((data.inscricoes.data[x].status == 'cancelado') || (data.inscricoes.data[x].status == 'andamento')) {
                                    html += '<td id="acao-'+data.inscricoes.data[x].id +'"><div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">'
                                    +'<button type="button" class="btn btn-sm  btn-danger" onclick="removerInscricao('+data.inscricoes.data[x].id +')">Remover</button>'
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
                        listarAtividadesParaInscricao();
                        listarInscricoesParticipante();
                    }
                });
            }
        </script>
    @endsection 