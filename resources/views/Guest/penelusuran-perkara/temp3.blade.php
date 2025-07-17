<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hasil Survei</title>
</head>
<style>
    table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      font-size: 5px;
    }
  
    thead {
      background-color: #f2f2f2;
    }
  
    th, td {
      padding: 8px 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
  
    tr:nth-child(even) {
      background-color: #fafafa;
    }
  
    tr:hover {
      background-color: #f1f1f1;
    }
  </style>
<body>
    <div class="center" style="border: 0.3px solid gray">
        @if($prepared_data)
        <div class="center" style="text-align: center;border:1px solid black;margin-bottom:10px">
          DETAIL  
          <h2>
                {!! $title !!}
            </h2>
        </div>
        <table>
            <thead>
                <th style="text-align:center">No</th>
                <th style="text-align:center">NIM</th>
                <th style="width: 30%;text-align:center">Nama</th> 
                <?php $y=0; ?>
                @foreach ($QsCode as $val )
                <th style="text-align:center">{{ $y+1 }}. {{ $val['qc'] }}</th>                   
                {{ $y++; }}
                @endforeach 
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($raw as $val)
                <tr>
                  <td style="text-align:center">{{ $i+1 }}</td>
                  <td>{{ $val['user']['username'] }}</td>
                  <td>{{ $val['user']['nama'] }}</td>
                  @foreach ($QsCode as $v )
                  <td style="text-align:center">{{ $val[$v['kode']] }}</td>
                  @endforeach 
                    {{ $i++; }}
                </tr>
                @endforeach
                <tr>
                  <td colspan="3" style="text-align: right">Question Code</td>
                  @foreach ($QsCode as $v )
                  <td style="text-align:center">{{ $v['kode'] }}</td>
                  @endforeach 
                </tr>
                <tr>
                  <td colspan="3" style="text-align: right">Rata-rata</td>
                  @foreach ($prepared_data['data'] as $v )
                  <td style="text-align:center">{{ $v['rata_rata'] }}</td>
                  @endforeach 
                </tr>
                <tr>
                  <td colspan="3" style="text-align: right">Standar Deviasi</td>
                  @foreach ($prepared_data['data'] as $v )
                  <td style="text-align:center">{{ $v['standar_deviasi'] }}</td>
                  @endforeach  
                </tr>
            </tbody>
        </table>
        @endif
    </div>
</body>
</html>