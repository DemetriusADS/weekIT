<table class="table">
      @php
      $atividades = DB::table('atividade')
            ->select(
                'atividade.titulo as titulo',
                'atividade.carga_horaria as CH',
                DB::raw("DATE_FORMAT(atividade.data_inicio,%d/%m/%Y) as dataIncio"),
                DB::raw("DATE_FORMAT(atividade.data_fim,%d/%m/%Y) as dataFim"),
                'atividade.tipo as tipo'
            )->where('atividade.evento_id', '=', Auth::user()->edicao_ativa)
            ->get();
            @endphp
      <thead>
      <tr>
          <th></th>
          <th>Nome da Atividade</th>
          <th>Carga Horaria</th>
          <th>Tipo</th>
          <th>Data Inicio</th>
          <th>Data Fim</th>
      </tr>
      </thead>          
      <tbody>
      @foreach ($atividades as $atividade)
          <tr>
              <td>{{ $atividade->titulo }}</td>
              <td>{{ $atividade->CH }}</td>
              <td>{{ $atividade->tipo }}</td>
              <td>{{ $atividade->dataInicio }}</td>
              <td>{{ $atividade->dataFim }}</td>
          </tr>
      @endforeach
      </tbody>
  </table>