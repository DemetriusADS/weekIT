<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" screen='print'>
      <title>Crachás</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body> 
            <div class="container">
            <div class="row">
      @foreach ($participantesData as $participante)
      <div class="border" style="float: left; padding: 8px; margin: 2px">
      <div class="text-center">
                  
                        @php
                        echo("<img  src='".$participante->QRCODE."'>");
                        echo('<h4 style="align-content: center; margin-left:7%">'.$participante->Nome_Cracha.'</h4>');
                        echo ("<div>".DNS1D::getBarcodeHTML($participante->CPF, "C128")."</div>");
                        echo('<h6 style="margin-left:8%">'.$participante->CPF.'</h6>')                      
                  @endphp
                  
      </div>
      </div>
      </div>
                  
      @endforeach   
</div>
</div>
</div> 

</body>
</html>
