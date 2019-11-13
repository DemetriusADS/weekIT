<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
       <!-- <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
            <li class="nav-item {{ request()->is('home') ? ' active' : '' }}" aria-haspopup="true">
                <a href="{{route('home')}}" class="nav-link">
                    <span class="nav-item-here"></span>
                    <span class="nav-link-text">
                        Inscrição
                    </span>
                </a>
            </li> -->
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 p-1" style="font-size: 16px">
            <li class="nav-item {{ request()->is('home') ? ' active' : '' }}" aria-haspopup="true">
                <a href="{{route('home')}}" class="nav-link">
                   
                        Inscrição
                </a>
            </li>            
            <li class="nav-item {{ request()->is('minhas-inscricoes') ? ' active' : '' }}" aria-haspopup="true">
                <a href="/exibir-inscricoes" class="nav-link">
                    <span class="nav-item-here"></span>
                    <span class="nav-link-text">
                        Minhas Inscrições
                    </span>
                </a>
            </li>          

            <li class="nav-item dropdown {{ request()->is('/*') ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @php 
                            echo "Edição: ". DB::table('participante')
                                        ->join('evento','evento.id','=','participante.edicao_ativa')
                                        ->select('evento.ano')->where('participante.id','=',Auth::user()->id)->get()[0]->ano;
                        @endphp
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @php
                            $att = DB::table('evento')->get();
                            $insc = DB::table('inscricao_eventos')->get();
                            foreach($att as $evento){                              
                                    foreach($insc as $inscrito){
                                        if($evento->id != 0 && $evento->id == $inscrito->evento_id && Auth::user()->id == $inscrito->participante_id){
                                            echo ' <a  href="'.route('eventochangeano',$evento->id).'" class="dropdown-item">
                                                                    <i class="m-menu__link-icon flaticon-list"></i>
                                                                                '.$evento->ano.'
                                                                </a>';
                                            break;
                                        }                               
                                    }                        
                                }
                        @endphp
                   
                </div>
            </li>

        </ul>
    </div>
</div>


