<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
      <title>Email</title>
</head>
<body>
      <h3>Olá {{ $nome }}</h3>
      <br>
      @foreach ($atividade as $item => $value)
      @if($value->tipo =='minicurso')
            <p> Estamos passando para avisar que o minicurso <strong>{{ $value->identificador}} - {{ $value->titulo }}</strong> está <strong style="color: red">CANCELADO</strong>.</p>
           @elseif($value->tipo =='palestra')
            <p> Estamos passando para avisar que a palestra <strong>{{ $value->identificador}} - {{ $value->titulo }}</strong> está <strong style="color: red">CANCELADO</strong>.</p>
            @else
            <p> Estamos passando para avisar que a mesa redonda <strong>{{ $value->identificador}} - {{ $value->titulo }}</strong> está <strong style="color: red">CANCELADO</strong>.</p>
      @endif
      @endforeach
      
      <br>
      <p>Se você desejar, verifique a disponibilidade de outra atividade ou procure um monitor ou coordenador.</p>
      <p>Pedimos desculpas pelo transtorno.</p>
      <br><br>
      <p>Atenciosamente,</p>
      <br>
      <p>Coordenação.</p>
<p>Semana de Tecnologia da Informação ({{ config('app.name') }})</p>
<p>IFBA Campus Vitória da Conquista</p>
</body>
</html>