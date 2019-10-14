<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Informações do Participante</title>
      <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="{{ asset('js/plugins/bootstrap-notify.js') }}" type="text/javascript"></script>
      <link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('assets/demo/demo5/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
      <script>
                  WebFont.load({
                      google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                      active: function() {
                          sessionStorage.fonts = true;
                      }
                  });
              </script>
      <script>
             window.dataLayer = window.dataLayer || [];
             function gtag(){dataLayer.push(arguments);}
             gtag('js', new Date());
                  
             gtag('config', 'UA-118415870-2');
       </script>
</head>
<body>
      @if($userLoggedID->tipo == 'coordenador')
            <h1>Painel do Coordenador</h1>
            <div class="m-portlet__body">                          
                        <div id="content-select-tppresenca">
                                    <br>
                        <form action="{{route('coordernador-setpresenca', ['participanteID' =>csrf_field(), 'atividadeID' => csrf_field()])}}" method="POST">
                                    <input type="hidden" name="participante_id" value='{{$participanteId}}'/>
                                    <select class="form-control col-2" name="atividadeID">
                                                @foreach ($data as $atividade => $atividadeID)
                                                <option value={{$atividadeID->AtividadeID}}>{{$atividadeID->Atividade}}</option>
                                                @endforeach
                                           
                                          </select>
                                          <br>
                                          <div class="text-left">'
                                          <button class="btn btn-primary" id="bt_confirma" type="submit">Confirmar Presença</button>
                                          </div>
                              </form>
                                   
                              </div>                                            
                              @else
                                    <h1 class='alert-warning'>Access Denied</h1>
                              @endif
                        </body>
                        </html>
