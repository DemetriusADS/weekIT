<!DOCTYPE html>
<html lang="pt-br">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" screen='print'>
      <title>Crachás</title>
      <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
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
        <div class="float-left border" style='width: 300px; padding: 5px'>
           
                  @php
                        echo("<img style='align-content: center; float:right'  src='".$participante->QRCODE."'>");
                        echo('<h5 class="font-weight-bold" style=" margin-left:90px; padding:0">'.$participante->Nome_Cracha.'</h5>');
                        echo (DNS1D::getBarcodeHTML($participante->CPF, "UPCA"));
                        
                       // echo '<img src="' . DNS1D::getBarcodePNG("4", "C39+",3,33) . '" alt="barcode"   />';
                       
                        echo('<h6 style="margin-left: 50px">'.$participante->CPF.'</h6>');
                  @endphp
                  
          
           
                  @php
                       
                  @endphp
            
     
        </div>
        @if(($cont % 2 == 0))
        <div class="clearfix"></div>
      @endif  
      @php
          $cont++;
      @endphp
      @endforeach   


</body>
</html>
