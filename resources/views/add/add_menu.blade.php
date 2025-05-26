<!-- resources/views/dashboard.blade.php -->

@include('layouts.header')
@section('title', '$pageTitle')

<section class="content">
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <!-- left column -->
            <div style="width: 100%">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Product Detail</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('menu.store') }}">
                        @csrf
                    
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputMenu">Menu</label>
                                <input type="text" class="form-control" id="exampleInputMenu" name="menu" placeholder="Enter name" required>
                            </div>
                    
                            <div class="form-group">
                                <label for="exampleInputKategori">Kategori</label>
                                <select class="form-control" id="exampleInputKategori" name="kategori" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="Japanese Food">Japanese Food</option>
                                    <option value="Indonesian Food">Indonesian Food</option>
                                    <option value="Sandwich">Sandwich</option>
                                    <option value="Drinks">Drinks</option>
                                </select>
                            </div>
                    
                            <div class="form-group">
                                <label for="bahan">Bahan</label>
                                <div id="bahan-wrapper">
                                    <div class="bahan-select mb-2 d-flex align-items-center">
                                        <select class="form-control" name="bahan[]">
                                            <option value="" disabled selected>-- Pilih Bahan --</option>
                                            @foreach ($bahans as $bahan)
                                                <option value="{{ $bahan }}">{{ $bahan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Add Bahan Button -->
                                <button type="button" id="add-bahan" class="btn btn-sm btn-success mt-2">
                                    <i class="fas fa-plus"></i> Tambah Bahan
                                </button>
                            </div>
                            
                                                   
                    
                        </div>
                        <!-- /.card-body -->
                    
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    
                </div>
                <!-- /.card -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- general form elements disabled -->

        <!-- /.card -->
    </div>
    <!--/.col (right) -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
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
<script>
    document.getElementById('add-bahan').addEventListener('click', function () {
    const wrapper = document.getElementById('bahan-wrapper');

    const newSelect = document.createElement('div');
    newSelect.classList.add('bahan-select', 'mb-2', 'd-flex', 'align-items-center');
    newSelect.innerHTML = `
        <select class="form-control" name="bahan[]">
            <option value="" disabled selected>-- Pilih Bahan --</option>
            @foreach ($bahans as $bahan)
                <option value="{{ $bahan }}">{{ $bahan }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-danger btn-sm ms-2 remove-bahan">×</button>
    `;

    // Append the new select element to the wrapper
    wrapper.appendChild(newSelect);

    // Add event listener to remove button
    newSelect.querySelector('.remove-bahan').addEventListener('click', function () {
        newSelect.remove();
    });
});

// Handle removal of initial select field with the delete button
document.querySelectorAll('.remove-bahan').forEach(button => {
    button.addEventListener('click', function () {
        button.closest('.bahan-select').remove();
    });
});


</script>

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

<!-- ✅ DataTable Init -->
<script>
    $(document).ready(function() {
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
    });
</script>

<!-- ✅ Optional styling -->
<style>
    /* Flexbox container for search and button */
    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Style for the button */
    .ms-2 {
        margin-left: 0.5rem;
    }

    #usersTable tbody tr:nth-child(even) {
        background-color: #c7e1df;
    }

    #usersTable tbody tr:hover {
        background-color: #dbe9e8;
    }
</style>
