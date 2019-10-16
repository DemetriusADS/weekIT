<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Document</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
      @php     
          $getParticipantes = DB::table('participante')
          ->join('inscricao_eventos','inscricao_eventos.participante_id','=','participante.id')
          ->select(
                'participante.id as id',
                'participante.nome as nome',
                'participante.edicao_ativa as evento'
          )->where('participante.edicao_ativa','=',Auth::user()->edicao_ativa)
          ->get();
         // dd($getParticipantes);
      @endphp
      
      <form class="form-inline" action="{{route('pdf.store', ['id'=>csrf_field()])}}" method="POST">
      <select class="custom-select" name="id">
             @foreach ($getParticipantes as $key=> $item)
                  <option value="{{$item->id}}">{{$item->nome}} | {{$item->id}}</option>  
           
            @endforeach
      </select>
      <button class="btn btn-primary" type="submit"> Adicionar </button>
</form>

      </p>     

            @php
           
            $getCompare = DB::table('participante')
          ->join('inscricao_eventos','inscricao_eventos.participante_id','=','participante.id')
          ->select(
                'participante.id as id',
                'participante.nome as nome',
                'participante.edicao_ativa as evento'
          )->where('participante.edicao_ativa','=',Auth::user()->edicao_ativa)
          ->get();
                  if(isset($getList)){
                        echo('<table class="table table-bordered table-hover">');
                        
                        foreach ($getList as $key => $idSelecionado) {                            
                             foreach ($getCompare as $value) {   
                                   //dd($value);                                
                                    if($idSelecionado['id'] == $value->id){
                                          echo('<tr class="d-inline-flex">');
                                         echo('<td><h5>'.$value->nome.'</h5> 
                                                <form class="form-inline" action="/pdfdelete/'.$idSelecionado['id'].'" method="get">
                                                 <button class="btn btn-danger " type="Submit">Remover</button>
                                                      </form>
                                          </td>');
                                         echo('</tr>');
                                          break;
                                    }
                              }                              
                        }
                        
                 echo('</table>
                  <form action="'.route('gerarpdf', '1').'" method="get">
                  <button class="btn btn-warning" type="submit">Gerar PDF</button>
      </form>');
                  }
            @endphp
           
</body>
</html>