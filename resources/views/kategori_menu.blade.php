@include('layouts.header')
@section('title', 'Menu')
@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container mt-4">

            <!-- Placeholder for toolbar -->
            <div id="addMenuWrapper" class="mb-2"></div>

            <table id="menuTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Kategori</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori_menus as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->kategori }}</td>
                            <td>{{ $data->desc }}</td>
                            <td>
                                <a href="{{ route('kategori_menu.edit', $data->id) }}" class="text-primary me-2"><i
                                        class="fas fa-pen-to-square"></i></a>
                                <a href="#" class="text-danger delete-kategori-menu" data-id="{{ $data->id }}"
                                    data-url="{{ route('kategori-menu.destroy', $data->id) }}">
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

<!-- ✅ DataTable Init + Delete Notification -->
<script>
    $(document).ready(function () {
        $(document).on('click', '.delete-kategori-menu', function (e) {
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
                                text: response.success || 'The kategori has been deleted.',
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
                                text: 'Failed to delete menu.'
                            });
                        }
                    });
                }
            });
        });

        const addButton = `
            <a href="{{ route('add_kategori_menu') }}" class="btn btn-primary" id="addMenuBtn">
                <i class="fas fa-plus"></i> Add Menu
            </a>`;

        const table = $('#menuTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lfr>tip',
            columnDefs: [{ orderable: false, targets: -1 }]
        });

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

        #bahanTable tbody tr:nth-child(even) {
            background-color: #c7e1df;
        }

        #bahanTable tbody tr:hover {
            background-color: #dbe9e8;
        }
    </style>
