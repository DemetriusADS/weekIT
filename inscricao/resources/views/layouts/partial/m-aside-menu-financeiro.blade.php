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
            
            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ request()->is('atividade/*') ? ' m-menu__item--active' : '' }}"  m-menu-submenu-toggle="click" aria-haspopup="true">
                <a  href="#" class="m-menu__link m-menu__toggle">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                        @php 
                            echo "Edição: ". DB::table('participante')
                                        ->join('evento','evento.id','=','participante.edicao_ativa')
                                        ->select('evento.ano')->where('participante.id','=',Auth::user()->id)->get()[0]->ano;
                        @endphp
                    </span>
                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">
                        @php
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
                        @endphp
                    </ul>
                </div>
            </li>            
        </ul>
    </div>
</div>


