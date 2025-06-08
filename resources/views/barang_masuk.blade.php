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
                    <th>ID</th>
                    <th>Bahan</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr id="template-row" style="display: none;">
                    <td>#</td>
                    <td>
                        <select name="bahan[]" class="form-select">
                            <option disabled selected>Select Bahan</option>
                            @foreach ($bahans as $bahan)
                                <option value="{{ $bahan }}">{{ $bahan }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="kategori-col">-</td>
                    <td><input type="number" class="form-control form-control-sm qty-input" placeholder="Insert qty">
                    </td>
                    <td class="unit-col">-</td>
                    <td class="harga-col">Rp-</td>
                    <td class="jumlah-col">Rp-</td>
                    <td><a href="#" class="text-danger remove-row"><i class="fas fa-trash-alt"></i></a></td>
                </tr>

            </tbody>

        </table>

        <!-- Summary Table -->
        <!-- Summary Table -->
        <table class="table table-bordered text-center mt-4" id="summaryTable">
            <tbody>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Dry good</td>
                    <td id="drygood-total">Rp-</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Veggies</td>
                    <td id="veggies-total">Rp-</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Meat</td>
                    <td id="meat-total">Rp-</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Fruit</td>
                    <td id="fruit-total">Rp-</td>
                </tr>
                <tr class="table-light">
                    <td colspan="6" class="text-start fw-bold">Total</td>
                    <td id="grand-total">Rp-</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="6" class="text-start fw-bold">Control</td>
                    <td id="control-total">Rp-</td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="6" class="text-start fw-bold">Control (Harus 0)</td>
                    <td id="control-zero">(Rp-)</td>
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
        const toolbarButtons = `
        <a href="{{ route('barangmasuk.saved_data') }}">
                                    <button class="btn btn-primary btn-sm">View Data</button>
                              </a>
            <button class="btn btn-success btn-sm" id="saveDataBtn">Save</button>
            <button class="btn btn-danger btn-sm">Clear data</button>
            <button id="addBarangBtn" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add barang masuk
            </button>
        `;

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

        // Add event listener for Save Button
        // Add event listener for Save Button
        $('#saveDataBtn').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure you want to save this data?',
                text: 'You will not be able to change this after saving.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get row details
                    const rows = [];
                    document.querySelectorAll('#barangTable tbody tr:not(#template-row)')
                        .forEach(row => {
                            rows.push({
                                bahan: row.querySelector('select').value,
                                kategori: row.querySelector('.kategori-col')
                                    .textContent,
                                qty: row.querySelector('.qty-input').value,
                                unit: row.querySelector('.unit-col').textContent,
                                harga: row.querySelector('.harga-col').textContent
                                    .replace(/[^\d]/g, ''),
                                jumlah: row.querySelector('.jumlah-col').textContent
                                    .replace(/[^\d]/g, '')
                            });
                        });

                    // Get summary totals from localStorage
                    const summary = JSON.parse(localStorage.getItem('summaryTotals')) || {
                        dry: 0,
                        veggies: 0,
                        meat: 0,
                        fruit: 0,
                        total: 0
                    };

                    // Send both to server
                    $.ajax({
                        url: '/save-barang-masuk',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            items: rows,
                            summary: summary
                        },
                        success: function() {
                            Swal.fire({
                                title: 'Saved!',
                                text: 'Your data has been saved.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Avoid clearing localStorage
                            // Optionally, if you want to reload after saving, it will now reload with saved data
                            // But instead of clearing localStorage, we leave it intact

                            // After saving, reload the page and refresh the localStorage with data from the DB (if needed)
                            // This step should be done if your server response contains fresh data
                            // location.reload();  // Uncomment if you want to refresh the page after saving
                        },
                        error: function(err) {
                            console.error(err);
                            Swal.fire('Error', 'Failed to save data.', 'error');
                        }
                    });
                }
            });
        });




        const addButton = document.querySelector('#addBarangBtn');
        const tableBody = document.querySelector('#barangTable tbody');
        let rowCount = 1;

        addButton.addEventListener('click', function() {
            const templateRow = document.querySelector('#template-row');
            const newRow = templateRow.cloneNode(true);
            newRow.removeAttribute('id');
            newRow.style.display = '';
            newRow.querySelector('td:first-child').textContent = `${rowCount}`;
            tableBody.appendChild(newRow);
            attachListeners(newRow);
            rowCount++;
            saveTableToLocalStorage();
        });

        function attachListeners(row) {
            const bahanSelect = row.querySelector('select');
            bahanSelect.classList.add('bahan-select');

            bahanSelect.addEventListener('change', function() {
                const selectedBahan = this.value;

                fetch(`/get-bahan-details/${selectedBahan}`)
                    .then(res => res.json())
                    .then(data => {
                        row.querySelector('.kategori-col').textContent = data.kategori;
                        row.querySelector('.unit-col').textContent = data.unit;
                        row.querySelector('.harga-col').textContent = `Rp ${data.harga}`;
                        calculateJumlah(row);
                        saveTableToLocalStorage();
                    })
                    .catch(err => {
                        console.error('Error fetching bahan details:', err);
                    });
            });
            row.querySelector('.remove-row').addEventListener('click', function(e) {
                e.preventDefault();

                // Get the created_at value or any other identifier (like an id)
                const createdAt = row.querySelector('.created-at-col').textContent.trim();

                // Check if createdAt is available
                if (!createdAt) {
                    alert('Error: Created at value is missing.');
                    return;
                }

                // Confirm deletion with the user
                if (!confirm('Are you sure you want to delete this item?')) {
                    return;
                }

                // Send AJAX request to delete the record from the database
                fetch(`/delete-barang-masuk/${createdAt}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Successfully deleted from the database, now remove it from the table
                            row.remove();
                            updateRowNumbers(); // Function to re-order the row numbers
                            saveTableToLocalStorage
                        (); // Function to save the table state to localStorage
                            updateSummaryTable(); // Function to update the summary table
                        } else {
                            alert('Failed to delete the data from the database.');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting data:', error);
                        alert('An error occurred while deleting the data.');
                    });
            });

            row.querySelector('.qty-input').addEventListener('input', function() {
                calculateJumlah(row);
                saveTableToLocalStorage();
            });

            row.querySelector('.remove-row').addEventListener('click', function(e) {
                e.preventDefault();
                row.remove();
                updateRowNumbers();
                saveTableToLocalStorage();
                updateSummaryTable();
            });
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#barangTable tbody tr:not(#template-row)');
            rowCount = 1;
            rows.forEach(row => {
                row.querySelector('td:first-child').textContent = `${rowCount}`;
                rowCount++;
            });
        }

        function calculateJumlah(row) {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const hargaText = row.querySelector('.harga-col').textContent.replace('Rp', '').replace(
                /[^0-9.-]+/g, "");
            const harga = parseFloat(hargaText) || 0;
            const jumlah = qty * harga;
            row.querySelector('.jumlah-col').textContent = isNaN(jumlah) ? 'Rp-' :
                `Rp ${jumlah.toLocaleString()}`;

            updateSummaryTable();
        }

        function updateSummaryTable() {
            const rows = document.querySelectorAll('#barangTable tbody tr:not(#template-row)');
            const categoryTotals = {
                'Dry_Goods': 0,
                'Veggies': 0,
                'Meat': 0,
                'Fruit': 0
            };

            let grandTotal = 0;

            rows.forEach(row => {
                let kategori = row.querySelector('.kategori-col').textContent.trim();
                let key = kategori.toLowerCase();

                switch (key) {
                    case 'dry goods':
                    case 'dry good':
                    case 'dry_goods':
                    case 'dry_good':
                        key = 'Dry_Goods';
                        break;
                    case 'veggies':
                        key = 'Veggies';
                        break;
                    case 'meat':
                        key = 'Meat';
                        break;
                    case 'fruit':
                        key = 'Fruit';
                        break;
                    default:
                        key = null;
                }

                const jumlahText = row.querySelector('.jumlah-col').textContent.replace('Rp', '')
                    .replace(/[^0-9.-]+/g, "");
                const jumlah = parseFloat(jumlahText) || 0;

                if (key && categoryTotals.hasOwnProperty(key)) {
                    categoryTotals[key] += jumlah;
                }

                grandTotal += jumlah;
            });

            // Update DOM
            document.getElementById('drygood-total').textContent =
                `Rp ${categoryTotals['Dry_Goods'].toLocaleString()}`;
            document.getElementById('veggies-total').textContent =
                `Rp ${categoryTotals['Veggies'].toLocaleString()}`;
            document.getElementById('meat-total').textContent = `Rp ${categoryTotals['Meat'].toLocaleString()}`;
            document.getElementById('fruit-total').textContent =
                `Rp ${categoryTotals['Fruit'].toLocaleString()}`;
            document.getElementById('grand-total').textContent = `Rp ${grandTotal.toLocaleString()}`;
            document.getElementById('control-total').textContent = `Rp ${grandTotal.toLocaleString()}`;
            document.getElementById('control-zero').textContent = `(Rp 0)`;

            // Save to localStorage
            localStorage.setItem('summaryTotals', JSON.stringify({
                dry: categoryTotals['Dry_Goods'],
                veggies: categoryTotals['Veggies'],
                meat: categoryTotals['Meat'],
                fruit: categoryTotals['Fruit'],
                total: grandTotal
            }));
        }

        function restoreSummaryTable() {
            const saved = JSON.parse(localStorage.getItem('summaryTotals'));
            if (saved) {
                document.getElementById('drygood-total').textContent = `Rp ${saved.dry.toLocaleString()}`;
                document.getElementById('veggies-total').textContent = `Rp ${saved.veggies.toLocaleString()}`;
                document.getElementById('meat-total').textContent = `Rp ${saved.meat.toLocaleString()}`;
                document.getElementById('fruit-total').textContent = `Rp ${saved.fruit.toLocaleString()}`;
                document.getElementById('grand-total').textContent = `Rp ${saved.total.toLocaleString()}`;
                document.getElementById('control-total').textContent = `Rp ${saved.total.toLocaleString()}`;
                document.getElementById('control-zero').textContent = `(Rp 0)`;
            }
        }

        function saveTableToLocalStorage() {
            const rows = [];
            document.querySelectorAll('#barangTable tbody tr:not(#template-row)').forEach(row => {
                const bahan = row.querySelector('select').value;
                const kategori = row.querySelector('.kategori-col').textContent;
                const qty = row.querySelector('.qty-input').value;
                const unit = row.querySelector('.unit-col').textContent;
                const harga = row.querySelector('.harga-col').textContent;
                const jumlah = row.querySelector('.jumlah-col').textContent;
                rows.push({
                    bahan,
                    kategori,
                    qty,
                    unit,
                    harga,
                    jumlah
                });
            });
            localStorage.setItem('barangMasukRows', JSON.stringify(rows));
        }


        function loadTableFromLocalStorage() {
            const savedRows = JSON.parse(localStorage.getItem('barangMasukRows')) || [];
            savedRows.forEach(data => {
                const templateRow = document.querySelector('#template-row');
                const newRow = templateRow.cloneNode(true);
                newRow.removeAttribute('id');
                newRow.style.display = '';
                newRow.querySelector('td:first-child').textContent = `${rowCount}`;
                newRow.querySelector('select').value = data.bahan;
                newRow.querySelector('.kategori-col').textContent = data.kategori;
                newRow.querySelector('.qty-input').value = data.qty;
                newRow.querySelector('.unit-col').textContent = data.unit;
                newRow.querySelector('.harga-col').textContent = data.harga;
                newRow.querySelector('.jumlah-col').textContent = data.jumlah;
                tableBody.appendChild(newRow);
                attachListeners(newRow);
                rowCount++;
            });
        }

        document.querySelector('.btn-danger').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will remove all rows from the table.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, clear it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    tableBody.querySelectorAll('tr:not(#template-row)').forEach(row => row
                        .remove());
                    rowCount = 1;
                    localStorage.removeItem('barangMasukRows');
                    localStorage.removeItem('summaryTotals');
                    Swal.fire('Cleared!', 'All data has been removed.', 'success');
                    updateSummaryTable();
                }
            });
        });

        document.querySelectorAll('#barangTable tbody tr').forEach((row) => {
            if (!row.id) attachListeners(row);
        });

        loadTableFromLocalStorage();
        restoreSummaryTable();
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
