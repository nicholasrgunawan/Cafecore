<!-- resources/views/dashboard.blade.php -->

@include('layouts.header')
@section('title', '$pageTitle')

<section class="content">
    <div class="container-fluid">
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
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bahan.update', $bahan->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputBahan">Bahan</label>
                                <input type="text" name="bahan" class="form-control" id="exampleInputBahan"
                                    value="{{ old('bahan', $bahan->bahan) }}" placeholder="Enter name">

                            </div>
                            <div class="form-group">
                                <label for="exampleInputMerk">Merk</label>
                                <input type="text" name="merk" class="form-control"
                                    value="{{ old('merk', $bahan->merk) }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputHarga">Harga</label>
                                <input type="number" name="harga" class="form-control"
                                    value="{{ old('harga', $bahan->harga) }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputKategori">Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <option value="Dry Good"
                                        {{ old('kategori', $bahan->kategori) == 'Dry Good' ? 'selected' : '' }}>Dry Good
                                    </option>
                                    <option value="Veggies"
                                        {{ old('kategori', $bahan->kategori) == 'Veggies' ? 'selected' : '' }}>Veggies
                                    </option>
                                    <option value="Meat"
                                        {{ old('kategori', $bahan->kategori) == 'Meat' ? 'selected' : '' }}>Meat
                                    </option>
                                    <option value="Fruit"
                                        {{ old('kategori', $bahan->kategori) == 'Fruit' ? 'selected' : '' }}>Fruit
                                    </option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputQty">Qty</label>
                                <input type="number" name="qty" class="form-control"
                                    value="{{ old('qty', $bahan->qty) }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputUnit">Unit</label>
                                <select name="unit" class="form-control">
                                    <option value="" disabled>-- Pilih Unit --</option>
                                    <option value="Gr" {{ old('unit', $bahan->unit) == 'Gr' ? 'selected' : '' }}>Gr
                                    </option>
                                    <option value="Kg" {{ old('unit', $bahan->unit) == 'Kg' ? 'selected' : '' }}>Kg
                                    </option>
                                    <option value="Pack" {{ old('unit', $bahan->unit) == 'Pack' ? 'selected' : '' }}>
                                        Pack</option>
                                    <option value="Ons" {{ old('unit', $bahan->unit) == 'Ons' ? 'selected' : '' }}>
                                        Ons</option>
                                </select>

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
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000); // hides after 3 seconds
    });
</script>
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
