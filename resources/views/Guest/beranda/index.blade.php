@extends('Guest.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        {{-- <h1 class="h3 mb-0 text-gray-800">Hasil Survei Kepuasan Mahasiswa   </h1> --}}
    </div>

    <!-- Content Row -->
    

</div>
@endsection
@section('script')
<script src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    // Filter Sort Function
    $('#toogFil').click(function(){ //you can give id or class name here for $('button')
        $(this).text(function(i,old){
            return old=='+' ?  '-' : '+';
        });
    });

    function tampilkanSurvei() {
        title = null;
        chart = null;
        filter = [];
        showLoader();        
        let datar = {};
        datar['_method']='POST';
        datar['_token']=$('._token').data('token');
        datar['id_prodi']= $('#prodi option:selected').val();
        datar['id_semester']= $('#semester option:selected').val();
        datar['tipe_survei']= $('#tipe_survei option:selected').val();
        if(datar['id_prodi']==-1){
            hideLoader();   
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Pilih program studi dahulu!",
                });
        }else if(datar['tipe_survei']==-1){
            hideLoader();   
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Pilih tipe survei dahulu!",
                });
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            $.ajax({
                type: 'post',
                url: $("#getDataSurvei").val(),
                data:datar,
                success: function(data) {
                    if (data.error==false) {
                        hideLoader();
                        // console.log(data);
                        if(data.data.length==0){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: "Belum ada responden",
                            });
                            $('#chart_title_div').html('');
                            drawChart([],null);
                        }else{
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil mengambil statistik.'
                            });
                            // mengupdate grafik
                            const prepTitle = `<h5>Statistik Survei Kepuasan Mahasiswa ${$('#prodi option:selected').text()} <br> Terhadap ${$('#tipe_survei option:selected').text()} TA ${$('#semester option:selected').text()} <br> n=${data.data['responden']}</h5>`;
                            const arrayData = data.data['data'];
                            let prepData = [];
                            Object.entries(arrayData).forEach(([key, value]) => {
                                const temp = [key,value['rata_rata'],(value['rata_rata']).toString(),value['lower'],value['upper']];
                                prepData.push(temp);
                            });
                            $('#chart_title_div').html(prepTitle);
                            drawChart(prepData,prepTitle);
                            filter = [$('#prodi option:selected').val(),$('#semester option:selected').val(),$('#tipe_survei option:selected').val(),
                            `<h5>Statistik Survei Kepuasan Mahasiswa ${$('#prodi option:selected').text()} <br> Terhadap ${$('#tipe_survei option:selected').text()} TA ${$('#semester option:selected').text()} <br> n=${data.data['responden']} <br> Rata-rata dan Standar Deviasi</h5>`];
                        }
                    }else{
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: JSON.stringify(data),
                        });
                        hideLoader();
                    }
                },
            });  
            hideLoader();

        }
    }
    
    let chart=null;
    let title=null;
    let filter = [];

    google.charts.load('current', {
      packages: ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    
    // ['A', 80, '80', 75, 85],
    //     ['B', 90, '90', 85, 95],
    //     ['C', 70, '70', 65, 75],
    //     ['D', 60, '60', 55, 65]
    const sampleData = [
      ];

    function drawChart(preparedData = sampleData,titleChart=null) {
      const data = new google.visualization.DataTable();

      // Columns: category, value, annotation, lower error, upper error
      data.addColumn('string', 'Category');
      data.addColumn('number', 'Value');
      data.addColumn({ type: 'string', role: 'annotation' }); // shows value label
      data.addColumn({ type: 'number', role: 'interval' }); // lower error
      data.addColumn({ type: 'number', role: 'interval' }); // upper error

      data.addRows(preparedData);

      const options = {
        title: titleChart,
        legend: 'none',
        titlePosition: 'none',
        intervals: { style: 'bars' }, // show error bars
        bar: { groupWidth: '50%' },
        annotations: {
          alwaysOutside: true,
          textStyle: {
            fontSize: 12,
            color: '#000'
          }
        }
      };

      title = titleChart;
      chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

    document.getElementById('surveiForm').addEventListener('submit', function (e) {
        e.preventDefault();
        if(title==null){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: "Belum ada grafik yang tersedia",
            });
            return;
        }
        const imageURI = chart.getImageURI(); // convert to base64 image
        document.getElementById('chartInputData').value = imageURI;
        document.getElementById('chartInputTitle').value = title;
        document.getElementById('chartFilter').value = JSON.stringify(filter);
        this.submit(); 
    });

    function showLoader() {
        $('.containerr').show();        
    }

    function hideLoader() {
        $('.containerr').hide();        
    }
  </script>
@endsection