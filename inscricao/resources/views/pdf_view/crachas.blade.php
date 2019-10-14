<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" screen='print'>
      <title>Crachás</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body> 
      @foreach ($participantesData as $participante)
      <div style="margin-left: 20%">
                  @php
                        echo("<img style='margin-left: 7%' src='".$participante->QRCODE."'>");
                        echo('<h3 style="align-content: center; margin-left:8%">'.$participante->Nome_Cracha.'</h3>');
                        echo (DNS1D::getBarcodeHTML($participante->CPF, "CODABAR"));                        
                  @endphp
                  </div>
      @endforeach    

</body>
</html>
