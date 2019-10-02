<div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
    <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
        <i class="la la-close"></i>
    </button>
    <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
        <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
            <li class="m-menu__item {{ request()->is('home') ? ' m-menu__item--active' : '' }}" aria-haspopup="true">
                <a href="{{route('home')}}" class="m-menu__link">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Dashboard
                    </span>
                </a>
            </li>            
            <li class="m-menu__item {{ request()->is('fazer-inscricao') ? ' m-menu__item--active' : '' }}" aria-haspopup="true">
                <a href="/inscricao/fazer-inscricao" class="m-menu__link">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Minhas Inscrições
                    </span>
                </a>
            </li>            
            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('evento/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="{{route('evento.index')}}" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Evento
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('evento.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar evento
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('evento.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar eventos
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>            
            
            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('inscricao/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="{{route('inscricao.index')}}" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Inscrição
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('inscricao.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar inscrição
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('inscricao.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar inscrições
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('participante/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="{{route('participante.index')}}" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Participante
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('participante.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar participante
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('participante.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar participantes
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('atividade/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="{{route('atividade.index')}}" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Atividade
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('atividade.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar atividade
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('atividade.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar atividades
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('gerenciar-monitor')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-settings"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Gerenciar monitor
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('gerenciar-presenca')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-edit"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Lançar presenças
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('gerenciar-sorteio')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-chat"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Sorteio
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>                         
                    </ul>
                </div>
            </li>

            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('palestrante/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="#" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Colaborador
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('palestrante.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar palestrante
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('monitor.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar monitor
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>                        
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('palestrante.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar palestrantes
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('monitor.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar monitores
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>                        
                    </ul>
                </div>
            </li>          

            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('local/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="{{route('local.index')}}" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        Local
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('local.create')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-add"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Cadastrar local
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{route('local.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-list"></i>
                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">
                                        <span class="m-menu__link-text">
                                            Listar locais
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="#" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        @php 
                            echo "Edição: ". DB::table('participante') //ERRO DA EDIÇÃO DO EVENTO
                                        ->join('evento','evento.id','=','participante.edicao_ativa')
                                        ->select('evento.ano')
                                        ->where('participante.id','=',Auth::user()->id)->get()[0]->ano;
                        @endphp
                    </span>
                   <!-- <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i> -->
                </a>
       <!--         <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        COLOCAR_ARROBAphp/*
                            $att = DB::table('evento')->get();
                            foreach($att as $evento){
                                echo '<li class="m-menu__item" aria-haspopup="true">
                                        <a  href="/inscricao/eventochangeano/'.$evento->id.'" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-list"></i>
                                            <span class="m-menu__link-title">
                                                <span class="m-menu__link-wrap">
                                                    <span class="m-menu__link-text">
                                                        '.$evento->ano.'
                                                     </span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>';                        
                            }
                        COLOCAR_ARROBAendphp
                    </ul>
                </div>-->
            </li>            
        </ul>
    </div>
</div>


