<!-- resources/views/dashboard.blade.php -->

@include('layouts.header')
@section('title', '$pageTitle')

<section class="content">
    <div class="container mt-4">

        <!-- Placeholder for toolbar -->
        <div id="addUsersWrapper" class="mb-2"></div>

        <table id="usersTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d-m-Y') }}</td>
                        <td>{{ $user->updated_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="text-primary me-2"><i class="fas fa-pen-to-square"></i></a>
                            <a href="#" class="text-danger delete-user" data-id="{{ $user->id }}">
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
<script>
    $(document).on('click', '.delete-user', function (e) {
    e.preventDefault();
    const userId = $(this).data('id');

    if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: `/users/${userId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                alert(response.success);
                location.reload(); // Optional: re-fetch the page or reinitialize DataTables
            },
            error: function () {
                alert('Failed to delete user.');
            }
        });
    }
});

    $(document).ready(function() {
        const addButton = `
    <a href="{{ route('add_user') }}" class="btn btn-primary" id="addUsersBtn">
    <i class="fas fa-plus"></i> Add Users
</a>
`;


        const table = $('#usersTable').DataTable({
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

        // Append Add Users button to the right of the search
        $('.dataTables_filter').after(`<div class="add-users-wrapper ms-2">${addButton}</div>`);
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

    /* Add Users floats right */
    .add-users-wrapper {
        margin-left: auto;
    }

    .add-users-wrapper button i {
        margin-right: 5px;
    }

    #usersTable tbody tr:nth-child(even) {
        background-color: #c7e1df;
    }

    #usersTable tbody tr:hover {
        background-color: #dbe9e8;
    }
</style>
