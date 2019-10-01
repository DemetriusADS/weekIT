@extends('layouts.app')

@section('content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">
                  <div class="form-group col-12">
                    <h3 id="titulo">Atividades para sorteio</h3>
                      <label for="atividade_select">Atividade </label>
                      <div id="aviso_atividade"></div> 
                      <div id="content-select-atividade">
                          <select class="form-control" id="atividade_select">
                            <option></option>
                          </select>
                      </div> 
                  </div> 
                <div id="content-sorteio">
                    <div class="form-group col-12 text-center">
                            <br><hr>
                        <br><br>
                        <h4><button type="button" onclick="realizarSorteio()" class="btn-lg btn-primary">SORTEAR</button></h4>                        
                    </div>                    
                </div> 
                <div>
                  <div class="row" id="content-sorteio-ganhadores">
                 
                  </div> 
               </div> 
            </div>
        </div>        
    </div>
@endsection
    @section('scripts')
        <script type="text/javascript">       
            var ganhadores = "";
            var qtGanhadores = 0;
            function carregaAtividades(){
                $.ajax({
                    type: "GET",
                    url: "/inscricao/atividade/presenca/gerenciar-presenca",
                    data: null,
                    success: function(data) {
                        if (data.atividades.length > 0) {
                            $('#aviso_atividade').html("");
                            var html = 
                                '<select class="form-control" id="atividade_select"><option></option>';
                            $.each(data.atividades, function(i, item) {                                
                                html += '<option value="'+data.atividades[i].id+'">'+data.atividades[i].identificador +' - '+data.atividades[i].titulo +
                                  ' | '+ data.atividades[i].data_inicio +'</option>';
                            });
                            html += '</select>';
                            $('#content-select-atividade').html(html);
                        } else {
                            $('#content-select-atividade').html("");
                            $('#aviso_atividade').html('<span style="color: red;"Nenhuma atividade vinculada ao seu usuário de monitoria. Entrar em contato com o coordenador do evento.</span>');
                        }
                    }
                });               
            }
            carregaAtividades();

            function realizarSorteio(){
                $.ajax({
                    type: "GET",
                    url: "/inscricao/atividade/sorteio/realizar-sorteio",
                    data: "atividade_id="+ $('#atividade_select').val() + "&lista="+ganhadores.substring(1, ganhadores.length),
                    success: function(data) {
                        qtGanhadores++;
                        ganhadores += ","+data.ganhador[0].id;
                        html = '<div class="col-md-4">'
                                    +'<div class="card mb-4 shadow-sm">'
                                        +'<img class="card-img-top" src="{{ url("img/winner.png") }}" alt="Card image cap">'
                                    +'<div class="card-body">'
                                        +'<p class="card-text text-center">'
                                           +'<span id="nome"><strong>'+data.ganhador[0].nome.toUpperCase()+'</strong></span><br>'
                                           +'<span id="cpf">'+data.ganhador[0].email+'</span>'
                                        +'</p>'
                                        +'<div class="d-flex justify-content-between align-items-center">'
                                            +'<small class="text-muted">'+qtGanhadores+'º ganhador(a)</small>'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                            +'</div>';
                        $('#content-sorteio-ganhadores').append(html);
                    }
                });                 
            }
        </script>
    @endsection 