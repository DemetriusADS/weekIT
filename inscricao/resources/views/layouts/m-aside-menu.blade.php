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
                        Início
                    </span>
                </a>
            </li>

            <li class="m-menu__item {{ request()->is('local/*') ? ' m-menu__item--active' : '' }}"  aria-haspopup="true">
                <a href="{{route('local.index')}}" class="m-menu__link">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                       Locals
                    </span>
                </a>
            </li>




            <li class="m-menu__item {{ request()->is('user/*') ? ' m-menu__item--active' : '' }}"  aria-haspopup="true">
                <a href="{{route('user.index')}}" class="m-menu__link">
                    <span class="m-menu__item-here"></span>
                    <span class="m-menu__link-text">
                       Usuários
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>
