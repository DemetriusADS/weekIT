@extends('layouts.app')

@section('content')
       <div class="m-content">
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