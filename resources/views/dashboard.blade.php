<!-- resources/views/dashboard.blade.php -->

@include('layouts.header')
@section('title', 'Dashboard')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background: white">
              <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total User</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              
            </div>
            
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background: white">
              <div class="inner">
               <h3>{{ $totalBarangMasuk }}</h3>
                <p>Total Barang Masuk</p>
              </div>
              <div class="icon">
                <i class="ion ion-cube"></i>
              </div>
              
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background: white">
              <div class="inner">
                <h3>{{ $totalBarangKeluar }}</h3>

                <p>Total Barang Keluar</p>
              </div>
              <div class="icon">
                <i class="ion ion-log-out"></i>
              </div>
              
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background: white">
              <div class="inner">
                <h3>
  Rp {{ number_format($totalSales, 0, ',', '.') }}

  @if ($salesTrend === 'up')
    <i class="fas fa-arrow-up" style="color: #008080;" title="Sales increased this month"></i>
  @elseif ($salesTrend === 'down')
    <i class="fas fa-arrow-down text-danger" title="Sales decreased this month"></i>
  @else
    <i class="fas fa-minus text-muted" title="Sales unchanged"></i>
  @endif
</h3>


                <p>Total Penjualan</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
      <section class="col-lg-12 connectedSortable">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-pie mr-1"></i>
              Sales
            </h3>
            <div class="card-tools">
              <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                </li>
                
              </ul>
            </div>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content p-0">
              <!-- Morris chart - Sales -->
              <div class="chart tab-pane active" id="revenue-chart"
                   style="position: relative; height: 300px;">
                  <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
               </div>
              
            </div>
          </div><!-- /.card-body -->
        </div>
      </section>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('layouts.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
  const monthlyLabels = {!! json_encode($monthlySales->pluck('month')) !!};
  const monthlyData = {!! json_encode($monthlySales->pluck('total')) !!};

  

  const ctx = document.getElementById('revenue-chart-canvas').getContext('2d');

const revenueChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: monthlyLabels,
    datasets: [{
      label: 'Sales',
      data: monthlyData,
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 2,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});



</script>


