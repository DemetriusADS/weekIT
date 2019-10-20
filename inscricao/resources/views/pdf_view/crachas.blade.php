<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" screen='print'>
      <title>Crachás</title>
      <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body> 
            <div class="container">
            <div class="row">
      @foreach ($participantesData as $participante)
      @php      //Retornar o primeiro e ultimo nomes do participante para o crachá
            $partes = explode(' ', $participante->Nome_Cracha);
            $primeiroNome = array_shift($partes);
            $ultimoNome = array_pop($partes);
            $participante->Nome_Cracha = $primeiroNome." ".$ultimoNome;
            $participante->Nome_Cracha = mb_convert_case($participante->Nome_Cracha, MB_CASE_TITLE, 'UTF-8');
      @endphp
      <div class="border" style="float: left; padding: 8px; margin: 2px; height: 200px; width: 220px">
      <div class="text-center">
                  
                        @php
                        echo("<img style='align-content: center; margin-left:5px;'  src='".$participante->QRCODE."'>");
                        echo('<h5 class="font-weight-bold" style=" margin-left:7%; padding:0">'.$participante->Nome_Cracha.'</h5>');
                        echo ("<div style='align-content: center;margin-left:3%'>".DNS1D::getBarcodeHTML($participante->CPF, "UPCA")."</div>");
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
