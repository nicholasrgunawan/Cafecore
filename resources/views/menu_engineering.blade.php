<!-- resources/views/dashboard.blade.php -->

@include('layouts.header')
@section('title', 'Standard Recipe(REV)')
    
<section class="content">
  <div class="container mt-4">

      <!-- Placeholder for toolbar -->
      <div id="addBahanWrapper" class="mb-2"></div>

      <table id="bahanTable" class="display" style="width:100%">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Menu</th>
                  <th>Kategori</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th>Tanggal</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
               @foreach ($sales_report as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->menu}}</td>
                    <td>{{ $data->kategori}}</td>
                    <td>{{$data->qty}}</td>
                    <td>Rp. {{ number_format($data->total, 2, ',', '.') }}</td>
                    <td>{{ $data->created_at }}</td>
                    <td>
                        <a href="#" class="text-danger delete-sales_report" data-id="{{ $data->id }}"
                            data-url="{{ route('sales-report.destroy', $data->id) }}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
          </tbody>
      </table>

      <table class="table table-bordered text-center mt-4" id="summaryTable">
  <tbody>
      <tr class="table-light">
          <td colspan="6" class="text-start fw-bold">Total Quantity</td>
          <td id="totalQuantity">0</td>
      </tr>
      <tr class="table-secondary">
        <td colspan="6" class="text-start fw-bold">Total Sales</td>
        <td id="totalSales">Rp-</td>
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
<script> $.widget.bridge('uibutton', $.ui.button); </script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>

<!-- ✅ DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- ✅ Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<!-- ✅ DataTable Init + Toolbar Button Injection -->
<!-- ✅ DataTable Init + Adjusted Layout for Entries & Search -->
<!-- SweetAlert2 CSS & JS CDN -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    const addButton = `
        <button class="btn btn-secondary btn-sm">Print laporan</button>
        <button class="btn btn-secondary btn-sm">Import data</button>
        <button class="btn btn-success btn-sm">Save</button>
        <button id="clearDataBtn" class="btn btn-danger btn-sm">Clear data</button>
        <a href="{{ route('add_sales_report') }}" class="btn btn-primary btn-sm" id="addMenuBtn">
        <i class="fas fa-plus"></i> Add Menu
    </a>`;

    const table = $('#bahanTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        dom: '<"d-flex justify-content-between align-items-center mb-3"lfr>tip',
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });

    // Append Add Bahan button to the right of the search
    $('.dataTables_filter').after(`<div class="add-bahan-wrapper ms-2">${addButton}</div>`);

    // Function to update totals
    function updateTotals() {
    let totalQty = 0;
    let totalSales = 0;

    table.rows({ search: 'applied' }).every(function () {
        const data = this.data();

        let qty = parseFloat(data[3]);
        if (isNaN(qty)) qty = 0;

        let totalStr = data[4]; // e.g. "Rp. 12.345,67"
        let totalNum = parseFloat(
            totalStr.replace(/[Rp.\s]/g, '').replace(',', '.')
        );
        if (isNaN(totalNum)) totalNum = 0;

        totalQty += qty;
        totalSales += totalNum;
    });

    $('#totalQuantity').text(totalQty);
    $('#totalSales').text(
        'Rp. ' + totalSales.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    );
}


    // Call once on page load
    updateTotals();

    // Update totals when table redraws (paging, filtering, etc)
    table.on('search.dt page.dt order.dt draw.dt', function () {
        updateTotals();
    });

    // Delete confirmation with SweetAlert2
    $('#bahanTable').on('click', '.delete-sales_report', function (e) {
        e.preventDefault();

        const url = $(this).data('url');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            );
                            // Remove row using the existing DataTable instance
                            table.row(row).remove().draw();

                            // Update totals after deletion
                            updateTotals();
                        } else {
                            Swal.fire('Error!', response.message || 'Delete failed.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Could not delete data.', 'error');
                    }
                });
            }
        });
    });
    $('#clearDataBtn').on('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete ALL data and reset the IDs. This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear all data!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("sales-report.clear") }}', // We'll create this route in Laravel
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire(
                            'Cleared!',
                            'All data has been deleted and IDs reset.',
                            'success'
                        );

                        // Clear DataTable rows
                        table.clear().draw();

                        // Update totals
                        updateTotals();
                    } else {
                        Swal.fire('Error!', response.message || 'Clear data failed.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Could not clear data.', 'error');
                }
            });
        }
    });
});

});

</script>






<!-- ✅ Optional styling -->
<style>
/* Wrap entries + search together and keep aligned */
.dataTables_wrapper > .d-flex {
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

