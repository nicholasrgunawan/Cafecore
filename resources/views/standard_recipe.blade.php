<!-- resources/views/barangmasuk.blade.php -->

@include('layouts.header')
@section('title', 'Konversi Harga Bahan')

<section class="content">
    <div class="container mt-4">

        <!-- Placeholder for toolbar -->
        <div id="toolbarWrapper" class="mb-2"></div>

        <!-- Main Table -->
        <table id="bahanTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bahan</th>
                    <th>Unit 1</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Unit 2</th>
                    <th>% Waste</th>
                    <th>Qty Waste</th>
                    <th>% Use</th>
                    <th>Qty Use</th>
                    <th>Conv</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <tr id="template-row" style="display: none">
                    <td>#</td>
                    <td>
                        <select name="bahan" class="form-select bahan-select">
                            <option disabled selected>Select Bahan</option>
                            @foreach ($bahans as $bahan)
                                <option value="{{ $bahan }}">{{ $bahan }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="unit-col">-</td>
                    <td class="harga-col">-</td>
                    <td><input type="number" class="form-control form-control-sm qty-input" placeholder="Qty"></td>
                    <td>
                        <select class="form-control form-control-sm unit2-input">
                            <option value="">Select</option>
                            <option value="Gr">Gr</option>
                            <option value="Kg">Kg</option>
                            <option value="Ton">Ton</option>
                            <option value="Portion">Portion</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control form-control-sm p_waste-input" placeholder="% Waste">
                    </td>
                    <td><span class="qty_waste-col">-</span></td>
                    <td><input type="number" class="form-control form-control-sm p_use-input" placeholder="% Use"></td>
                    <td><span class="qty_use-col">-</span></td>
                    <td><span class="conv-col">-</span></td>
                    <td><a href="#" class="text-danger remove-row"><i class="fas fa-trash-alt"></i></a></td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    // Define your unit hierarchy (for kilo to grams etc)
const unitHierarchy = ["Gr", "Kg", "Ton"];

// Portion conversion rates (1 Portion = 0.48 Kg = 480 Gr)
const portionConversionRates = {
    "Gr": 1 / 480,    // 1 Portion = 480 Grams
    "Kg": 1 / 0.48,   // 1 Portion = 0.48 Kg
    "Ton": 1 / 0.00048 // 1 Portion = 0.00048 Ton
};

// Helper to format number to Rupiah currency style (Rp)
function formatRupiah(angka) {
    let number_string = angka.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return "Rp" + number_string;
}

function recalculateRow($row) {
    const hargaText = $row.find('.harga-col').text();
    const harga = parseFloat(hargaText.replace(/[^\d.]/g, '')) || 0;

    const qty = parseFloat($row.find('.qty-input').val()) || 0;
    const pWaste = parseFloat($row.find('.p_waste-input').val()) || 0;
    const pUse = 100 - pWaste;
    const qtyWaste = (qty * pWaste) / 100;
    const qtyUse = qty - qtyWaste;

    console.log('hargaText:', hargaText);
    console.log('harga:', harga);
    console.log('qty:', qty);
    console.log('pWaste:', pWaste);
    console.log('qtyWaste:', qtyWaste);
    console.log('qtyUse:', qtyUse);

    let conv = 0;
    if (qtyUse > 0) {
        conv = harga / qtyUse;
    }

    console.log('conv:', conv);

    $row.find('.qty_waste-col').text(qtyWaste.toFixed(0));
    $row.find('.qty_use-col').text(qtyUse.toFixed(0));
    $row.find('.p_use-input').val(pUse.toFixed(2));
    $row.find('.conv-col').text(conv.toFixed(2));
}







    function updateRowNumbers() {
        const rows = document.querySelectorAll('#bahanTable tbody tr:not(#template-row)');
        let count = 1;
        rows.forEach(row => {
            row.querySelector('td:first-child').textContent = count++;

        });
    }

    function saveTableToLocalStorage() {
        const rows = [];
        $('#bahanTable tbody tr:not(#template-row)').each(function() {
            const $row = $(this);
            rows.push({
                bahan: $row.find('select').val(),
                unit: $row.find('.unit-col').text(),
                harga: $row.find('.harga-col').text(),
                qty: $row.find('.qty-input').val(),
                unit2: $row.find('.unit2-input').val(),
                p_waste: $row.find('.p_waste-input').val(),
                qty_waste: $row.find('.qty_waste-col').text(),
                p_use: $row.find('.p_use-input').val(),
                qty_use: $row.find('.qty_use-col').text(),
                conv: $row.find('.conv-col').text()
            });
        });
        localStorage.setItem('standardRecipeRows', JSON.stringify(rows));
    }

    function loadTableFromLocalStorage() {
        const savedRows = JSON.parse(localStorage.getItem('standardRecipeRows')) || [];
        savedRows.forEach(data => {
            const newRow = $('#template-row').clone().removeAttr('id').show();
            newRow.find('td:first').text($('#bahanTable tbody tr:not(#template-row)').length + 1);
            newRow.find('select').val(data.bahan);
            newRow.find('.unit-col').text(data.unit);
            newRow.find('.harga-col').text(data.harga);
            newRow.find('.qty-input').val(data.qty);
            newRow.find('.unit2-input').val(data.unit2);
            newRow.find('.p_waste-input').val(data.p_waste);
            newRow.find('.qty_waste-col').text(data.qty_waste);
            newRow.find('.p_use-input').val(data.p_use);
            newRow.find('.qty_use-col').text(data.qty_use);
            newRow.find('.conv-col').text(data.conv);
            $('#bahanTable tbody').append(newRow);
            attachListeners(newRow);
        });
    }

    function attachListeners(row) {
        row.find('select.bahan-select').on('change', function() {
            const selectedBahan = this.value;
            fetch(`/get-bahan-details/${selectedBahan}`)
                .then(res => res.json())
                .then(data => {
                    row.find('.unit-col').text(data.unit);
                    row.find('.harga-col').text(data.harga);
                    saveTableToLocalStorage();
                })
                .catch(err => console.error('Error fetching bahan details:', err));
        });

        row.find('.qty-input').on('input', function() {
            recalculateRow(row);
            saveTableToLocalStorage();
        });

        row.find('.p_waste-input').on('input', function() {
    let pWaste = Math.min(Math.max(parseFloat($(this).val()) || 0, 0), 100);
    const pUseInput = row.find('.p_use-input');
    pUseInput.val((100 - pWaste).toFixed(2));
    recalculateRow(row);
    saveTableToLocalStorage();
});

row.find('.p_use-input').on('input', function() {
    let pUse = Math.min(Math.max(parseFloat($(this).val()) || 0, 0), 100);
    const pWasteInput = row.find('.p_waste-input');
    pWasteInput.val((100 - pUse).toFixed(2));
    recalculateRow(row);
    saveTableToLocalStorage();
});


        row.find('.unit2-input').on('focus', function () {
    const baseUnit = row.find('.unit-col').text().trim();
    const baseIndex = unitHierarchy.indexOf(baseUnit);
    $(this).empty().append('<option value="">Select</option>');

    // Always allow "Portion" option
    $(this).append(`<option value="Portion">Portion</option>`);

    if (baseIndex === -1) return;

    for (let i = 0; i <= baseIndex; i++) {
        $(this).append(`<option value="${unitHierarchy[i]}">${unitHierarchy[i]}</option>`);
    }
});



        row.find('.remove-row').on('click', function(e) {
            e.preventDefault();
            row.remove();
            updateRowNumbers();
            saveTableToLocalStorage();
        });
    }

    $(document).ready(function() {
        // Toolbar
        const toolbarButtons = `
    <a href="{{ route('standardrecipe.saved_data') }}">
        <button class="btn btn-primary btn-sm">View Data</button>
    </a>
    <button class="btn btn-success btn-sm" id="saveDataBtn">Save</button>
    <button class="btn btn-danger btn-sm">Clear data</button>
    <button id="addBahanBtn" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add bahan
    </button>
`;


        if (!$.fn.dataTable.isDataTable('#bahanTable')) {
            $('#bahanTable').DataTable({
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
                `<div class="toolbar-wrapper ms-2 d-flex gap-2">${toolbarButtons}</div>`);
        }

        // Add Row
        $('#addBahanBtn').on('click', function() {
            const newRow = $('#template-row').clone().removeAttr('id').show();
            newRow.find('td:first').text($('#bahanTable tbody tr:not(#template-row)').length + 1);
            $('#bahanTable tbody').append(newRow);
            attachListeners(newRow);
            saveTableToLocalStorage();
        });

        // Clear Data
        $('.btn-danger').on('click', function() {
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
                    $('#bahanTable tbody tr:not(#template-row)').remove();
                    localStorage.removeItem('standardRecipeRows');
                    Swal.fire('Cleared!', 'All data has been removed.', 'success');
                }
            });
        });

         function parseRupiahToFloat(rupiahStr) {
    let numberStr = rupiahStr.replace(/[^0-9,]/g, '').trim();
    numberStr = numberStr.replace(/\./g, '');
    numberStr = numberStr.replace(/,/g, '.');
    return parseFloat(numberStr) || 0;
}




        // Save Data
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
            // Collect each row's data
            const rows = [];
            document.querySelectorAll('#bahanTable tbody tr:not(#template-row)').forEach(row => {
                rows.push({
                    bahan: row.querySelector('select').value,
                    unit1: row.querySelector('.unit-col').textContent,
                    harga: row.querySelector('.harga-col').textContent.replace(/[^\d.]/g, ''),
                    qty: row.querySelector('.qty-input').value,
                    unit2: row.querySelector('.unit2-input').value,
                    p_waste: row.querySelector('.p_waste-input').value,
                    qty_waste: row.querySelector('.qty_waste-col').textContent,
                    p_use: row.querySelector('.p_use-input').value,
                    qty_use: row.querySelector('.qty_use-col').textContent,
                    conv: parseFloat(row.querySelector('.conv-col').textContent) || 0,

                });
            });

            // Optional: log for debugging
            console.log('Sending standard recipe rows:', rows);

            $.ajax({
                url: '/save-standard-recipe',
                method: 'POST',
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    items: rows
                },
                success: function() {
                    Swal.fire({
                        title: 'Saved!',
                        text: 'Your data has been saved.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Optional: refresh page or redirect
                    // location.reload();
                },
                error: function(xhr) {
                    console.error('Save error:', xhr.responseText);
                    Swal.fire('Error', 'Failed to save data.', 'error');
                }
            });
        }
    });
});



        // Initial load
        $('#bahanTable tbody tr:not(#template-row)').each(function() {
            attachListeners($(this));
        });

        loadTableFromLocalStorage();
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
    #bahanTable tbody tr:nth-child(even) {
        background-color: #c7e1df;
    }

    #bahanTable tbody tr:hover {
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
