<!DOCTYPE html>
<html lang="pt-BR" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
         Sistema de Inscrição da Week-IT
    </title>
    <meta name="description" content="Latest updates and statistic charts">
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
    <link href="{{ asset('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->
     <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" />
     <style>
         body{
             min-width: 400px;
         }
         #m_login_signin_submit:hover{
             background-color: #f25a29 !important;
             border: 1px solid #f25a29 !important;
         }
     </style>
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url(../../../assets/app/media/img//bg/bg-3.jpg);">
        <div class="m-grid__item m-grid__item--fluid	m-login__wrapper" style="padding: 6% 2rem 1rem 2rem">
            <div class="m-login__container">
                <div class="m-login__logo mb-0">
                    <a href="/">
                        <img src="{{ asset('img/logoWeek.png') }}" class="w-auto" style="max-width: 350px;">
                    </a>
                </div>
                <div class="m-login__signin">

                    @include('layouts.partial.message')

                    <form class="m-login__form m-form mt-0" method="POST"  action="{{ route('login') }}">

                        {{ csrf_field() }}

                        <div class="form-group m-form__group">
                            <input class="form-control m-input {{ $errors->has('email') ? ' is-invalid' : '' }}"   type="text" placeholder="Email" name="email" autocomplete="off" value="{{ old('email') }}" required autofocus style="border-radius: 4px; background: #ecebec;">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>


                        <div class="form-group m-form__group">
                            <input class="form-control m-input m-login__form-input--last {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" placeholder="Senha" name="password" style="border-radius: 4px; background: #ecebec;">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="row m-login__form-sub">
                            <div class="col m--align-left m-login__form-left">

                            </div>
                            <div class="col m--align-right m-login__form-right">
                                <a href="recuperar-senha" id="m_login_forget_password" class="m-link">Esqueceu a senha ?</a>
                                <a href="{{ route('cadastrar') }}" id="m_login_cadastro" class="m-link">Cadastre-se</a>
                            </div>                          
                        </div>
                        <div class="m-login__form-action">
                            <button id="m_login_signin_submit" type="submit" class="btn btn-primary m-btn  m-btn--custom m-btn--air m-login__btn m-login__btn--primary btn-block" style="background: #00710a;
                               border: 1px solid #005307;">
                                Entrar
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Page -->
<!--begin::Base Scripts -->
<script src="{{ asset('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Base Scripts -->
</body>
<!-- end::Body -->
</html>
