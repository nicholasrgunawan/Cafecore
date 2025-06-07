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
                  <th>Id</th>
                  <th>Menu</th>
                  <th>Bahan</th>
                  <th>Unit</th>
                  <th>Total Harga</th>
                  <th>Created At</th>
                  <th>Updated At</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
    @foreach ($menu_pricing as $data)
        <tr>
            <td>{{ $data->id }}</td>
            <td>{{ $data->menu ? $data->menu->menu : 'N/A' }}</td> {{-- menu name --}}
            <td>{{ $data->standardRecipe ? $data->standardRecipe->bahan : 'N/A' }}</td> {{-- bahan name --}}
            <td>{{ $data->used_qty }}</td>
            <td>Rp. {{ number_format($data->used_cost, 2, ',', '.') }}</td>
            <td>{{ $data->created_at }}</td>
            <td>{{ $data->updated_at }}</td>
            <td>
                <a href="{{ route('menu_pricing.edit', $data->id) }}" class="text-primary me-2">
    <i class="fas fa-pen-to-square"></i>
</a>

                <a href="#" class="text-danger delete-menu_pricing" data-id="{{ $data->id }}"
                    data-url="{{ route('menu-pricing.destroy', $data->id) }}">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
</tbody>

      </table>
      <!-- Second Table -->
<div class="mt-5">
    <!-- Toolbar placeholder for second table -->
    <div id="addSummaryWrapper" class="mb-2"></div>

    <table id="summaryTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Menu</th>
                <th>Total Bahan Cost</th>
                <th>Final Price(+5%)</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menu_summary as $summary)
                <tr>
                    <td>{{ $summary->id }}</td>
                    <td>{{ $summary->menu ? $summary->menu->menu : 'N/A' }}</td>
                    <td>Rp. {{ number_format($summary->total_bahan_cost, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($summary->final_price, 2, ',', '.') }}</td>
                    <td>{{ $summary->created_at }}</td>
                    <td>{{ $summary->updated_at }}</td>
                    <td>
                        <a href="#" class="text-danger delete-menu_summary" data-id="{{ $summary->id }}"
                            data-url="{{ route('menu-summary.destroy', $summary->id) }}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    // === Table 1 (Bahan Table) ===
    const addButton1 = `
    <button class="btn btn-secondary btn-sm">Print laporan</button>
    <button class="btn btn-secondary btn-sm">Import data</button>
    <a href="{{ route('add_menu_pricing') }}" class="btn btn-primary btn-sm" id="addMenuBtn">
        <i class="fas fa-plus"></i> Add Menu
    </a>`;

    const table1 = $('#bahanTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        dom: '<"bahan-toolbar d-flex justify-content-between align-items-center mb-3"lfr>tip',
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });

    $('.bahan-toolbar .dataTables_filter').after(`<div class="add-bahan-wrapper ms-2">${addButton1}</div>`);


    // === Table 2 (Summary Table) ===
    const addButton2 = `
    <button class="btn btn-secondary btn-sm">Print laporan</button>
    <button class="btn btn-secondary btn-sm">Import data</button>
    <a href="{{ route('add_menu_summary') }}" class="btn btn-primary btn-sm" id="addSummaryBtn">
        <i class="fas fa-plus"></i> Add Summary
    </a>`;

    const table2 = $('#summaryTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        dom: '<"summary-toolbar d-flex justify-content-between align-items-center mb-3"lfr>tip',
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });

    $('.summary-toolbar .dataTables_filter').after(`<div class="add-summary-wrapper ms-2">${addButton2}</div>`);
});

 

$(document).ready(function() {
  // Delete MenuPricing row
  $(document).on('click', '.delete-menu_pricing', function(e) {
    e.preventDefault();  // prevent default link behavior
    
    let id = $(this).data('id');
    let url = $(this).data('url');
    let row = $(this).closest('tr');

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will delete the menu pricing entry permanently.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: url,  // from data-url attribute
          type: 'DELETE',
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: function(response) {
            Swal.fire('Deleted!', response.message, 'success');
            row.remove();
          },
          error: function(xhr) {
            Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete.', 'error');
          }
        });
      }
    });
  });

  // Delete MenuSummary row
  $(document).on('click', '.delete-menu_summary', function(e) {
    e.preventDefault();

    let id = $(this).data('id');
    let url = $(this).data('url');
    let row = $(this).closest('tr');

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will delete the menu summary entry permanently.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: url,
          type: 'DELETE',
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: function(response) {
            Swal.fire('Deleted!', response.message, 'success');
            row.remove();
          },
          error: function(xhr) {
            Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete.', 'error');
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
.add-summary-wrapper { 
    margin-left: auto; }
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

