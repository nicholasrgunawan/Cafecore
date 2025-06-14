@include('layouts.header')
@section('title', 'Bahan')
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container mt-4">

            <!-- Placeholder for toolbar -->
            <div id="addBahanWrapper" class="mb-2"></div>

            <table id="bahanTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Bahan</th>
                        <th>Bahan</th>
                        <th>Merk</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bahans as $bahan)
                        <tr>
                            <td>{{ $bahan->id }}</td>
                            <td>{{ $bahan->bahan }}</td>
                            <td>{{ $bahan->merk }}</td>
                            <td>Rp. {{ number_format($bahan->harga, 2, ',', '.') }}</td>
                            <td>{{ $bahan->kategori }}</td>
                            <td>{{ $bahan->qty }}</td>
                            <td>{{ $bahan->unit }}</td>
                            <td>
                                <a href="{{ route('bahan.edit', $bahan->id) }}" class="text-primary me-2"><i
                                        class="fas fa-pen-to-square"></i></a>
                                <a href="#" class="text-danger delete-bahan" data-id="{{ $bahan->id }}"
                                    data-url="{{ route('bahan.destroy', $bahan->id) }}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>

                            </td>
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
    <!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ DataTable Init + SweetAlert2 Delete -->
<script>
    $(document).ready(function () {
        $(document).on('click', '.delete-bahan', function (e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete the item.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success || 'Bahan has been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to delete bahan.'
                            });
                        }
                    });
                }
            });
        });

        const addButton = `
            <a href="{{ route('add_bahan') }}" class="btn btn-primary" id="addBahanBtn">
                <i class="fas fa-plus"></i> Add Bahan
            </a>`;

        const table = $('#bahanTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lfr>tip',
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        $('.dataTables_filter').after(`<div class="add-bahan-wrapper ms-2">${addButton}</div>`);
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

        /* Add Bahan floats right */
        .add-bahan-wrapper {
            margin-left: auto;
        }

        .add-bahan-wrapper button i {
            margin-right: 5px;
        }

        #bahanTable tbody tr:nth-child(even) {
            background-color: #c7e1df;
        }

        #bahanTable tbody tr:hover {
            background-color: #dbe9e8;
        }
    </style>
