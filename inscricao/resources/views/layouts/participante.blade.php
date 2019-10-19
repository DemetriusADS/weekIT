@extends('layouts.app')

@section('content')
       <div class="m-content">
       
       <div id="content-incricoes"></div> 
        
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">

                <div id="content-minhas-incricoes"></div> 
                
            </div>
        </div> 

    </div>
    <div class='content'>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <div id="atividades-abertas"></div>
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
                        console.table(data.inscricoes);
                        var html =
                            '<h2>'+data.titulo +'</h2>'
                                +'<br>'
                            +'<div class="table-responsive-md"><table class="table table-striped">'
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
                        
                        html += '</table></div>';
                        $('#content-minhas-incricoes').html(html);
                    }
                });                
            }
            listarInscricoesParticipante();              
            function inscricoesAbertas(){
                //ARRUMAR!!!!
                $.ajax({
                    url: '/inscricao/atividades-inscricao',
                    type: 'GET',
                    data: null,
                    success:function(data){
                        //inserir tabela de atividades abertas
                        //vai fazer o contrario do listar participante
                        //console.table(data.atividades);
                        var html =
                            '<h2>'+data.titulo +'</h2>'
                                +'<br>'
                            +'<div class="table-responsive-md"><table class="table table-striped">'
                                +'<tr>'
                                   
                                    +'<th>Titulo</th>'
                                    +'<th>Data</th>'
                                    +'<th>Descrição</th>'
                                    +'<th>Max</th>'
                                    +'<th>Inscritos</th>'
                                   +'<th>CH</th>'
                                   +'<th>Preço</th>'
                                    +'<th>Ação</th>'
                            +'</tr>';
                        $.each(data.atividades, function(x, item){
                          //  'id' => $entry->id,
                               // 'identificador' => $entry->identificador,
                              //  'titulo' => $entry->titulo,
                               /* 'descricao' => $entry->descricao,
                                'data_inicio' => $entry->data_inicio,
                                'hora_inicio' => $entry->hora_inicio,
                                'hora_fim' => $entry->hora_fim,
                                'maximo_participantes' => $entry->maximo_participantes,
                                'carga_horaria' => $entry->carga_horaria,
                                'data_inicio_insc' => $entry->data_inicio_insc,
                                'data_fim_insc' => $entry->data_fim_insc,
                                'inscritos' => $inscritos,
                                'liberar_inscricao' => $liberarInscricao,
                                'liberar_breve' => $liberarBreve,
                                'liberar_encerrado' => $liberarEncerrado,
                                'liberar_esgotado' => $liberarEsgotato,
                                'ja_inscrito' => $ja_inscrito*/
                                //console.table(data.atividades);
                           
                                if(!data.atividades[x].ja_inscrito){
                                    html +='<tr>'
                                +'<td>'+data.atividades[x].identificador +' - '+ data.atividades[x].titulo +'</td>'              
                                +'<td>'+data.atividades[x].data_inicio+' de '+data.atividades[x].hora_inicio +' até '+ data.atividades[x].hora_fim +'</td>'               
                                +'<td>'+data.atividades[x].descricao +'</td>'
                                +'<td>'+data.atividades[x].maximo_participantes +'</td>' 
                                +'<td>'+data.atividades[x].inscritos +'</td>'
                                +'<td>R$ '+data.atividades[x].preco +'</td>'              
                                +'<td>'+data.atividades[x].carga_horaria + 'hrs'+'</td>'
                                +'<td><button class="btn btn-info" onclick="removerInscricao('+data.atividades[x].id +')">Inscrever</button></td>'}

                               /* if (!data.inscricoes[x].encerrada) {
                                    if(data.inscricoes[x].status == 'cancelado')
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--danger m-badge--wide" id="status-'+data.inscricoes[x].id+'" >cancelado</span></td>';

                                    if(data.inscricoes[x].status == 'andamento')
                                        html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--warning m-badge--wide" id="status-'+data.inscricoes[x].id +'" >em andamento</span></td>';

                                    if(data.inscricoes[x].status == 'pago')
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--success m-badge--wide" id="status-'+data.inscricoes[x].id +'" >pago</span></td>';

                                    if(data.inscricoes[x].status == 'isento')
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--info m-badge--wide" id="status-'+data.inscricoes[x].id +'" >isento</span></td>';
                                } else {
                                    /*if (data.inscricoes[x].presente == 1) {
                                        html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--success m-badge--wide" id="status-'+data.inscricoes[x].id +'" >presente</span></td>';
                                    } else if (data.inscricoes[x].presente == 0) {
                                       html += '<td id="status-'+data.inscricoes[x].id +'"><span class="m-badge m-badge--danger m-badge--wide" id="status-'+data.inscricoes[x].id+'" >ausente</span></td>';
                                    }                                    
                                }

                                if(!data.atividadeList[x].encerrada && ((data.atividadeList[x].status == 'isento') || (data.atividadeList[x].status == 'andamento'))) {
                                    html += '<td id="acao-'+data.atividadeList[x].id +'"><div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">'
                                    +'<button type="button" class="btn btn-sm  btn-danger" onclick="removerInscricao('+data.atividadeList[x].id +')">Remover</button>'
                                    +'</div></td>';
                                } else {
                                    html += '<td></td>';
                                }*/
                            html += '</tr>';
                        });
                        
                        html += '</table></div>';
                        $('#atividades-abertas').html(html);
                    }
                })
            }
            inscricoesAbertas();
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
                    }
                });
            }
            window.removerInscricao = removerInscricao
        </script>
    @endsection  
@endsection