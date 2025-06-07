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
                  <th>Cost</th>
                  <th>Price</th>
                  <th>Total Quantity</th>
                  <th>Amount Cost</th>
                  <th>Sales</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
              @foreach ($sales_potentials as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->menu}}</td>
                    <td>{{ $data->kategori}}</td>
                    <td>Rp. {{ number_format($data->price, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($data->per_cost, 2, ',', '.') }}</td>
                    <td>{{$data->qty}}</td>
                    <td>Rp. {{ number_format($data->cost, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($data->sales, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') }}</td>
<td>{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}</td>

                    <td>
                        <a href="#" class="text-danger delete-sales_report" data-id="{{ $data->id }}"
                            data-url="{{ route('sales-potentials.destroy', $data->id) }}">
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
          <td id="totalQty">-</td>
      </tr>
      <tr class="table-secondary">
          <td colspan="6" class="text-start fw-bold">Total Cost</td>
          <td id="totalCost">-</td>
      </tr>
      <tr class="table-light">
          <td colspan="6" class="text-start fw-bold">Total Sales</td>
          <td id="totalSales">-</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- ✅ DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- ✅ Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


<script>
$(document).ready(function () {
    const addButton = `
        <button class="btn btn-secondary btn-sm">Print laporan</button>
        <button class="btn btn-secondary btn-sm">Import data</button>
        <a href="{{ route('add_sales_potentials') }}" class="btn btn-primary btn-sm" id="addMenuBtn">
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

    // Function to update totals in the summary table
    function updateTotals() {
    let totalQty = 0;
    let totalCost = 0;
    let totalSales = 0;

    table.rows().every(function() {
        const data = this.data();
        totalQty += parseFloat(data[3]) || 0;

        const cost = parseFloat(data[4].replace(/[^0-9,-]+/g,"").replace(/\./g, '').replace(',', '.')) || 0;
        const sales = parseFloat(data[5].replace(/[^0-9,-]+/g,"").replace(/\./g, '').replace(',', '.')) || 0;

        totalCost += cost;
        totalSales += sales;
    });

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(number);
    };

    $('#totalQty').text(totalQty);
    $('#totalCost').text(formatRupiah(totalCost));
    $('#totalSales').text(formatRupiah(totalSales));
}function updateTotals() {
    let totalQty = 0;
    let totalCost = 0;
    let totalSales = 0;

    table.rows({ filter: 'applied' }).every(function() {
        const data = this.data();

        // Total Quantity (index 5)
        totalQty += parseFloat(data[5]) || 0;

        // Amount Cost (index 6)
        const cost = parseFloat(data[6].replace(/[^0-9,-]+/g,"").replace(/\./g, '').replace(',', '.')) || 0;

        // Sales (index 7)
        const sales = parseFloat(data[7].replace(/[^0-9,-]+/g,"").replace(/\./g, '').replace(',', '.')) || 0;

        totalCost += cost;
        totalSales += sales;
    });

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(number);
    };

    $('#totalQty').text(totalQty);
    $('#totalCost').text(formatRupiah(totalCost));
    $('#totalSales').text(formatRupiah(totalSales));
}



    updateTotals();

    // Recalculate totals on table redraw (search, paging, etc)
    table.on('draw', function() {
        updateTotals();
    });
});

$(document).on('click', '.delete-sales_report', function (e) {
    e.preventDefault();

    const id = $(this).data('id');
    const url = $(this).data('url');

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
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
                        title: 'Deleted!',
                        text: 'The record has been deleted.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // You can also manually remove the row if preferred
                    });
                },
                error: function (xhr) {
                    Swal.fire(
                        'Failed!',
                        'An error occurred while deleting the record.',
                        'error'
                    );
                }
            });
        }
    });
});

$(document).on('click', '#clearDataBtn', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete ALL records permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear all!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('sales-potentials.clear') }}",
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Cleared!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();  // reload the page or update DataTable
                    });
                },
                error: function(xhr) {
                    Swal.fire('Failed!', 'Could not clear data.', 'error');
                }
            });
        }
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

