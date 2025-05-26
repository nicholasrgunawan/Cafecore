<!-- resources/views/costing.blade.php -->
@include('layouts.header')
@section('title', 'Costing')

<section class="content">
    <div class="container mt-4">

        <!-- Placeholder for toolbar -->
        <div id="addCostingWrapper" class="mb-2"></div>

        <table id="costingTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Dry Good</th>
                    <th>Veggies</th>
                    <th>Meat</th>
                    <th>Fruit</th>
                    <th>Total per hari</th>
                    <th>Control harus 0</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap_barang_masuk as $data)
                    <tr>
                        <td>{{ $data->created_at }}</td>
                        <td>Rp. {{ number_format($data->dry_good, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($data->veggies, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($data->meat, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($data->fruit, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($data->total, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($data->control, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>

<!-- ✅ DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- ✅ Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<!-- ✅ DataTable Init + Toolbar Button Injection -->
<script>
    $(document).ready(function() {
        const addButton = `
        <button class="btn btn-secondary btn-sm">Print laporan</button>`;

        const table = $('#costingTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lfr>tip',
            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });

        // Append Add Costing button to the right of the search
        $('.dataTables_filter').after(`<div class="add-costing-wrapper ms-2">${addButton}</div>`);
    });
</script>

<!-- ✅ Optional styling -->
<style>
    /* Wrap entries + search together and keep aligned */
    .dataTables_wrapper>.d-flex {
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    .dataTables_length,
    .dataTables_filter {
        margin: 0;
    }

    .dataTables_length {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .dataTables_filter {
        margin-left: 1rem;
    }

    /* Add Costing floats right */
    .add-costing-wrapper {
        margin-left: auto;
    }

    .add-costing-wrapper button i {
        margin-right: 5px;
    }

    #costingTable tbody tr:nth-child(even) {
        background-color: #c7e1df;
    }

    #costingTable tbody tr:hover {
        background-color: #dbe9e8;
    }
</style>
