@include('layouts.header')
@section('title', 'Barang Keluar')

<section class="content">
    <div class="container mt-4">

        <!-- Placeholder for toolbar -->
        <div id="toolbarWrapper" class="mb-2"></div>

        <!-- Main Table -->
        <table id="barangTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bahan</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang_keluar as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->bahan }}</td>
                        <td>{{ $data->kategori }}</td>
                        <td>{{ $data->qty }}</td>
                        <td>{{ $data->unit }}</td>
                        <td>Rp. {{ number_format($data->harga, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($data->jumlah, 2, ',', '.') }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td><a href="#" class="text-danger remove-row"><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                @endforeach

            </tbody>

        </table>

        <!-- Summary Table -->
        <!-- Summary Table -->
        <table class="table table-bordered text-center mt-4" id="summaryTable">
            <tbody>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Dry good</td>
                    <td id="drygood-total">Rp{{ number_format($summary['dry'], 2, ',', '.') }}</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Veggies</td>
                    <td id="veggies-total">Rp{{ number_format($summary['veggies'], 2, ',', '.') }}</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Meat</td>
                    <td id="meat-total">Rp{{ number_format($summary['meat'], 2, ',', '.') }}</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Fruit</td>
                    <td id="fruit-total">Rp{{ number_format($summary['fruit'], 2, ',', '.') }}</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Total</td>
                    <td id="grand-total">Rp{{ number_format($summary['total'], 2, ',', '.') }}</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="6" class="text-start fw-bold">Control</td>
                    <td id="control-total">Rp{{ number_format($summary['control'], 2, ',', '.') }}</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="6" class="text-start fw-bold">Control (Harus 0)</td>
                    <td id="control-zero">Rp{{ number_format($summary['control_zero'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>


    </div>

</section>
</div>
@include('layouts.footer')


<!-- Scripts -->
<!-- SweetAlert2 -->
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
    // Initialize DataTable only once
    const table = $('#barangTable').DataTable({
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

    // Add toolbar buttons once, after DataTable init
    const toolbarButtons = `
        <button id="printLaporanBtn" class="btn btn-secondary btn-sm">Print laporan</button>
        <button class="btn btn-primary btn-sm" id="importBtn">Import data</button>
        <input type="file" id="csvFileInput" accept=".csv" style="display:none" />
        <button class="btn btn-danger btn-sm">Clear data</button>
    `;
    $('.dataTables_filter').after(`<div class="toolbar-wrapper ms-2 d-flex gap-2">${toolbarButtons}</div>`);

    $('#importBtn').on('click', function() {
        $('#csvFileInput').click();
    });

    $('#csvFileInput').on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const text = event.target.result;
            importCSVData(text);
            $('#csvFileInput').val('');
        };
        reader.readAsText(file);
    });

    function importCSVData(csvText) {
    const lines = csvText.trim().split('\n');
    if (lines.length < 2) {
        Swal.fire('Error', 'CSV file is empty or invalid', 'error');
        return;
    }

    const rowsToImport = [];

    for (let i = 1; i < lines.length; i++) {
        const row = lines[i].split(',');
        if (row.length < 8) continue;

        rowsToImport.push(row);
    }

    if (rowsToImport.length === 0) {
        Swal.fire('Error', 'No valid rows found in CSV', 'error');
        return;
    }

    // Send rows to server for saving
    $.ajax({
        url: "{{ route('barang-keluar.import') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            rows: rowsToImport,
        },
        success: function(response) {
            // On success, add to DataTable UI
            rowsToImport.forEach(function(row) {
                table.row.add([
                    row[0], // ID
                    row[1], // Bahan
                    row[2], // Kategori
                    row[3], // Qty
                    row[4], // Unit
                    row[5], // Harga
                    row[6], // Jumlah
                    row[7], // Created at
                    `<a href="#" class="text-danger remove-row"><i class="fas fa-trash-alt"></i></a>`
                ]).draw(false);
            });
            Swal.fire('Imported', response.message, 'success');
            updateSummaryTable();
        },
        error: function(xhr) {
            Swal.fire('Error', 'Failed to import data.', 'error');
        }
    });
}



        // Add event listener on the newly added .btn-danger
        $('.btn-danger').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will remove all rows from the table and delete data from the database.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, clear it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('barang-keluar.clear') }}",
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // pass CSRF token
                        },
                        success: function(response) {
                            // Clear DataTable rows visually
                            table.clear().draw();

                            // Clear localStorage
                            localStorage.removeItem('barangKeluarRows');
                            localStorage.removeItem('summaryTotals');

                            updateSummaryTable();

                            Swal.fire('Cleared!', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to clear data.', 'error');
                        }
                    });
                }
            });
        });
        $('#barangTable tbody').on('click', '.remove-row', function(e) {
            e.preventDefault();
            const row = $(this).closest('tr');
            const rowId = row.find('td:first').text().trim(); // Assuming first column is ID

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete the row from the database.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang-keluar/${rowId}`, // Adjust your route accordingly
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remove row from DataTable
                            table.row(row).remove().draw();

                            // Optionally update summary table & localStorage here
                            updateSummaryTable();
                            localStorage.removeItem(
                            'barangKeluarRows'); // or update accordingly

                            Swal.fire('Deleted!', response.message, 'success');
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to delete row.', 'error');
                        }
                    });
                }
            });
        });


        // Initialize DataTable and add toolbar buttons
        if (!$.fn.dataTable.isDataTable('#barangTable')) {
            $('#barangTable').DataTable({
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

            $('.dataTables_filter').after(
                `<div class="toolbar-wrapper ms-2 d-flex gap-2">${toolbarButtons}</div>`
            );
        }
    });

    function downloadCSV(filename, csvContent) {
        const blob = new Blob([csvContent], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    function escapeCSV(value) {
        if (typeof value === 'string') {
            if (value.includes(',') || value.includes('"') || value.includes('\n')) {
                value = value.replace(/"/g, '""');
                return `"${value}"`;
            }
            return value;
        }
        return value;
    }

    $(document).ready(function() {
        $('#printLaporanBtn').on('click', function() {
            const table = $('#barangTable').DataTable();

            // Prepare CSV header from main table columns (exclude Action)
            const headers = [];
            $('#barangTable thead th').each(function(index) {
                if (index !== 8) { // skip Action column
                    headers.push($(this).text().trim());
                }
            });

            let csv = headers.join(",") + "\n";

            // Get main table data rows
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                const row = this.data();
                // row is an array-like or DOM elements, depends on DataTable config
                // We'll get cell data manually for each column except Action (index 8)
                const rowData = [];
                $('#barangTable tbody tr').eq(rowIdx).find('td').each(function(i) {
                    if (i !== 8) {
                        rowData.push(escapeCSV($(this).text().trim()));
                    }
                });
                csv += rowData.join(",") + "\n";
            });

            // Add empty line to separate summary
            csv += "\n";

            // Add summary table header
            csv += "Summary Table\n";
            $('#summaryTable tbody tr').each(function() {
                const cols = [];
                $(this).find('td').each(function() {
                    cols.push(escapeCSV($(this).text().trim()));
                });
                csv += cols.join(",") + "\n";
            });

            // Download CSV file
            downloadCSV("laporan_barang_keluar.csv", csv);
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />



<style>
    /* Make sure the wrapper for entries, search, and toolbar is aligned */
    .dataTables_wrapper>.d-flex {
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    /* Make sure the entries dropdown is properly aligned */
    .dataTables_length {
        display: flex;
        align-items: center;
        /* Vertically center the content */
        gap: 1rem;
        /* Space between entries dropdown and other elements */
    }

    /* Make sure the search box aligns with the entries dropdown */
    .dataTables_filter {
        display: flex;
        align-items: center;
        /* Vertically align the search input with the entries dropdown */
        margin-left: 1rem;
        /* Space between the search box and the entries dropdown */
        margin-bottom: 0 !important;
        /* Ensure no extra space below */
    }

    /* Align the toolbar buttons */
    .toolbar-wrapper {
        margin-left: auto;
        display: flex;
        gap: 10px;
        /* Adds spacing between buttons */
        align-items: center;
        /* Ensure buttons are vertically aligned */
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
        padding: 10px 0;
        /* Adjust padding as needed */
        margin: 0;
        height: auto;
        /* Let it shrink to the content */
        position: relative;
        bottom: 0;
        width: 100%;
    }

    /* If you are using .content-wrapper or similar, reset padding for consistency */
    .content-wrapper {
        padding-bottom: 0;
        /* Reset any padding to make the footer fit snugly */
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
        padding: 10px;
        /* Adjust as needed to ensure the correct height */
    }

    /* Optionally set a max-height or limit height to keep it consistent */
    footer {
        max-height: 80px;
        /* Limit the height to your desired max height */
    }
</style>
