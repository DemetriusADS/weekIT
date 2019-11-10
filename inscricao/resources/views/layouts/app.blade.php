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
    <style>
        body{
            min-width: 430px !important;
           
        }
    </style>

</head>
<!-- end::Head -->
<!-- end::Body -->
<body>
<!-- begin:: Page -->
                    <!-- begin::Brand -->
                    <div class="sticky-top bg-light" style="min-width: 430px;" >
                    <div class="m-1">
                            <a class="navbar-brand float-left"  href="{{route('home')}}">
                              <img class="float-left" src="{{ asset('img/logoWeek.png')}}" width="210" height="80" alt="">
                            </a>
                            <div class="btn-group float-right" style="margin-top: 30px">
                                    <button type="button" class="btn btn-danger dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ Auth::user()->email }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="participante/update/{{ Auth::user()->id }}">
                                            <i class="la la-edit"></i>
                                            Alterar cadastro
                                        </a>
                                        <a class="dropdown-item" href="{{route('logout')}}">
                                            <i class="la la-close"></i>
                                            Sair
                                        </a>                                                   
                                    </div>                                                
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <nav class="navbar navbar-expand-lg navbar-dark" style='background: darkgreen'>
                                    @include('layouts.partial.m-aside-menu-'. Auth::user()->tipo)
                                </nav>
                    </div>
                    
                    
                            
   
    <!-- end::Header -->
    <!-- begin::Body -->
    <div>
        <div class="m-3">
            @yield('content')
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

<!-- end:: Page -->

<!-- begin::Scroll Top -->
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
