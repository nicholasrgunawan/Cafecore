<!-- resources/views/barangmasuk.blade.php -->

@include('layouts.header')
@section('title', 'HPP Food')

<section class="content">
    <div class="container mt-4">

        <!-- Placeholder for toolbar -->
        <div id="toolbarWrapper" class="mb-2"></div>

        <!-- Main Table -->
        <table id="bahanTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kategori</th>
                    <th>Menu</th>
                    <th>HPP</th>
                    <th>HJP</th>
                    <th>HJP NETT (+15%)</th>
                    <th>% Cost</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <tr id="template-row" style="display: none">
                    <td class="id-col">-</td>
                    <td class="kategori-col">-</td>
                    <td>
                        <select name="menu" class="form-select menu-select">
                            <option disabled selected>Select Menu</option>
                            @foreach ($menus as $menu)
                                <option value="{{ $menu['id'] }}" data-kategori="{{ $menu['kategori'] }}"
                                    data-hpp="{{ $menu['hpp'] }}">
                                    {{ $menu['name'] }}
                                </option>
                            @endforeach
                        </select>

                    </td>
                    <td class="HPP-col">-</td>
                    <td class="HJP-col"><input type="number"></td>
                    <td class="HJP_nett-col">-</td>
                    <td class="p_cost-col">-</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

    function saveTableToLocalStorage() {
    const rowsData = [];
    $('#bahanTable tbody tr:not(#template-row)').each(function() {
        const row = $(this);
        const menuId = row.find('select.menu-select').val();
        const kategori = row.find('.kategori-col').text();
        const hppRaw = row.find('.HPP-col').data('raw-hpp') || 0;
        const hjpValue = row.find('.HJP-col input').val();
        const hjpNett = row.find('.HJP_nett-col').text();
        const pCost = row.find('.p_cost-col').text();

        rowsData.push({
            menuId,
            kategori,
            hppRaw,
            hjpValue,
            hjpNett,
            pCost
        });
    });

    localStorage.setItem('bahanTableData', JSON.stringify(rowsData));
}

function loadTableFromLocalStorage() {
    const storedData = localStorage.getItem('bahanTableData');
    if (!storedData) return;

    const rowsData = JSON.parse(storedData);
    const tbody = $('#bahanTable tbody');

    // Clear existing rows except the template
    tbody.find('tr:not(#template-row)').remove();

    rowsData.forEach((data, index) => {
        const newRow = $('#template-row').clone().removeAttr('id').show();
        newRow.find('td:first').text(index + 1);

        // Set menu select value and trigger change to update kategori & hpp
        newRow.find('select.menu-select').val(data.menuId).trigger('change');

        // Set HPP raw value & formatted text
        newRow.find('.HPP-col').data('raw-hpp', parseFloat(data.hppRaw));
        newRow.find('.HPP-col').text(formatRupiah(parseFloat(data.hppRaw)));

        // Set HJP input value
        newRow.find('.HJP-col input').val(data.hjpValue);

        // Set HJP nett and % cost texts
        newRow.find('.HJP_nett-col').text(data.hjpNett);
        newRow.find('.p_cost-col').text(data.pCost);

        // Attach event listeners
        attachListeners(newRow);

        tbody.append(newRow);
    });
}


    function formatRupiah(amount) {
    if (isNaN(amount) || amount === null) return '';
    return 'Rp ' + amount.toFixed(2)  // two decimals
        .replace('.', ',')           // replace decimal point with comma
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // thousand separator as dot
}


    function calculatePercentCost(row) {
        const hpp = row.find('.HPP-col').data('raw-hpp') || 0;
        const hjpValue = parseFloat(row.find('.HJP-col input').val()) || 0;

        if (hjpValue === 0) {
            row.find('.p_cost-col').text('');
        } else {
            const percentCost = (hpp / hjpValue) * 100;
            row.find('.p_cost-col').text(percentCost.toFixed(2) + '%');
        }
    }


    function calculateHjpNett(row) {
    const hjpInput = row.find('.HJP-col input');
    const hjpValue = parseFloat(hjpInput.val()) || 0;
    const hjpNett = hjpValue + (hjpValue * 0.15);
    
    row.find('.HJP_nett-col').data('raw-hjp-nett', hjpNett);  // store raw number
    row.find('.HJP_nett-col').text(formatRupiah(hjpNett));

    calculatePercentCost(row);
}



    function updateRowNumbers() {
        const rows = document.querySelectorAll('#bahanTable tbody tr:not(#template-row)');
        let count = 1;
        rows.forEach(row => {
            row.querySelector('td:first-child').textContent = count++;

        });
    }

    function attachListeners(row) {
    row.find('select.menu-select').on('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const kategori = selectedOption.getAttribute('data-kategori');
        const hpp = selectedOption.getAttribute('data-hpp');

        row.find('.kategori-col').text(kategori);
        row.find('.HPP-col').data('raw-hpp', parseFloat(hpp));
        row.find('.HPP-col').text(formatRupiah(parseFloat(hpp)));

        saveTableToLocalStorage();  // Save after change
    });

    row.find('.HJP-col input').on('input', function() {
        calculateHjpNett(row);
        saveTableToLocalStorage();  // Save after change
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
    <a href="{{ route('hppfood.saved_data') }}">
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
            const rows = [];

$('#bahanTable tbody tr:not(#template-row)').each(function() {
    const row = $(this);
    const menuSelect = row.find('select.menu-select option:selected');

    rows.push({
        kategori: row.find('.kategori-col').text(),
        menu: menuSelect.text().trim(),
        hpp: parseFloat(row.find('.HPP-col').data('raw-hpp')) || 0,
        hjp: parseFloat(row.find('.HJP-col input').val()) || 0,
        hjp_nett: parseIndoCurrencyToNumber(row.find('.HJP_nett-col').text()) || 0,
        percent_cost: parseFloat(row.find('.p_cost-col').text().replace('%', '')) || 0
    });
});

function parseIndoCurrencyToNumber(value) {
    if (!value) return 0;
    // Remove Rp and spaces first, then replace thousand and decimal separators
    const cleaned = value.replace(/Rp\s?/g, '').replace(/\./g, '').replace(',', '.');
    return parseFloat(cleaned) || 0;
}



            $.ajax({
                url: '/save-hpp-food',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
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
