@extends('Guest.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        {{-- <h1 class="h3 mb-0 text-gray-800">Hasil Survei Kepuasan Mahasiswa   </h1> --}}
    </div>

    <!-- Content Row -->
    <div class="row">

        {{-- Detail Profil --}}
        <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Data Perdata Umum
                        {{-- - Prodi --}}
                        {{-- {{ucfirst(App\Models\ProdiFakultas::where("id","=",
                        App\Models\UserAdmin::where('id_user','=',
                        Sentinel::getUser()->id
                        )->first()->id_prodi
                        )->first()->nama_program_studi)}} --}}
                    </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        {{-- <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addVirtualAkun">
                            <i class="fas fa-fw fa-plus-circle"></i> Tambah</button> --}}
                    </div>
                    <div class="">
                        <table class="table table-bordered table-striped table-hover kls-klh-datatable display" style="width:100%;">
                            <thead class="text-center text-xs">
                                <th>NO</th>
                                <th>ACT</th>
                                <th>NOMOR PERKARA</th>
                                <th>TANGGAL PENDAFTARAN</th>
                                <th>KATEGORI PERKARA</th>
                                <th>PARA PIHAK</th>
                                <th>WAKTU PROSES</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody class="text-sm">
                                <tr>
                                    <td align="center">1</td>
                                    <td align="center">
                                        <a href="https://feedermate.uniwa.ac.id/wr1-kelas-perkuliahan/detail/1f89dce9-807e-4ca6-ae42-9954ae593f68" target="_blank" class="ml-1 btn btn-sm btn-primary" title="Detail Kelas Perkuliahan"><i class="fa fa-info-circle"></i>&nbsp;Detail</a>
                                    </td>
                                    <td>1174/Pid.Sus/2025/PN Tng</td>
                                    <td align="center">14 Jul 2025</td>
                                    <td align="center">Narkotika</td>
                                    <td>
                                         Penggugat:<br>Aldo Taufiq Pratama, SH., MH.<br><br> Tergugat:<br>FITRIYANTO bin ARIFIN (Alm)
                                    </td>
                                    <td align="center">2 Hari</td>
                                    <td align="center">Sidang pertama</td>
                                </tr>
                                <tr>
                                    <td align="center">2</td>
                                    <td align="center">
                                        <a href="https://feedermate.uniwa.ac.id/wr1-kelas-perkuliahan/detail/1f89dce9-807e-4ca6-ae42-9954ae593f68" target="_blank" class="ml-1 btn btn-sm btn-primary" title="Detail Kelas Perkuliahan"><i class="fa fa-info-circle"></i>&nbsp;Detail</a>
                                    </td>
                                    <td>1172/Pid.Sus/2025/PN Tng</td>
                                    <td align="center">14 Jul 2025</td>
                                    <td align="center">Kesehatan</td>
                                    <td>
                                         Penggugat:<br>CUT WARDAH Z. A. SH.<br><br> Tergugat:<br>MUKSALMINA Als MUKSAL Bin TARMIZI
                                    </td>
                                    <td align="center">2 Hari</td>
                                    <td align="center">Sidang pertama</td>
                                </tr>
                                <tr>
                                    <td align="center">3</td>
                                    <td align="center">
                                        <a href="https://feedermate.uniwa.ac.id/wr1-kelas-perkuliahan/detail/1f89dce9-807e-4ca6-ae42-9954ae593f68" target="_blank" class="ml-1 btn btn-sm btn-primary" title="Detail Kelas Perkuliahan"><i class="fa fa-info-circle"></i>&nbsp;Detail</a>
                                    </td>
                                    <td>1171/Pid.Sus/2025/PN Tng</td>
                                    <td align="center">14 Jul 2025</td>
                                    <td align="center">Kesehatan</td>
                                    <td>
                                         Penggugat:<br>PRISILIA ANDREIS,S.H<br><br> Tergugat:<br>FAIZIL Bin JUNAEDI
                                    </td>
                                    <td align="center">2 Hari</td>
                                    <td align="center">Sidang pertama</td>
                                </tr>
                                    
                            </tbody>
                        </table>
                        <input type="hidden" id="klsklhlist" name="klsklhlist"
                            value="{{url('wr1-kelas-perkuliahan/list')}}">
                    </div>
                </div>
            </div>
        </div>

    </div>

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