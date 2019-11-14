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
            <p> Estamos passando para avisar que o status do pagamento do minicurso <strong>{{ $value->titulo }}</strong> mudou para <strong>{{ $status }}</strong>.</p>
           
      @endforeach
      @if($status == 'pago' || $status == 'isento')
      <p>Segue as informações do minicurso</p>
      <table>
      @foreach ($atividade as $item => $value)
            <tr>
                  <td><strong>Minicurso</strong></td>
                  <td>{{ $value->titulo }}</td>
            </tr>
            <tr>
                  <td><strong>Data</strong></td>
                  <td>{{ $value->data_do_curso }}</td>
            </tr>
            <tr>
                  <td><strong>Horário</strong></td>
                  <td>{{ $value->horario }}</td>
            </tr>
            <tr>
                  <td><strong>Local</strong></td>
                  <td>{{ $value->local }}</td>
            </tr>
      @endforeach
      </table>
      @endif
      <br><br>
      <p>Atenciosamente,</p>
      <br>
      <p>Coordenação.</p>
<p>Semana de Tecnologia da Informação ({{ config('app.name') }})</p>
<p>IFBA Campus Vitória da Conquista</p>
</body>
</html>