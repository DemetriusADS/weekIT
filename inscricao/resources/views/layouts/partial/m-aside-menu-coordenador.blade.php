 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
         <ul class="navbar-nav mr-auto mt-2 mt-lg-0 p-1" style="font-size: 16px">
            <li class="nav-item {{ request()->is('home') ? ' active' : '' }}">
                 <a class="nav-link" href="{{route('home')}}">
                    Dashboard
                </a>
             </li>
            <li class="nav-item {{ request()->is('fazer-inscricao') ? ' active' : '' }}">
                <a class="nav-link " href="/exibir-inscricoes">Minhas Inscrições</a>
            </li>
                  <li class="nav-item dropdown {{ request()->is('evento/*') ? ' active' : '' }}">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="{{ route('evento.index') }}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Evento
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <a class="dropdown-item" href="{{ route('evento.create') }}">
                                <i class="m-menu__link-icon flaticon-add"></i>Cadastrar evento
                            </a>
                            <a class="dropdown-item" href="{{ route('evento.index') }}">
                                    <i class="m-menu__link-icon flaticon-list"></i>
                                    Listar eventos
                                </a>
                        </div>
                  </li>
             <li class="nav-item dropdown {{ request()->is('inscricao/*') ? ' active' : '' }}">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="{{route('inscricao.index')}}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Inscrição
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <a class="dropdown-item" href="{{route('inscricao.create')}}">
                            <i class="m-menu__link-icon flaticon-add"></i>
                            Cadastrar inscrição</a>
                          <a class="dropdown-item" href="{{route('inscricao.index')}}">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                Listar inscrições
                        </a>
                        </div>
             </li>
            <li class="nav-item dropdown {{ request()->is('participante/*') ? ' active' : '' }}">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="{{route('participante.index')}}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Participante
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{route('participante.create')}}">
                            <i class="m-menu__link-icon flaticon-add"></i>
                            Cadastrar participante
                    </a>
                    <a class="dropdown-item" href="{{route('participante.index')}}">
                            <i class="m-menu__link-icon flaticon-list"></i>
                            Listar participantes
                    </a>
                    <a class="dropdown-item" href="{{route('participantes-planilha')}}">
                            <i class="m-menu__link-icon flaticon-edit"></i>
                            Gerar planilha
                    </a>
                    <a class="dropdown-item" href="{{route('pdf.index')}}">
                            <i class="m-menu__link-icon flaticon-add"></i>
                            Gerar Crachás
                    </a>
            </div>
            </li>
            <li class="nav-item dropdown {{ request()->is('atividade/*') ? ' active' : '' }}">
                 <a href='{{ route('atividade.index') }}' class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Atividade
                </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{route('atividade.create')}}">
                            <i class="m-menu__link-icon flaticon-add"></i>
                          Cadastrar atividade
                        </a>
                        <a  href="{{route('atividade.index')}}" class="dropdown-item" >
                            <i class="m-menu__link-icon flaticon-list"></i>
                            Listar atividades
                        </a>
                        <a  href="{{route('gerenciar-monitor')}}" class="dropdown-item">
                            <i class="m-menu__link-icon flaticon-settings"></i>
                           Gerenciar monitor
                        </a>
                        <a  href="{{route('gerenciar-presenca')}}" class="dropdown-item">
                            <i class="m-menu__link-icon flaticon-edit"></i>
                           Lançar presenças
                        </a>
                        <a  href="{{route('atividades-planilha')}}" class="dropdown-item">
                            <i class="m-menu__link-icon flaticon-edit"></i>
                           Gerar Planilha</a>
                   <a  href="{{route('gerenciar-sorteio')}}" class="dropdown-item">
                            <i class="m-menu__link-icon flaticon-chat"></i>
                           Sorteio
                        </a>
                                </div>
                              </li>
                              <li class="nav-item dropdown {{ request()->is('palestrante/*') ? ' active' : '' }}">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Colaborador
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <a  href="{{route('palestrante.create')}}" class="dropdown-item">
                                                    <i class="m-menu__link-icon flaticon-add"></i>
                                                   Cadastrar palestrante
                                                </a>
                                            <a  href="{{route('monitor.create')}}" class="dropdown-item">
                                                    <i class="m-menu__link-icon flaticon-add"></i>
                                                   Cadastrar monitor
                                            </a>
                                            <a  href="{{route('palestrante.index')}}" class="dropdown-item">
                                                    <i class="m-menu__link-icon flaticon-list"></i>
                                                   Listar palestrantes
                                                </a>
                                           <a  href="{{route('monitor.index')}}" class="dropdown-item">
                                                    <i class="m-menu__link-icon flaticon-list"></i>
                                                    Listar monitores
                                                </a>
                                    </div>
                                  </li>
                                  <li class="nav-item dropdown {{ request()->is('local/*') ? ' active' : '' }}">
                                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="{{route('local.index')}}" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Local
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <a  href="{{route('local.create')}}" class="dropdown-item">
                                                        <i class="m-menu__link-icon flaticon-add"></i>
                                                       Cadastrar local
                                                               </a>
                                               <a  href="{{route('local.index')}}" class="dropdown-item">
                                                        <i class="m-menu__link-icon flaticon-list"></i>
                                                       Listar locais
                                                               </a>
                                        </div>
                                      </li>
                                      <li class="nav-item dropdown {{ request()->is('/*') ? ' active' : '' }}">
                                            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @php 
                                                    echo "Edição: ". DB::table('participante') //ERRO DA EDIÇÃO DO EVENTO
                                                                ->join('evento','evento.id','=','participante.edicao_ativa')
                                                                ->select('evento.ano')
                                                                ->where('participante.id','=',Auth::user()->id)->get()[0]->ano;
                                                @endphp
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    @php
                                                    $att = DB::table('evento')->get();
                                                    foreach($att as $evento){  
                                                        if($evento->id != 0){
                                                        echo ' <a  href="'.route('eventochangeano',$evento->id).'" class="dropdown-item">
                                                                    <i class="m-menu__link-icon flaticon-list"></i>
                                                                                '.$evento->ano.'
                                                                </a>';
                                                        }                        
                                                    }
                                                @endphp
                                            </div>
                                          </li>
          </ul>
        </div>

