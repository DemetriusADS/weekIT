@extends('layouts.app')

@section('content')
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">                
                  <div class="form-group col-12">
                    <h3 id="titulo">Atividades para monitoria</h3>
                      <label for="atividade_select">Atividade </label>
                      <div id="aviso_atividade"></div> 
                      <div id="content-select-atividade">
                          <select class="form-control" id="atividade_select">
                            <option></option>
                          </select>
                      </div> 
                      <div id="content-select-tppresenca">
                          <br>
                          <select class="form-control col-2" id="tppresenca_select">
                            <option value="1">Manual</option>
                            <option value="2">Leitor código de barras</option>
                          </select>
                      </div>                     
                  </div>                    
             
                    <br>
                <h4 id="titulo_controle">Controle de presença</h4>
                <div id="aviso"></div>    
                <div id="content-gerenciar-presencas">
                
                </div>              
            </div>
       </div>             
    </div> 
    @section('scripts')
        <script type="text/javascript">       
            $('#content-select-tppresenca').hide();
           
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
                            $('#atividade_select').change(function(){
                                preparaGerenciamentoPresenca(); 
                            });
                        } else {
                            $('#content-select-atividade').html("");
                            $('#aviso_atividade').html('<span style="color: red;"Nenhuma atividade vinculada ao seu usuário de monitoria. Entrar em contato com o coordenador do evento.</span>');
                        }
                    }
                });               
            }
            carregaAtividades();

            function preparaGerenciamentoPresenca(){
                $.ajax({
                    type: "GET",
                    url: "/inscricao/atividade/presenca/carregar-participantes",
                    data: "atividade_id="+ $('#atividade_select').val(),
                    success: function(data) {  
                        if (data.participantes.length > 0 || data.participantes == 1){
                            var titulo = data.tipo;
                            if (titulo == "mesaredonda"){
                              titulo = "mesa-redonda";
                            }                          
                            $('#titulo_controle').html("Controle de presença para "+titulo);
                            if (data.tipo == 'minicurso'){
                                $('#content-select-tppresenca').show(400);
                                $('#tppresenca_select').val(1);
                                $('#tppresenca_select').change(function(){
                                    if ($('#tppresenca_select').val() == 1){
                                        carregarParticipantes();                                          
                                    } else {
                                        showBarcodeLoad();                                        
                                    }                
                                });

                                if ($('#tppresenca_select').val() == 1){
                                    carregarParticipantes();                                          
                                } else {
                                    showBarcodeLoad();                                        
                                }                                                                    
                            } else {
                                $('#content-select-tppresenca').hide(400);
                                showBarcodeLoad();    
                            }
                           
                        } else {
                            $('#titulo_controle').html("Controle de presença");
                            $('#content-gerenciar-presencas').html("");  
                        }

                    }
                });               
            }    

            function carregarParticipantes(){
                $.ajax({
                    type: "GET",
                    url: "/inscricao/atividade/presenca/carregar-participantes",
                    data: "atividade_id="+ $('#atividade_select').val(),
                    success: function(data) {  
                        if (data.participantes.length > 0){
                          $('#titulo_controle').html("Controle de presença para "+data.tipo);
                          var contador = 0;
                          var html = 
                              '<table class="table table-striped">'
                              +'<tr>'                                
                                  +'<th style="width: 20px;">N°</th>'
                                  +'<th style="width: 180px;">CPF</th>'
                                  +'<th style="width: 380px;">Participante</th>'
                                  +'<th style="width: 80px;">Situação</th>'                                  
                                  +'<th style="width: 80px; text-align: center;">Presente</th>'                                  
                              +'</tr>';
                          $.each(data.participantes, function(i, item) { 
                              contador++;                               
                              html += 
                                '<tr>'                        
                                    +'<td>'+contador+'</td>'
                                    +'<td>'+data.participantes[i].cpf+'</td>'
                                    +'<td>'+data.participantes[i].nome.toUpperCase()+'</td>'

                                  if(data.participantes[i].status == 'cancelado')
                                      html += '<td><span class="m-badge m-badge--danger m-badge--wide">cancelado</span></td>';

                                  if(data.participantes[i].status == 'andamento')
                                      html += '<td><span class="m-badge m-badge--warning m-badge--wide">em andamento</span></td>';

                                  if(data.participantes[i].status == 'pago')
                                      html += '<td><span class="m-badge m-badge--success m-badge--wide">pago</span></td>';

                                  if(data.participantes[i].status == 'isento')
                                      html += '<td><span class="m-badge m-badge--info m-badge--wide">isento</span></td>';

                                  if (data.participantes[i].status != 'pago' && data.participantes[i].status != 'isento') {
                                      html += '<td>&nbsp;</td>'
                                  } else {
                                    if (data.participantes[i].presente){
                                        html += '<td style="text-align: center;" id="acao-'+data.participantes[i].id+'">'
                                            +'<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">'
                                              +'<input style="filter: invert(100%) hue-rotate(18deg) brightness(1.7);" type="checkbox" value="'+ data.participantes[i].presente +'" onclick="setarPresenca('+ data.participantes[i].inscricao_id + ','+ data.participantes[i].id +')" checked/>'
                                            +'</div>'
                                          +'</td>'
                                      +'</tr>';
                                    } else {
                                        html += '<td style="text-align: center;">'
                                            +'<div class="btn-group m-btn-group" id="alterar-status" role="group" aria-label="...">'
                                              +'<input id="acao-'+data.participantes[i].id+'" style="filter: invert(100%) hue-rotate(18deg) brightness(1.7);" type="checkbox" value="'+ data.participantes[i].presente +'" onclick="setarPresenca('+ data.participantes[i].inscricao_id + ','+ data.participantes[i].id +')"/>'
                                            +'</div>'
                                          +'</td>'
                                      +'</tr>';                                    
                                    }                                    
                                  } 

                          
                          });
                          html += '</table>';
                          $('#content-gerenciar-presencas').html(html);
                        }  else {
                            $('#titulo_controle').html("Controle de presença");
                            $('#content-gerenciar-presencas').html("");  
                        }

                    }
                });               
            }

            function setarPresenca(inscricao_id, participante_id){
                var presente = +$('#acao-'+participante_id).is( ':checked' );
                $.ajax({
                    url: '/inscricao/atividade/presenca/setar-presenca',
                    type: 'GET',
                    data: "inscricao_id="+ inscricao_id +"&presente="+ presente,
                    success: function(data) {
                        if (data.resposta == 1){
                                                           
                        }                        
                    }
                });
            } 

            (function ($) {
                $.fn.codeScanner = function (options) {
                    var settings = $.extend({}, $.fn.codeScanner.defaults, options);

                    return this.each(function () {
                        var pressed = false;
                        var chars = [];
                        var $input = $(this);

                        $(window).keypress(function (e) {
                            var keycode = (e.which) ? e.which : e.keyCode;
                            if ((keycode >= 65 && keycode <= 90) ||
                                (keycode >= 97 && keycode <= 122) ||
                                (keycode >= 48 && keycode <= 57)
                            ) {
                                chars.push(String.fromCharCode(e.which));
                            }
                            // console.log(e.which + ":" + chars.join("|"));
                            if (pressed == false) {
                                setTimeout(function () {
                                    if (chars.length >= settings.minEntryChars) {
                                        var barcode = chars.join('');
                                        settings.onScan($input, barcode);
                                    }
                                    chars = [];
                                    pressed = false;
                                }, settings.maxEntryTime);
                            }
                            pressed = true;
                        });

                        $(this).keypress(function (e) {
                            if (e.which === 13) {
                                e.preventDefault();
                            }
                        });

                        return $(this);
                    });
                };

                $.fn.codeScanner.defaults = {
                    minEntryChars: 8,
                    maxEntryTime: 100,
                    onScan: function ($element, barcode) {
                        buscaParticipante(barcode);  
                    }
                };
            })(jQuery);

            function buscaParticipante(cpf){
                $.ajax({
                    url: '/inscricao/atividade/presenca/busca-participante',
                    type: 'GET',
                    data: "cpf="+ cpf +"&atividade_id="+ $('#atividade_select').val(),
                    success: function(data) {
                        if (data.participante.length > 0 && data.presente == 0){
                            var html = 
                              '<div class="form-group col-12">'
                                    +'<br><br>'
                                    +'<div class="panel panel-success">'
                                      +'<div class="panel-heading">Participante verificado</div>'
                                      +'<div class="panel-body">'
                                        +'<ul>'
                                          +'<li><span id="cpf">'+data.participante[0].cpf+'</span></li>'
                                          +'<li><span id="nome">'+data.participante[0].nome+'</li>'
                                        +'</ul>'
                                        +'<input type="hidden" id="participante_id" value="'+data.participante[0].id+'"/>'
                                        +'<div class="text-right">'
                                            +'<button class="btn btn-danger" id="bt_cancela" onclick="showBarcodeLoad()">ESC PARA CANCELAR</button>&nbsp;&nbsp;'  
                                            +'<button class="btn btn-primary" id="bt_confirma" onclick="confirmarPresenca()">ENTER PARA CONFIRMAR</button>'  
                                        +'</div>'
                                      +'</div>'
                                    +'</div>'                        
                                +'</div>';
                            $('#content-gerenciar-presencas').html(html);                                        
                        } else if (data.participante.length == 0) {
                            exibeNotificacao("<b>Aviso</b> - Nenhum registro encontrado!", 'danger');
                            showBarcodeLoad();                                 
                        } else {
                            exibeNotificacao("<b>Aviso</b> - A presença deste participante já foi confirmada!", 'warning');
                            showBarcodeLoad();                                                                   
                        }                       
                    }
                });              
            }

            document.querySelector('body').addEventListener('keydown', function(event){
                if(getCharCode(event) == 13) $('#bt_confirma').click();
                if(getCharCode(event) == 27) $('#bt_cancela').click();
            });

            function getCharCode(e) {
              e = (e) ? e : window.event, charCode = null;

              try {
                charCode = (e.which) ? e.which : e.keyCode;
                return charCode;
              } catch (err) {
                return charCode;
              }
            }

            function confirmarPresenca(){                
                $.ajax({
                    url: '/inscricao/atividade/presenca/setar-presenca-code',
                    type: 'GET',
                    data: "participante_id="+ $('#participante_id').val() +"&atividade_id="+ $('#atividade_select').val(),
                    success: function(data) {
                        console.log(data.resposta);
                        if (data.resposta > 0){
                            exibeNotificacao("<b>Sucesso</b> - Presença confirmada!", 'success');
                            showBarcodeLoad();                               
                        } else if (data.resposta == -1) {
                            exibeNotificacao("<b>Aviso</b> - Participante não inscrito nesta atividade!", 'warning');
                            showBarcodeLoad();                                
                        } else if (data.resposta == -2) {
                            exibeNotificacao("<b>Aviso</b> - Participante inscrito, porém pagamento não efetuado!", 'warning');
                            showBarcodeLoad(); 
                        }                        
                    }
                });
            }         

            function showBarcodeLoad(){
                var html =  
                    '<div class="form-group col-12 text-center">'
                            +'<div class="text-center col-3">'
                                +'<input class="form-control" id="code-scan" type="text" placeholder="CPF"/>'
                            +'</div>'
                        +'<br><br>'
                        +'<h4>Aguardando leitura...</h4>'
                        +'<img src="{{ url("img/barcode.png") }}"/>'
                        +'</div>'; 
                $('#content-gerenciar-presencas').html(html); 
                $('#atividade_select').blur();                                                           
                $('#code-scan').val("");
                $('#code-scan').focus();
                $('#code-scan').codeScanner(); 
               $('#code-scan').on('keyup', function(e) {
                    if(e.which == 13) buscaParticipante($('#code-scan').val());
                });

            }

            function exibeNotificacao(msg, type) {
                $.notify({
                  icon: "",
                  message: msg

                }, {
                  type: type,
                  timer: 3000,
                  placement: {
                    from: 'bottom',
                    align: 'right'
                  }
                });
            }             
        </script>
    @endsection  
@endsection