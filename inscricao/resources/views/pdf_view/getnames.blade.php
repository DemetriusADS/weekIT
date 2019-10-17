@extends('layouts.app')
@section('content')
<div class="m-content">
      <hr>                     
              <div class="info">
                        <h4>Gerar Crachás</h4>
              <button class="btn btn-outline-danger float-right ml-5"  type="buttin"><a style="text-decoration: none; color: black" class='danger' href="{{route('gerarpdf')}}">Gerar Todos</button>
      @php     
          $getParticipantes = DB::table('participante')
          ->join('inscricao_eventos','inscricao_eventos.participante_id','=','participante.id')
          ->select(
                'participante.id as id',
                'participante.nome as nome',
          )->where('inscricao_eventos.evento_id','=',Auth::user()->edicao_ativa)
          ->get();
         // dd($getParticipantes);
      @endphp
       <form class="form-inline" action="{{route('shownomestopdf', ['nome'=>csrf_field()])}}" method="POST">
                  <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Pesquisar</span>
                              </div>
                    <input class="form-control search-field" type="text" name="nome" placeholder="Nome">
                    <div class="input-group-append">
                              <button class="btn btn-outline-success" type="submit" id="button-addon2">Buscar</button>
                            </div>
                           
                  </div>
                  
                </form> 
                               
            @php    
            if(session('list')) {
             
                        $nomes = session('list');
                        if($nomes == '[]'){
                        echo('<div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>ops!</strong> Participante não encontrado..
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                              </div>');
                        }else{
                        $nomesArray = json_decode($nomes);
                        //dd($nomesArray);
                        echo( Form::open(array('route' => ['pdf.store', 'id[]'])));
                        foreach ($nomesArray as $key => $item){ 
                              echo('<div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <div class="input-group-text">');
                              echo (Form::checkbox('id[]', $item->id));
                              echo('      </div>
                                                </div>
                                       <label class="form-control" for="id[]">'.$item->nome.'</label>
                                    </div>');
                        }
                        echo(Form::submit('Adicionar', ['class' => 'btn btn-outline-success']));
                        echo(Form::close());  
                        }}
               @endphp 
                 
      <form action="{{route('pdf.store', ['id'=>csrf_field()])}}" method="POST">
                  <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Todos os Participantes</label>
                              </div>
                              <select class="custom-select" id="inputGroupSelect01" name="id">                                    
                                    @foreach ($getParticipantes as $key=> $item)
                                          <option value="{{$item->id}}">{{$item->nome}} | {{$item->id}}</option>
                                    @endforeach
                              </select>
                              <div class="input-group-append">
                                          <button class="btn btn-outline-success" type="submit">Adicionar</button>
                                        </div>
                                      </div>
                        </div>
</form>

      </p>     

            @php
           
            $getCompare = DB::table('participante')
          ->join('inscricao_eventos','inscricao_eventos.participante_id','=','participante.id')
          ->select(
                'participante.id as id',
                'participante.nome as nome',
                'participante.edicao_ativa as evento'
          )->where('inscricao_eventos.evento_id','=',Auth::user()->edicao_ativa)
          ->get();
                  if(isset($getList)){
                        echo('<table class="table table-borderless ">');
                                    //dd($getList);
                        foreach ($getList as $key => $idSelecionado) {  
                             //dd($idSelecionado);                          
                             foreach ($getCompare as $value) {   
                                   //dd($value);                                
                                    if($idSelecionado == $value->id){
                                          
                                          echo('<tr>
                                                      <td class="align-content-center">
                                                           <div class="input-group mb-3">
                                                            <h4 style="line-height: 150%">'.$value->nome.'</h4> 
                                                            <form class="form-inline" action="/pdfdelete/'.$idSelecionado.'" method="get">
                                                                  <div class="input-group-append">
                                                                              <button class="btn btn-danger" style="margin-left: 5px" type="Submit">-</button>
                                                                  </div>
                                                            </form>
                                                           </div>
                                                      </td>
                                                </tr>');
                                    }
                              }                              
                        }
                        
                 echo('</table>
                  <form action="'.route('gerarpdf', '1').'" method="get">
                  <button class="btn btn-warning" type="submit">Gerar PDF</button>
      </form>');
}
                  
            @endphp
              </div>
            </div>
@endsection
    
            
