<!DOCTYPE html>
<html lang="pt-BR" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        Sistema de Inscrição da Week-IT
    </title>
    <meta name="description" content="Sistema de gerenciamento Cootraseoba">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/demo/demo5/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
    
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118415870-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-118415870-2');
    </script>

</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- begin::Header -->
    <header class="m-grid__item		m-header "  data-minimize="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
        <div class="m-header__top">
            <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <!-- begin::Brand -->
                    <div class="m-stack__item m-brand">
                        <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                            <div class="m-stack__item m-stack__item--middle m-brand__logo">
                            <a href="{{route('home')}}" class="m-brand__logo-wrapper">
                                    <img alt="" src="{{ asset('img/logo-sys.png')}}" style="max-width: 210px;"/>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end::Brand -->
                    <!-- begin::Topbar -->
                    <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                        <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                            <div class="m-stack__item m-topbar__nav-wrapper">
                                <ul class="m-topbar__nav m-nav m-nav--inline">
                                    <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                                        <div class="col mt-4">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" style="background: #d31717;border: 1px solid #b61010;"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ Auth::user()->email }}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/inscricao/participante/edit/{{ Auth::user()->id }}">
                                                        <i class="la la-edit"></i>
                                                        Alterar cadastro
                                                    </a>
                                                    <a class="dropdown-item" href="{{route('logout')}}">
                                                        <i class="la la-close"></i>
                                                        Sair
                                                    </a>                                                   
                                                </div>                                                
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end::Topbar -->
                </div>
            </div>
        </div>
        <div class="m-header__bottom">
            <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <!-- begin::Horizontal Menu -->
                    @include('layouts.partial.m-aside-menu-'. Auth::user()->tipo)
                    <!-- end::Horizontal Menu -->
                </div>
            </div>
        </div>
    </header>
    <!-- end::Header -->
    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive m-container--xxl m-page__container m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            @yield('content')
        </div>
    </div>
    <!-- end::Body -->
    <!-- begin::Footer -->
    <footer class="m-grid__item m-footer ">
        <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
            <div class="m-footer__wrapper">
                <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                    <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
						<span class="m-footer__copyright">
							{{date('Y')}} &copy; Av. Sérgio Vieira de Mello, 3150 - Zabelê, Vitória da Conquista - BA
						</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end::Footer -->
</div>
<!-- end:: Page -->

<!-- begin::Scroll Top -->
<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
    <i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->
<!-- begin::Quick Nav -->

<!-- begin::Quick Nav -->
<!--begin::Base Scripts -->
<script src="{{ asset('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/demo/demo5/base/scripts.bundle.js') }}" type="text/javascript"></script>


<!--end::Base Scripts -->
<!--begin::Page Resources -->
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/bootstrap-notify.js') }}" type="text/javascript"></script>
<!--end::Page Resources -->
@yield('scripts')

<script type="text/javascript">

    var Inputmask={
        init:function(){
            $(".m_inputmask_data").inputmask("dd/mm/yyyy",{autoUnmask:!0}),$(".m_inputmask_datetime").inputmask("ddmm/yyyy",{placeholder:"*"}),$(".m_inputmask_3").inputmask("mask",{mask:"(999) 999-9999"}),$(".m_inputmask_4").inputmask({mask:"99-9999999",placeholder:""}),$(".m_inputmask_5").inputmask({mask:"9",repeat:10,greedy:!1}),$(".m_inputmask_6").inputmask("decimal",{rightAlignNumerics:!1}),$(".m_inputmask_7").inputmask("â‚¬ 999.999.999,99",{numericInput:!0}),$(".m_inputmask_8").inputmask({mask:"999.999.999.999"}),$("#m_inputmask_9").inputmask({mask:"*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",greedy:!1,onBeforePaste:function(m,a){return(m=m.toLowerCase()).replace("mailto:","")},definitions:{"*":{validator:"[0-9A-Za-z!#$%&'*+/=?^_`{|}~-]",cardinality:1,casing:"lower"}}})}};jQuery(document).ready(function(){Inputmask.init()});
</script>
</body>
<!-- end::Body -->
</html>
