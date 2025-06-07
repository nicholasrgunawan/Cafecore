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
                        <h3 class="card-title">Menu Pricing Detail</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- Assume variable $menuPricing contains the existing data -->

                    <form method="POST" action="{{ route('menu_pricing.update', $menu_pricing->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputMenu">Menu</label>
                                <select class="form-control" name="menu" id="exampleInputMenu">
                                    @foreach ($menus as $data)
                                        <option value="{{ $data->id }}"
                                            {{ $data->id == $menu_pricing->menu_id ? 'selected' : '' }}>
                                            {{ $data->menu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputBahan">Bahan</label>
                                <select class="form-control" name="bahan" id="exampleInputBahan">
                                    @foreach ($standard_recipe as $data)
                                        <option value="{{ $data->id }}"
                                            {{ $data->id == $menu_pricing->standard_recipe_id ? 'selected' : '' }}>
                                            {{ $data->bahan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="conv">Harga Konversi (Price per unit)</label>
                                <input type="text" class="form-control" id="conv" name="conv"
                                    value="{{ $menu_pricing->standardRecipe->conv ?? '' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <input type="number" class="form-control" id="unit" name="unit"
                                    value="{{ $menu_pricing->used_qty }}" required>
                            </div>

                            <div class="form-group">
                                <label for="total">Used Cost (Jumlah Harga)</label>
                                <input type="number" class="form-control" id="total" name="used_cost"
                                    value="{{ $menu_pricing->used_cost }}" readonly>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#btnSubmit').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure you want to save this data?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Prepare form data
                    let formData = $('form').serialize();

                    // Send AJAX POST request
                    $.ajax({
                        url: "{{ route('menu_pricing.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data successfully saved.',
                                icon: 'success',
                            }).then(() => {
                                location
                            .reload(); // reload page or redirect as needed
                            });
                        },
                        error: function(xhr) {
                            let errorMsg = 'Failed saving the data.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', errorMsg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    // Update conv when bahan changes
    $('#exampleInputBahan').on('change', function() {
        let bahanId = $(this).val();

        if (bahanId) {
            $.ajax({
                url: '/get-conv/' + bahanId,
                type: 'GET',
                success: function(response) {
                    $('#conv').val(response.conv);
                    calculateTotal(); // recalculate total if unit is already entered
                },
                error: function() {
                    $('#conv').val('');
                    $('#total').val('');
                }
            });
        } else {
            $('#conv').val('');
            $('#total').val('');
        }
    });

    // Update total when unit is changed
    $('#unit').on('input', function() {
        calculateTotal();
    });

    function calculateTotal() {
        let conv = parseFloat($('#conv').val()) || 0;
        let unit = parseFloat($('#unit').val()) || 0;
        let total = conv * unit;

        $('#total').val(total.toFixed(2));
    }
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
