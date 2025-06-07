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
                    <form method="POST" action="{{ route('sales_potentials.store') }}">
                        @csrf

                        <div class="card-body">

                            <div class="form-group">
                                <label for="exampleInputMenu">Menu</label>
                                <select class="form-control" name="menu" id="exampleInputMenu">
                                    <option disabled selected>Select Menu</option>
                                    @foreach ($sales_reports as $data)
                                        <option value="{{ $data->menu }}" data-kategori="{{ $data->kategori }}">
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
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    placeholder="Pick menu first" required disabled>
                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    placeholder="Pick menu first" required disabled>
                            </div>

                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    placeholder="Pick date first" readonly>
                            </div>

                            <div class="form-group">
                                <label for="per_cost">Cost</label>
                                <input type="number" class="form-control" id="per_cost" name="per_cost"
                                    placeholder="Pick date first" readonly>
                            </div>

                            <div class="form-group">
                                <label for="qty">Total Quantity</label>
                                <input type="number" class="form-control" id="qty" name="qty"
                                    placeholder="Pick date first" readonly>
                            </div>

                            <div class="form-group">
                                <label for="cost">Amount Cost</label>
                                <input type="number" class="form-control" id="cost" name="cost"
                                    placeholder="Pick date first" readonly>
                            </div>

                            <div class="form-group">
                                <label for="sales">Sales</label>
                                <input type="number" class="form-control" id="sales" name="sales"
                                    placeholder="Pick date first" readonly>
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

        function fetchSalesData() {
            const menu = $('#exampleInputMenu').val();
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (!menu || !startDate || !endDate) {
                $('#qty, #cost, #sales').val('').prop('readonly', true);
                return;
            }

            $.ajax({
                url: `/get-sales-data`,
                method: 'GET',
                data: {
                    menu,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    const qty = response.qty || 0;
                    $('#qty').val(qty).prop('readonly', true);

                    // Amount Cost
                    $.ajax({
                        url: `/get-amount-cost`,
                        method: 'GET',
                        data: {
                            menu,
                            qty
                        },
                        success: function(res) {
                            $('#cost').val(res.amountCost || 0).prop('readonly', true);
                        },
                        error: function() {
                            $('#cost').val('').prop('readonly', true);
                        }
                    });

                    // Amount Sales
                    $.ajax({
                        url: `/get-amount-sales`,
                        method: 'GET',
                        data: {
                            menu,
                            qty
                        },
                        success: function(res) {
                            $('#sales').val(res.amountSales || 0).prop('readonly',
                                true);
                        },
                        error: function() {
                            $('#sales').val('').prop('readonly', true);
                        }
                    });

                    $.ajax({
                        url: `/get-price`,
                        method: 'GET',
                        data: {
                            menu,
                            qty
                        },
                        success: function(res) {
                            $('#price').val(res.price || 0).prop('readonly',
                                true);
                        },
                        error: function() {
                            $('#price').val('').prop('readonly', true);
                        }
                    });

                    $.ajax({
                        url: `/get-per-cost`,
                        method: 'GET',
                        data: {
                            menu,
                            qty
                        },
                        success: function(res) {
                            $('#per_cost').val(res.per_cost || 0).prop('readonly',
                                true);
                        },
                        error: function() {
                            $('#per_cost').val('').prop('readonly', true);
                        }
                    });
                }
            });
        }


        // Disable date inputs initially
        $('#start_date, #end_date').prop('disabled', true);

        // When menu changes
        $('#exampleInputMenu').on('change', function() {
            const menu = $(this).val();
            const kategori = $(this).find(':selected').data('kategori') || 'N/A';
            $('#kategori').val(kategori);

            if (menu) {
                // Enable date inputs when a menu is selected
                $('#start_date, #end_date').prop('disabled', false);
            } else {
                // Disable if menu is deselected
                $('#start_date, #end_date').prop('disabled', true).val('');
                $('#qty').val('').prop('readonly', true);
            }

            fetchSalesData();
        });

        // When start_date or end_date changes
        $('#start_date, #end_date').on('change', function() {
            fetchSalesData();
        });

    });

    $('#btnSubmit').on('click', function() {
        const menu = $('#exampleInputMenu').val();
        const kategori = $('#kategori').val();
        const price = $('#price').val();
        const per_cost = $('#per_cost').val();
        const qty = $('#qty').val();
        const cost = $('#cost').val();
        const sales = $('#sales').val();

        if (!menu || !kategori || !price || !per_cost || !qty || !cost || !sales) {
            Swal.fire('Warning', 'Please fill all fields before submitting.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this data?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to save data
                $.ajax({
                    url: "{{ route('sales_potentials.store') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        menu: menu,
                        kategori: kategori,
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        price: price,
                        per_cost: per_cost,
                        qty: qty,
                        cost: cost,
                        sales: sales
                    },
                    success: function(response) {
                        Swal.fire('Saved!', 'Data saved successfully.', 'success').then(
                        () => {
                                window.location.href =
                                    "{{ route('menu_engineering3') }}";
                            });
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText); // Add this line
                        Swal.fire('Error!', 'Failed to save data.', 'error');
                    }
                });
            } else {
                Swal.fire('Cancelled', 'Data not saved.', 'info');
            }
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
