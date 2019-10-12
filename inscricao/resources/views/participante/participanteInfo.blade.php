<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Informações do Participante</title>
      <link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('assets/demo/demo5/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />     
</head>
<body>
      <h2>OI</h2>
                 <div class="m-content">
                        <div class="m-portlet m-portlet--mobile">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h1 class="m-portlet__head-text">
                                            Detalhes do Participante                                            
                                        </h1> 
                                    </div>
                              </div> 
                        </div>                                  
                                    <div class="m-portlet__body">                                        
                        
                                          <table class="table m-table m-table--head-bg-success table-striped">
                                          <thead>
                                                      <tr>
                                                            <th>Campo</th>
                                                            <th>Valor</th>
                                                      </tr>
                                                      </thead>
                                          <tbody>
                                                <tr>
                                                      <td><b>Tipo</b></td>
                                                      <td>{{$dadosPessoais[0]->tipo}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>ID</b></td>
                                                       <td>{{$dadosPessoais[0]->id}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>Nome</b></td>
                                                      <td>{{$dadosPessoais[0]->Nome}}</td>
                                                </tr>
                                                <tr>
                                                <td><b>Curso</b></td>
                                                      <td>{{$dadosPessoais[0]->Curso}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>Instituição</b></td>
                                                      <td>{{$dadosPessoais[0]->Instituição}}</td>
                                                </tr>
                                                <tr>
                                                            <td><b>Evento</b></td>
                                                            <td>{{$dadosPessoais[0]->Evento}}</td>
                                                </tr>                                         
                                                <tr>
                                                      <td><b>EventoAno</b></td>
                                                      <td>{{$dadosPessoais[0]->EventoAno}}</td>
                                                </tr>
                                                
                                          @php
                                          foreach($data as $atividade => $value){
                                               // if($atividade != 'Nome' && $atividade != 'tipo'&& $atividade != 'id'
                                               // && $atividade !='Evento' && $atividade !='Curso' && $atividade !='Instituição' && 
                                               // $atividade !='EventoID' && $atividade !='EventoAno' ){
                                                     echo('<tr>
                                                            <td><b>AtividadeID ['.$atividade.']</b></td>
                                                            <td>'.$value->AtividadeCod.'</td>
                                                      </tr>
                                                      <tr>
                                                            <td><b>Atividade</b></td>
                                                            <td>'.$value->Atividade.'</td>
                                                      </tr>
                                                      <tr>
                                                            <td><b>Data da Inscricao</b></td>
                                                            <td>'.$value->data.'</td>
                                                      </tr>'); 
                                               // }
                                          } 
                                          @endphp 
                                     </tbody>
                                    </table>
                        
                                    </div>
                   </div>
            </div>    
</body>
</html>


