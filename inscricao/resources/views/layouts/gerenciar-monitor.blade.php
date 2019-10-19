@extends('layouts.app')

@section('content')
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                <h2 id="titulo">Gerenciar monitor</h2>
                <form action="{{route('vincular-monitor',['monitor_select' =>csrf_field(), 'atividade_select' => csrf_field()])}}" method="get">
                  <div class="form-group col-8">
                      <label for="monitor_select">Monitor </label>
                      <select class="form-control" id="monitor_select" name="monitor_select">                        
                        @foreach($monitores as $monitor)
                            <option value="{{ $monitor->participante_id }}">{{strtoupper($monitor->nome)}}</option>
                        
                        @endforeach
                      </select>
                  </div>                    
                  <div class="form-group col-12">
                      <label for="atividade_select">Atividade </label>
                      <div id="aviso_atividade"></div> 
                      <div id="content-select-atividade">
                          <select class="form-control" id="atividade_select" name="atividade_select">                            
                            @foreach ($atividades as $atividade)
                            <option value="{{ $atividade->id }}">{{strtoupper($atividade->titulo)}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div> 
                    <button id="bt_vincular" class="btn btn-info" onclick="vincularMonitor()" type="submit">Vincular Monitor</button>                
                </form>
                </div>
                    <br><br>
                <h3 id="titulo">Monitorias</h3>
                <div id="aviso"></div>    
                <div id="content-gerenciar-monitores">
                                   
                </div>              
            </div>
       </div>             
    </div> 
    @section('scripts')
        <script type="text/javascript">       
            $('#monitor_select').change(function(){
                carregaAtividades(); 
            });
     
            function carregaAtividades(){
                $.ajax({
                    type: "GET",
                    url: "/inscricao/atividade/monitor/carregar-atividades",
                    data: "participante_id="+ $("#monitor_select").val(),
                    success: function(data) {
                        if (data.data.length > 0){
                            $('#aviso_atividade').html("");
                            var html = 
                                '<select class="form-control" id="atividade_select" name="atividade_select">';
                            $.each(data.data, function(i, item) {                                
                                html += '<option value="'+data.data[i].id+'">'+data.data[i].identificador +' - '+data.data[i].titulo +'</option>';
                            });
                            html += '</select>';
                            $('#content-select-atividade').html(html);
                        } else {
                            $('#content-select-atividade').html("");
                            $('#aviso_atividade').html('<span style="color: red;">Não existem mais horários disponíveis para este monitor.</span>');  
                        }

                    }
                });               
            }

            function carregarMonitorias(){
                $.ajax({
                    type: "GET",
                    url: "/atividade/monitor/carregar-monitorias",
                    data: null,
                    success: function(data) { 
                        if (data.monitorias.length > 0){
                          var html = 
                              '<table class="table table-striped">'
                              +'<tr>'                                
                                  +'<th style="width: 180px;">Monitor</th>'
                                  +'<th style="width: 360px;">Atividade</th>'
                                  +'<th style="width: 160px;">Data</th>'
                                  +'<th style="width: 40px;">Ação</th>'
                              +'</tr>';
                          $.each(data.monitorias, function(i, item) {                                
                              html += 
                                '<tr>'                        
                                    +'<td>'+data.monitorias[i].nome.toUpperCase()+'</td>'
                                    +'<td>'+data.monitorias[i].identificador +' - '+ data.monitorias[i].titulo +'</td>'
                                    +'<td>'+data.monitorias[i].data +' de '+ data.monitorias[i].hora_inicio +' às '+ data.monitorias[i].hora_fim +'</td>'
                                    +'<td id="acao-'+data.monitorias[i].monitor_id+'">'
                                      +'<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">'
                                        +'<button type="button" class="btn btn-sm  btn-danger" onclick="removerMonitoria('+ data.monitorias[i].monitor_id +','+ data.monitorias[i].atividade_id +')">Remover</button>'
                                      +'</div>'
                                    +'</td>'
                                +'</tr>';                          
                          });
                          html += '</table>';
                          $('#content-gerenciar-monitores').html(html);
                        } else {
                          $('#content-gerenciar-monitores').html("");
                        }

                    }
                });               
            }
            carregarMonitorias();

            function vincularMonitor(){
                $.ajax({
                    url: '/atividade/monitor/vincular-monitor',
                    type: 'get',
                    data: "monitor_id="+$("#monitor_select").val()+"&atividade_id="+$("#atividade_select").val(),
                    success: function(data) {
                        if (data.resposta == 1){
                            carregarMonitorias();
                            carregaAtividades();                                
                        }                        
                    }
                });
            }

            function removerMonitoria(monitor_id, atividade_id){
                $('#bt_vincular').prop('disabled', true);
                $.ajax({
                    url: '/atividade/monitor/remover-monitoria',
                    type: 'GET',
                    data: "monitor_id="+ monitor_id +"&atividade_id="+ atividade_id,
                    success: function(data) {
                        if (data.resposta != -1){
                            var html = 
                                '<div class="m-form__section m-form__section--first "><div class="form-group m-form__group row"><div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success alert-dismissible fade show" role="alert"><div class="m-alert__icon"><i class="flaticon-success"></i></div><div class="m-alert__text"><strong>Aviso ! </strong>Monitoria removida com sucesso !</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div></div></div></div>';
                            $('#aviso').html(html);
                            carregarMonitorias();
                            console.log($("#monitor_select").val());
                            if ($("#monitor_select").val() != ""){
                                carregaAtividades();
                            }
                            $('#bt_vincular').prop('disabled', false);
                        }                        
                    }
                });
            }            
        </script>
    @endsection  
@endsection