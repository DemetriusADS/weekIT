<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Document</title>
      <style>
            table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }
            
            td, th {
              border: 1px solid black;
              text-align: left;
              padding: 8px;
              text-transform: uppercase;
            }
            span{
                  text-transform: uppercase;
            }
            </style>
</head>
<body>
      <table style="width:1024px;">
                  <div style="width: 1000px; margin: auto"> 
                              <img style="max-width: 300px" src="{{ asset('img/MARCA_IFBA.png')}}" class="p-3 img-fluid" />
                              <img style="max-width: 200px; float:right; margin-right: 150px" src="{{ asset('img/logoWeek.png')}}" class="p-3 img-fluid" />
                              </div>
                              <h2 style="text-align: center">Lista de Presença</h2>
            
                  @foreach($atividadeInfo as $key => $value)
                  <tr>  
                        <td><span style="font-weight:bold">{{$value->tipo}}: </span> {{$value->titulo}}</td>
                  </tr>
                  <tr>      
                        <td><span style="font-weight:bold">Palestrante: </span>{{$value->nomePalestrante}}</td>
                  </tr>
      </table>
      <table style="width:1024px">  
                  <tr>
                        <td>
                              <span style="font-weight:bold">Local: </span>{{$value->local}}
                        </td>
                        <td>
                              <span style="font-weight:bold">Data: </span>{{$value->data}}
                        </td>
                        <td>
                              <span style="font-weight:bold">Horário: </span>{{$value->horaInicio}} - {{$value->horaFim}}
                        </td>      
                  </tr>   
                  @endforeach
            
      </table>
      
      

      <table style="width:1024px; border=3px solid black">
            
           <tr style="background: #CCC">
                  <th style="width: 20px">#</th>
                  <th style="width: 250px; text-align: center">Nome</th>
                  <th style=" text-align: center">Assinatura</th>
                </tr>
               @php
               $i = 1;
               foreach($atividadeLista as $key => $value){
               if($value->status != 'andamento' || $value->status !='cancelado'){
               echo(" <tr>
                  <td>$i</td>
                  <td>$value->nomeAluno</td>
                  <td style='width: 300px'></td>
                </tr>");
                $i++;
            }}
                
               @endphp
      </table>
</body>
</html>