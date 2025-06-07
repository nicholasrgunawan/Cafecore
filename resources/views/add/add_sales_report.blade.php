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
                    <form method="POST" action="{{ route('sales_report.store') }}">
                        @csrf

                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputMenu">Menu</label>
                                <select class="form-control" name="menu" id="exampleInputMenu">
    <option disabled selected>Select Menu</option>
    @foreach ($hpp_foods as $data)
        <option value="{{ $data->menu }}" data-harga="{{ $data->hjp_nett }}" data-kategori="{{ $data->kategori }}">
            {{ $data->menu }}
        </option>
    @endforeach
</select>

                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <input type="text" class="form-control" id="kategori" name="kategori"
                                    placeholder="Pick menu first" readonly>
                            </div>

                            <div class="form-group">
                                <label for="qty">Quantity</label>
                                <input type="text" class="form-control" id="qty" name="qty"
                                    placeholder="Enter Quantity" required>
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control" id="harga" name="harga"
                                    placeholder="Pick menu first" readonly>
                            </div>

                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="text" class="form-control" id="total" name="total"
                                    placeholder="Pick menu first" readonly>
                            </div>




                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button>
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
        // Auto-fill harga when menu is selected
        $('#exampleInputMenu').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const harga = parseFloat(selectedOption.data('harga')) || 0;
            const kategori = selectedOption.data('kategori') || 'N/A';

            $('#harga').val(harga);
            $('#kategori').val(kategori); // auto-fill kategori

            calculateTotal(); // optional if quantity is already entered
        });


        // Calculate total on qty input
        $('#qty').on('input', function() {
            calculateTotal();
        });

        function calculateTotal() {
            const harga = parseFloat($('#harga').val()) || 0;
            const qty = parseFloat($('#qty').val()) || 0;
            const total = harga * qty;
            $('#total').val(total.toFixed(2));
        }
    });

    $('#btnSubmit').click(function(e) {
        e.preventDefault();

        const form = $(this).closest('form');
        const url = form.attr('action');
        const data = form.serialize();

        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to submit this sales report entry.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: 'Sales report saved successfully.',
                        }).then(() => {
                            // Optionally reload page or reset form here
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'An error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Laravel validation errors
                            message = Object.values(xhr.responseJSON.errors).flat().join(
                                '\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to save',
                            text: message,
                        });
                    }
                });
            }
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
