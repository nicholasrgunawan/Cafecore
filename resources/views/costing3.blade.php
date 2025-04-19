<!-- resources/views/barangmasuk.blade.php -->

@include('layouts.header')
@section('title', 'Barang Masuk')

<section class="content">
  <div class="container mt-4">

      <!-- Placeholder for toolbar -->
      <div id="toolbarWrapper" class="mb-2"></div>

      <!-- Main Table -->
      <table id="barangTable" class="display" style="width:100%">
          <thead>
              <tr>
                  <th>Kategori</th>
                  <th>Pembelanjaan</th>
                  <th>Pengeluaran</th>
                  <th>Saldo Gudang</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>Kategori 1</td>
                  <td>Rp-</td>
                  <td>Rp-</td>
                  <td>Rp-</td>
              </tr>
              <tr>
                <td>Kategori 2</td>
                <td>Rp-</td>
                <td>Rp-</td>
                <td>Rp-</td>
            </tr>
            <tr>
                <td>Kategori 3</td>
                <td>Rp-</td>
                <td>Rp-</td>
                <td>Rp-</td>
            </tr>
          </tbody>
      </table>

      <!-- Summary Table -->
      <table class="table table-bordered text-center mt-4" id="summaryTable">
          <tbody>
              <tr class="table-secondary">
                  <td colspan="6" class="text-start fw-bold">Total Pembelanjaan</td>
                  <td>Rp-</td>
              </tr>
              <tr class="table-secondary">
                <td colspan="6" class="text-start fw-bold">Total Pengeluaran</td>
                <td>Rp-</td>
            </tr>
            <tr class="table-secondary">
                <td colspan="6" class="text-start fw-bold">Saldo Gudang</td>
                <td>Rp-</td>
            </tr>
          </tbody>
      </table>
  </div>
</section>

</div>
@include('layouts.footer')


<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script> $.widget.bridge('uibutton', $.ui.button); </script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
$(document).ready(function () {
    // Toolbar buttons with date pickers
    const toolbarButtons = `
        <input type="text" id="minDate" class="form-control form-control-sm" placeholder="From date" style="width: 140px;">
        <input type="text" id="maxDate" class="form-control form-control-sm" placeholder="To date" style="width: 140px;">
        <button class="btn btn-secondary btn-sm">Print Laporan</button>
    `;

    // Initialize DataTable
    const table = $('#barangTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        dom: '<"d-flex justify-content-between align-items-center mb-3"lfr>tip',
        columnDefs: [{ orderable: false, targets: -1 }]
    });

    // Add the toolbar buttons
    $('.dataTables_filter').after(`<div class="toolbar-wrapper ms-2 d-flex gap-2">${toolbarButtons}</div>`);

    // Custom filtering function for date range
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        const min = $('#minDate').datepicker("getDate");
        const max = $('#maxDate').datepicker("getDate");
        const dateStr = data[0]; // Adjust index based on date column (assumes it's the first column)
        const date = new Date(dateStr);

        if (
            (!min && !max) ||
            (min && !max && date >= min) ||
            (!min && max && date <= max) ||
            (min && max && date >= min && date <= max)
        ) {
            return true;
        }
        return false;
    });

    // Initialize the datepickers
    $("#minDate, #maxDate").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function () {
            table.draw();
        }
    });
});
</script>

<style>
/* Make sure the wrapper for entries, search, and toolbar is aligned */
.dataTables_wrapper > .d-flex {
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

/* Make sure the entries dropdown is properly aligned */
.dataTables_length {
    display: flex;
    align-items: center;  /* Vertically center the content */
    gap: 1rem;  /* Space between entries dropdown and other elements */
}

/* Make sure the search box aligns with the entries dropdown */
.dataTables_filter {
    display: flex;
    align-items: center; /* Vertically align the search input with the entries dropdown */
    margin-left: 1rem; /* Space between the search box and the entries dropdown */
    margin-bottom: 0 !important; /* Ensure no extra space below */
}

/* Align the toolbar buttons */
.toolbar-wrapper {
    margin-left: auto;
    display: flex;
    gap: 10px;  /* Adds spacing between buttons */
    align-items: center;  /* Ensure buttons are vertically aligned */
}

/* Add some space between the buttons and icons */
.toolbar-wrapper .btn i {
    margin-right: 5px;
}

/* Ensure the search box aligns with the dropdown and toolbar */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: flex;
    align-items: center;
}

/* Table row styles */
#barangTable tbody tr:nth-child(even) {
    background-color: #c7e1df;
}

#barangTable tbody tr:hover {
    background-color: #dbe9e8;
}

/* Ensure footer height is consistent */
footer {
    padding: 10px 0; /* Adjust padding as needed */
    margin: 0;
    height: auto; /* Let it shrink to the content */
    position: relative;
    bottom: 0;
    width: 100%;
}

/* If you are using .content-wrapper or similar, reset padding for consistency */
.content-wrapper {
    padding-bottom: 0; /* Reset any padding to make the footer fit snugly */
}

/* Ensure the footer sticks to the bottom of the page */
.footer-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Remove excessive margin/padding around footer if added by default */
footer .container {
    margin: 0;
    padding: 10px; /* Adjust as needed to ensure the correct height */
}

/* Optionally set a max-height or limit height to keep it consistent */
footer {
    max-height: 80px;  /* Limit the height to your desired max height */
}
</style>
