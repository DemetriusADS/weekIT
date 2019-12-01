<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" screen='print'>
      <title>Crachás</title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body> 
      @php
      $cont = 0;
      @endphp
      @foreach ($participantesData as $participante)
      @php      //Retornar o primeiro e ultimo nomes do participante para o crachá
            $partes = explode(' ', $participante->Nome_Cracha);
            $primeiroNome = array_shift($partes);
            $ultimoNome = array_pop($partes);
            $participante->Nome_Cracha = $primeiroNome." ".$ultimoNome;
            $participante->Nome_Cracha = mb_convert_case($participante->Nome_Cracha, MB_CASE_TITLE, 'UTF-8');
      @endphp
        <div class="float-left border text-center" style='width: 360px; padding: 5px'>
                  
                        <img style='align-content: center; float:right; margin-top: -5px; padding: 0;'  src='{{ $participante->QRCODE }}'>
                        <div class="text-center" style="width: 260px">
                              <h5 class="font-weight-bold text-center">{{ $participante->Nome_Cracha }}</h5>
                        </div>
                        <img style='align-content: center;margin-left:3%;' src="data:image/png;base64,{{DNS1D::getBarcodePNG( $participante->CPF, 'C128')}}" alt="barcode" />
                        <div class="text-center" style="width: 260px">
                              <h6>{{ $participante->CPF }}</h6>
                        </div>
                  
        </div>
      @if($cont % 2 == 0)
            <div class="clearfix"></div>
            @if($cont>1  && $cont % 13 == 0)
            <div style="page-break-after: always"></div>
            @endif
            
      @endif  
      @php
          $cont++;
            
      @endphp
      @endforeach   


</body>
</html>
