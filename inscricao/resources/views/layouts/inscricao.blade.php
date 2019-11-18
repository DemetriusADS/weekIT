@extends('layouts.app')

@section('content')
    <div class="m-content">
        @if(DB::table('participante')->join('evento','evento.id','=','participante.edicao_ativa')->select('participante.edicao_ativa')->where('participante.id','=',Auth::user()->id)->get()[0]->edicao_ativa == DB::table('evento')->max('id'))
            <div class="bs-component">
                <div class="alert alert-dismissible" style="background-color: #ebfaeb;">
                    <h4 class="block sbold" style="padding-bottom: 10px;">As inscrições na Week-IT 2018 estão abertas de <b>12 a 30/11</b>.</h4>
                    

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
            <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__body">
                            <div id="aviso"></div>
                        <div id="atividades-abertas"></div> 
                        
                    </div>
                </div> 
        @endif
    </div>
    <div id='atividades-abertas'></div>
@endsection
    @section('scripts')
        <script type="text/javascript">       

            /*function listarInscricoesParticipante(){
                $.ajax({
                    url: 'inscricao/minhas-inscricoes',
                    type: 'GET',
                    data: null,
                    success: function(data) {
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
            listarInscricoesParticipante();  */          
            
            function inscricoesAbertas(){
                var d = new Date();
                dataHora = (d.toLocaleString());
                $.ajax({
                    url: '/inscricao/atividades-inscricao',
                    type: 'GET',
                    data: null,
                    success:function(data){
                        var html =
                            '<h2>'+data.titulo +'</h2>'
                                +'<br>'
                            +'<div class="table-responsive-md"><table class="table table-striped">'
                                +'<tr>'
                                   
                                    +'<th>Titulo</th>'
                                    +'<th>Data</th>'
                                    +'<th>Local</th>'
                                    +'<th>Max</th>'
                                    +'<th>Inscritos</th>'
                                   +'<th>CH</th>'
                                   +'<th>Preço</th>'
                                    +'<th>Ação</th>'
                            +'</tr>';
                        $.each(data.atividades, function(x, item){
                                if(!data.atividades[x].ja_inscrito && data.atividades[x].liberar_inscricao){
                                    html +='<tr>'
                                +'<td>'+data.atividades[x].identificador +' - '+ data.atividades[x].titulo +'</td>'              
                                +'<td>'+data.atividades[x].data_inicio+' de '+data.atividades[x].hora_inicio +' até '+ data.atividades[x].hora_fim +'</td>'               
                                +'<td>'+data.atividades[x].descricao +'</td>'
                                +'<td>'+data.atividades[x].maximo_participantes +'</td>' 
                                +'<td>'+data.atividades[x].inscritos +'</td>'
                                +'<td>R$ '+data.atividades[x].preco +'</td>'              
                                +'<td>'+data.atividades[x].carga_horaria + 'hrs'+'</td>'
                                +'<td id="acao2-'+data.atividades[x].id+'">'
                                +'<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="..."><button class="btn btn-info" onclick="inscrverAtividade('+data.atividades[x].id +')">Inscrever</button></td>'
                                }
                                else if(!data.atividades[x].ja_inscrito && data.atividades[x].liberar_inscricao){
                                    html +='<tr>'
                                +'<td>'+data.atividades[x].identificador +' - '+ data.atividades[x].titulo +'</td>'              
                                +'<td>'+data.atividades[x].data_inicio+' de '+data.atividades[x].hora_inicio +' até '+ data.atividades[x].hora_fim +'</td>'               
                                +'<td>'+data.atividades[x].descricao +'</td>'
                                +'<td>'+data.atividades[x].maximo_participantes +'</td>' 
                                +'<td>'+data.atividades[x].inscritos +'</td>'
                                +'<td>R$ '+data.atividades[x].preco +'</td>'              
                                +'<td>'+data.atividades[x].carga_horaria + 'hrs'+'</td>'
                                +'<td id="acao"><button class="btn btn-info disabled">Inscrever</button></td>'
                                }
                            html += '</tr>';
                        });
                        
                        html += '</table></div>';
                        $('#atividades-abertas').html(html);
                    }
                })
            }
            inscricoesAbertas();
            
            function inscrverAtividade(id){
                $.ajax({
                    url: 'inscricao/realizar-inscricao',
                    type: 'GET',
                    data: "atividade_id="+id,
                    success: function(data) {
                        if (data.resposta == 1){                       
                            var html = 
                                '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-success"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Inscrito com sucesso!</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                            $('#aviso2').html(html);
                            $('#acao2-'+id).html('<span style="color: green; font-weight: bold;">Inscrito</span>');                            
                        }else{
                            var html = 
                                '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-danger"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Você não pode se inscrever nessa atividade pois já tem uma inscrição em outra atividade no mesmo horário.</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                            $('#aviso2').html(html);
                            $('#acao2-'+id).html('<span style="color: red; font-weight: bold;">Choque de horarios</span>');
                        }
                        inscricoesAbertas();
                    }
                });
            }
            window.adicionarInscricao = adicionarInscricao
            
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
                        inscricoesAbertas();
                    }
                });
            }
        </script>
    @endsection 