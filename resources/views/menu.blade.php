<!-- resources/views/dashboard.blade.php -->

@include('layouts.header')
@section('title', 'Menu')

<section class="content">
    <div class="container mt-4">

        <!-- Placeholder for toolbar -->
        <div id="addMenuWrapper" class="mb-2"></div>

        <table id="menuTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID Menu</th>
                    <th>Menu</th>
                    <th>Merk</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#1</td>
                    <td>menu 1</td>
                    <td>Merk 1</td>
                    <td>Rp-</td>
                    <td>Kategori 1</td>
                    <td>-</td>
                    <td>Kg</td>
                    <td>
                        <a href="#" class="text-primary me-2"><i class="fas fa-pen-to-square"></i></a>
                        <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>#2</td>
                    <td>menu 2</td>
                    <td>Merk 2</td>
                    <td>Rp-</td>
                    <td>Kategori 2</td>
                    <td>-</td>
                    <td>Gr</td>
                    <td>
                        <a href="#" class="text-primary me-2"><i class="fas fa-pen-to-square"></i></a>
                        <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
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
        <button class="btn btn-primary" id="addMenuBtn">
            <i class="fas fa-plus"></i> Add Menu
        </button>`;

        const table = $('#menuTable').DataTable({
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

        // Append Add Menu button to the right of the search
        $('.dataTables_filter').after(`<div class="add-menu-wrapper ms-2">${addButton}</div>`);
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

    /* Add Menu floats right */
    .add-menu-wrapper {
        margin-left: auto;
    }

    .add-menu-wrapper button i {
        margin-right: 5px;
    }

    #menuTable tbody tr:nth-child(even) {
        background-color: #c7e1df;
    }

    #menuTable tbody tr:hover {
        background-color: #dbe9e8;
    }
</style>
