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
      font-size: 14px;
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
            <h2>
                {!! $title !!}
            </h2>
        </div>
        <table>
            <thead>
                <th style="width: 30%;text-align:center">Questions</th>
                <th style="text-align:center">Average</th>
                <th style="text-align:center">Standard Deviation</th> 
                <th style="text-align:center">Question Code</th>                 
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($prepared_data['data'] as $key =>$value)
                    <tr>
                        <td style="width: 40%;text-align:center">{{ $i+1 }}. {{ $value['question'][0]['question'] }}</td>
                        <td style="text-align:center">{{ $value['rata_rata'] }}</td>
                        <td style="text-align:center">{{ $value['standar_deviasi'] }}</td>
                        <td style="text-align:center">{{ $key }}</td>
                    </tr>
                    {{ $i++; }}
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</body>
</html>