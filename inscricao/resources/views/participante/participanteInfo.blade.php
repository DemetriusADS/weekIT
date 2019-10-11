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
            <div class="m-content">
                        <div class="m-portlet m-portlet--mobile">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            Detalhes do Participante                                            
                                        </h3> 
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
                                                      <td>{{$data->tipo}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>ID</b></td>
                                                       <td>{{$data->id}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>Nome</b></td>
                                                      <td>{{$data->Nome}}</td>
                                                </tr>
                                                <tr>
                                                <td><b>Curso</b></td>
                                                      <td>{{$data->Curso}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>Instituição</b></td>
                                                      <td>{{$data->Instituição}}</td>
                                                </tr>
                                                <tr>
                                                            <td><b>Evento</b></td>
                                                            <td>{{$data->Evento}}</td>
                                                </tr>
                                                <tr>
                                                      <td><b>EventoID</b></td>
                                                      <td>{{$data->EventoID}}</td>
                                                </tr>                                          
                                                <tr>
                                                      <td><b>EventoAno</b></td>
                                                      <td>{{$data->EventoAno}}</td>
                                                </tr>
                                                
                                          
                                          @foreach($data as $atividade => $value)
                                          @php
                                                if($atividade != 'Nome' && $atividade != 'tipo'&& $atividade != 'id'
                                                && $atividade !='EventoID' && $atividade !='Curso' && $atividade !='Instituição' && 
                                                $atividade !='EventoID' && $atividade !='EventoAno' ){
                                                     echo('<tr>
                                                            <td><b>'.$atividade.'</b></td>
                                                            <td>'.$value.'</td>
                                                      </tr>'); 
                                                }
                                                
                                          @endphp         
                                          @endforeach
                                          </tbody>
                                          </table>
                        
                                    </div>
                   </div>
            </div>                        
                        
</body>
</html>


