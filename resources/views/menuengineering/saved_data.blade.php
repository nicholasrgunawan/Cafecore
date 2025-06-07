@include('layouts.header')
@section('title', 'Menu Engineering')

<section class="content">
    <div class="container-fluid mt-4">

        <!-- Placeholder for toolbar -->
        <div id="addBahanWrapper" class="mb-5"></div>

        <!-- ✅ Full-width DataTable -->
        <div class="table-container">
            <table id="bahanTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Kategori</th>
                        <th>Total Sold</th>
                        <th>Menu Mix</th>
                        <th>Food Cost</th>
                        <th>Sell Price</th>
                        <th>Food Cost %</th>
                        <th>Cont</th>
                        <th>Menu Cost</th>
                        <th>Total Sales</th>
                        <th>M Cont</th>
                        <th>LHCM</th>
                        <th>LHMM</th>
                        <th>MI Class</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="bahanTableBody">
                    @foreach ($details as $row)
                        <tr>
                            <td>{{ $row->menu }}</td>
                            <td>{{ $row->kategori }}</td>
                            <td>{{ $row->total_sold }}</td>
                            <td>{{ $row->menu_mix }}</td>
                            <td>{{ $row->food_cost }}</td>
                            <td>{{ $row->sell_price }}</td>
                            <td>{{ $row->food_cost_p }}</td>
                            <td>{{ $row->cont }}</td>
                            <td>{{ $row->menu_cost }}</td>
                            <td>{{ $row->total_sales }}</td>
                            <td>{{ $row->m_cont }}</td>
                            <td>{{ $row->lhcm }}</td>
                            <td>{{ $row->lhmm }}</td>
                            <td>{{ $row->mi_class }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                                <a href="#"
       class="btn-delete text-danger"
       data-id="{{ $row->id }}">   {{-- <── add data-id --}}
        <i class="fas fa-trash-alt"></i>
    </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>




            </table>

        </div>
        <!-- ✅ Centered Summary Table -->
        <div class="table-container mt-4">
            <table id="summaryTable" class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Total Quantity</th>
                        <th>Total Menu Mix (%)</th>
                        <th>Item Food Cost (Rp)</th>
                        <th>Item Sell Price (Rp)</th>
                        <th>Item Food Cost (%)</th>
                        <th>Item Contribution (Rp)</th>
                        <th>Total Menu Cost (Rp)</th>
                        <th>Total Sales (Rp)</th>
                        <th>Menu Contribution (Rp)</th>
                        <th>Potential Food Cost</th>
                        <th>Average Profit</th>
                        <th>Average Contribution</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    @if ($summary)   {{-- if a record exists --}}
        <tr>
            <td>{{ $summary->total_quantity }}</td>
            <td>{{ $summary->menu_mix }}</td>
            <td>{{ $summary->item_food_cost }}</td>
            <td>{{ $summary->item_sell_price }}</td>
            <td>{{ $summary->item_food_cost_p }}</td>
            <td>{{ $summary->item_contribution }}</td>
            <td>{{ $summary->menu_cost }}</td>
            <td>{{ $summary->total_sales }}</td>
            <td>{{ $summary->menu_contribution }}</td>
            <td>{{ $summary->potential_food_cost }}</td>
            <td>{{ $summary->average_profit }}</td>
            <td>{{ $summary->average_contribution }}</td>
            <td>{{ $summary->created_at }}</td>
            <td>
                                <a href="#"
       class="btn-delete-summary text-danger"
       data-id="{{ $summary->id }}">
        <i class="fas fa-trash-alt"></i>
    </a>
                            </td>
        </tr>
    @else
        <tr><td colspan="13" class="text-center">No summary yet</td></tr>
    @endif
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
<!-- ✅ SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.menu-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const option = this.selectedOptions[0];
                const row = this.closest('tr');

                const kategori = option.dataset.kategori || '-';
                const totalSold = option.dataset.totalSold || 0;
                const foodCost = parseFloat(option.dataset.foodCost || 0).toFixed(2);
                const sellPrice = parseFloat(option.dataset.sellPrice || 0).toFixed(2);
                const foodCostPercent = (sellPrice > 0) ? ((foodCost / sellPrice) * 100)
                    .toFixed(2) + '%' : '0%';
                const avgProfit = calculateAverageProfit();

                row.querySelector('.kategori-col').textContent = kategori;
                row.querySelector('.total-sold-col').textContent = totalSold;
                row.querySelector('.food-cost-col').textContent = foodCost;
                row.querySelector('.sell-price-col').textContent = sellPrice;
                row.querySelector('.food-cost-p-col').textContent = foodCostPercent;

                // Optional: reset other columns
                row.querySelector('.menu-mix-col').textContent = '0';
                row.querySelector('.cont-col').textContent = '0';
                row.querySelector('.menu-cost-col').textContent = '0';
                row.querySelector('.total-sales-col').textContent = '0';
                row.querySelector('.m-cont-col').textContent = '0';
                row.querySelector('.lhcm-col').textContent = '-';
                row.querySelector('.lhmm-col').textContent = '-';
                row.querySelector('.mi-class-col').textContent = '-';
            });
        });
    });

    $(document).ready(function() {
        const addButton = `
        <button id="printLaporanBtn" class="btn btn-secondary btn-sm">Print laporan</button>
        <button class="btn btn-primary btn-sm" id="importBtn">Import data</button>
        <input type="file" id="csvFileInput" accept=".csv" style="display:none" />
    `;



        table = $('#bahanTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lfr>tip',
            language: {
                emptyTable: "There are no data."
            },
            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });

        $('.dataTables_filter').after(
            `<div id="addBahanWrapper" class="add-bahan-wrapper ms-2">${addButton}</div>`);

        $(function () {
    // CSRF header for all jQuery AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // DETAILS table – delegated click handler
    $('#bahanTable').on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const $row = $(this).closest('tr');
        const id   = $(this).data('id');          // comes from data-id in Blade

        Swal.fire({
            title: 'Delete this record?',
            text: 'This will remove it permanently from the database.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '{{ route("menuengineerings.destroy", ":id") }}'.replace(':id', id),
                type: 'DELETE',
                success() {
                    // If you used DataTables:
                    const dt = $('#bahanTable').DataTable();
                    dt.row($row).remove().draw(false);

                    // If not using DataTables, just: $row.remove();
                    Swal.fire('Deleted!', 'The record has been removed.', 'success');
                },
                error(xhr) {
                    Swal.fire('Error', 'Failed to delete: ' + xhr.statusText, 'error');
                }
            });
        });
    });

    // SUMMARY row – same idea (only if you really want to delete it)
    $('#summaryTable').on('click', '.btn-delete-summary', function (e) {
        e.preventDefault();
        const $row = $(this).closest('tr');
        const id   = $(this).data('id');

        Swal.fire({
            title: 'Delete this summary?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '{{ route("menuengineeringsummary.destroy", ":id") }}'.replace(':id', id),
                type: 'DELETE',
                success() {
                    $row.remove();
                    Swal.fire('Deleted!', 'Summary deleted.', 'success');
                },
                error(xhr) {
                    Swal.fire('Error', 'Failed to delete summary.', 'error');
                }
            });
        });
    });
});

        $('#save-btn').on('click', function(e) {
            e.preventDefault();

            let validationErrors = [];

            $('#bahanTableBody tr').each(function(index) {
                const row = $(this);
                const menu = row.find('select.menu-select').val();
                const totalSold = row.find('.total-sold-col').text().trim();
                const foodCost = row.find('.food-cost-col').text().trim();
                const sellPrice = row.find('.sell-price-col').text().trim();

                if (!menu) {
                    validationErrors.push(`Row ${index + 1}: Menu is empty.`);
                }
                if (!totalSold || isNaN(totalSold)) {
                    validationErrors.push(
                    `Row ${index + 1}: Total Sold is missing or invalid.`);
                }
                if (!foodCost || isNaN(foodCost)) {
                    validationErrors.push(`Row ${index + 1}: Food Cost is missing or invalid.`);
                }
                if (!sellPrice || isNaN(sellPrice)) {
                    validationErrors.push(
                    `Row ${index + 1}: Sell Price is missing or invalid.`);
                }
            });

            if (validationErrors.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `<ul style="text-align:left;">${validationErrors.map(e => `<li>${e}</li>`).join('')}</ul>`
                });
                return; // stop saving
            }

            // If passed, show confirm dialog
            Swal.fire({
                title: 'Save data?',
                text: "Are you sure you want to save?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let mainData = [];

                    $('#bahanTableBody tr').each(function() {
                        const row = $(this);
                        mainData.push({
                            id: row.data('id') || null,
                            menu: row.find('select.menu-select').val(),
                            kategori: row.find('.kategori-col').text(),
                            total_sold: parseInt(row.find('.total-sold-col')
                                .text()) || 0,
                            menu_mix: parseFloat(row.find('.menu-mix-col')
                            .text()) || 0,
                            food_cost: parseFloat(row.find('.food-cost-col')
                                .text()) || 0,
                            sell_price: parseFloat(row.find('.sell-price-col')
                                .text()) || 0,
                            food_cost_p: row.find('.food-cost-p-col').text(),
                            cont: parseFloat(row.find('.cont-col').text()) || 0,
                            menu_cost: parseFloat(row.find('.menu-cost-col')
                                .text()) || 0,
                            total_sales: parseFloat(row.find('.total-sales-col')
                                .text()) || 0,
                            m_cont: parseFloat(row.find('.m-cont-col')
                            .text()) || 0,
                            lhcm: row.find('.lhcm-col').text(),
                            lhmm: row.find('.lhmm-col').text(),
                            mi_class: row.find('.mi-class-col').text(),
                            created_at: new Date().toISOString(),
                            updated_at: new Date().toISOString()
                        });
                    });

                    $.ajax({
                        url: '{{ route('menu-engineerings.save') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            data: mainData
                        },
                        success: function(response) {
                            Swal.fire('Saved!', 'Main data has been saved.',
                                'success');
                        },
                        error: function(xhr) {
                            // Better error handling
                            let errorMessage = 'Failed to save main data.';
                            try {
                                const res = JSON.parse(xhr.responseText);
                                if (res.message) errorMessage = res.message;
                            } catch (e) {}

                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });





        // Add row functionality
        $(document).on('click', '#addMenuBtn', function() {
            const newRow = `
            <tr>
                <td>
                    <select name="menu" class="form-select menu-select">
                        ${menuOptions}
                    </select>
                </td>
                <td class="kategori-col">-</td>
                <td class="total-sold-col">0</td>
                <td class="menu-mix-col">0</td>
                <td class="food-cost-col">0</td>
                <td class="sell-price-col">0</td>
                <td class="food-cost-p-col">0%</td>
                <td class="cont-col">0</td>
                <td class="menu-cost-col">0</td>
                <td class="total-sales-col">0</td>
                <td class="m-cont-col">0</td>
                <td class="lhcm-col">0</td>
                <td class="lhmm-col">0</td>
                <td class="mi-class-col">-</td>
                <td><a href="#" class="text-danger remove-row"><i class="fas fa-trash-alt"></i></a></td>
            </tr>
        `;
            $('#bahanTableBody').append(newRow);
            updateMenuMix();
            updateCont();
            updateAverageProfit();
            updateTotalMCont(); // <-- add here to update summary total M Cont
            updateLHCM();
            updateAverageContribution();
            updateLHMM();
            updateMIClass();
            calculateTotals();
            updateSummary()
            updateTotalMenuMix()
            calculateSimpleTotals()
            calculateFoodCostPercentTotal();

        });

        // Remove row
        $(document).on('click', '.remove-row', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            updateTotalQuantity(); // <-- update after removing row
            updateMenuMix();
            updateCont();
            updateAverageProfit();
            updateTotalMCont(); // <-- add here to update summary total M Cont
            updateAverageContribution();
            updateLHMM();
            updateMIClass();
            calculateTotals();
            updateSummary()
            updateTotalMenuMix()
            calculateSimpleTotals()
            calculateFoodCostPercentTotal();

        });

        // Dropdown listener
        $(document).on('change', '.menu-select', function() {
            const option = this.selectedOptions[0];
            const row = $(this).closest('tr');

            const kategori = option.dataset.kategori || '-';
            const totalSold = option.dataset.totalSold || 0;
            const foodCost = parseFloat(option.dataset.foodCost || 0).toFixed(2);
            const sellPrice = parseFloat(option.dataset.sellPrice || 0).toFixed(2);
            const foodCostPercent = sellPrice > 0 ? ((foodCost / sellPrice) * 100).toFixed(2) : '0';

            row.find('.kategori-col').text(kategori);
            row.find('.total-sold-col').text(totalSold);
            row.find('.food-cost-col').text(foodCost);
            row.find('.sell-price-col').text(sellPrice);
            row.find('.food-cost-p-col').text(foodCostPercent);

            // Optional resets
            row.find(
                '.menu-mix-col, .cont-col, .menu-cost-col, .total-sales-col, .m-cont-col, .lhcm-col, .lhmm-col, .mi-class-col'
            ).text('0');

            updateTotalQuantity();
            updateMenuMix();
            updateCont();
            updateAverageProfit();
            updateTotalMCont();
            updateLHCM();
            updateAverageContribution();
            updateLHMM();
            updateMIClass();
            calculateTotals();
            updateSummary()
            updateTotalMenuMix()
            calculateSimpleTotals()
            calculateFoodCostPercentTotal();

        });
    });

    function loadLatestSummary() {
        $.ajax({
            url: '{{ route('menu-engineering.getLatestSummary') }}', // make sure you have this route!
            method: 'GET',
            success: function(data) {
                $('#summaryTotalQuantity').text(data.total_quantity);
                $('#summaryMenuMix').text(parseFloat(data.menu_mix).toFixed(2) + '%');
                $('#summaryItemFoodCost').text(formatCurrency(data.item_food_cost));
                $('#summaryItemSellPrice').text(formatCurrency(data.item_sell_price));
                $('#summaryItemFoodCostPercent').text(data.item_food_cost_p);
                $('#summaryItemContribution').text(formatCurrency(data.item_contribution));
                $('#summaryMenuCost').text(formatCurrency(data.menu_cost));
                $('#summaryTotalSales').text(formatCurrency(data.total_sales));
                $('#summaryMenuContribution').text(formatCurrency(data.menu_contribution));
                $('#summaryPotentialFoodCost').text(data.potential_food_cost);
                $('#summaryAverageProfit').text(data.average_profit);
                $('#summaryAverageContribution').text(data.average_contribution);

            },
            error: function() {
                console.error('Failed to load summary');
            }
        });
    }

    $(document).ready(function() {
        loadLatestSummary();
    });

    function updateTotalQuantity() {
        let totalQuantity = 0;

        $('#bahanTableBody tr').each(function() {
            // Get the text of .total-sold-col, parse to int
            let val = parseInt($(this).find('.total-sold-col').text()) || 0;
            totalQuantity += val;
        });

        // Update the "Total Quantity" field in the summary table
        // It is the first row with text 'Total Quantity', last cell (7th cell)
        $('#summaryTable tbody tr').each(function() {
            const firstCellText = $(this).find('td:first').text().trim();
            if (firstCellText === 'Total Quantity') {
                // update last td with the totalQuantity
                $(this).find('td:last').text(totalQuantity);
            }
        });
    }

    function updateMenuMix() {
        let totalQuantity = 0;

        // First sum total sold for all rows
        $('#bahanTableBody tr').each(function() {
            let val = parseInt($(this).find('.total-sold-col').text()) || 0;
            totalQuantity += val;
        });

        // Update total quantity in summary table
        $('#summaryTable tbody tr').each(function() {
            const firstCellText = $(this).find('td:first').text().trim();
            if (firstCellText === 'Total Quantity') {
                $(this).find('td:last').text(totalQuantity);
            }
        });

        // Now update each row's menu mix %
        $('#bahanTableBody tr').each(function() {
            let rowTotalSold = parseInt($(this).find('.total-sold-col').text()) || 0;
            let menuMix = totalQuantity > 0 ? ((rowTotalSold / totalQuantity) * 100).toFixed(2) : '0%';
            $(this).find('.menu-mix-col').text(menuMix);
        });
    }

    function updateCont() {
        let totalSalesAll = 0;

        $('#bahanTableBody tr').each(function() {
            let sellPrice = parseFloat($(this).find('.sell-price-col').text()) || 0;
            let totalSold = parseInt($(this).find('.total-sold-col').text()) || 0;
            let foodCost = parseFloat($(this).find('.food-cost-col').text()) || 0;

            // Calculate total sales
            let totalSales = sellPrice * totalSold;
            $(this).find('.total-sales-col').text(totalSales.toFixed(2));

            // Calculate menu cost = food cost * total sold
            let menuCost = foodCost * totalSold;
            $(this).find('.menu-cost-col').text(menuCost.toFixed(2));

            // Calculate contribution = sell price - food cost (for display)
            let contValue = sellPrice - foodCost;
            $(this).find('.cont-col').text(contValue.toFixed(2));

            // Calculate M Cont = Total Sales - Menu Cost
            let mCont = totalSales - menuCost;
            $(this).find('.m-cont-col').text(mCont.toFixed(2));

            totalSalesAll += totalSales;
        });

        updateAverageProfit();

        updateTotalMCont(); // <--- ADD THIS HERE
    }

    // Call this to update the summary table cell with average profit percentage
    function formatRupiah(number) {
        return 'Rp ' + number.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateAverageProfit() {
        let totalMCont = 0;
        let totalQuantity = 0;

        $('#bahanTableBody tr').each(function() {
            const mCont = parseFloat($(this).find('.m-cont-col').text()) || 0;
            const qty = parseFloat($(this).find('.total-sold-col').text()) || 0;

            totalMCont += mCont;
            totalQuantity += qty;
        });

        let avgProfitPerUnit = 0;
        if (totalQuantity > 0) {
            avgProfitPerUnit = totalMCont / totalQuantity;
        }

        $('#summaryTable tbody tr').each(function() {
            if ($(this).find('td:first').text().trim() === 'Average Profit') {
                $(this).find('td:last').text(formatRupiah(avgProfitPerUnit));
            }
        });
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateTotalMCont() {
        let totalMCont = 0;

        $('#bahanTableBody tr').each(function() {
            const mContText = $(this).find('.m-cont-col').text();
            const mCont = parseFloat(mContText) || 0;
            totalMCont += mCont;
        });

        $('#summaryTable tbody tr').each(function() {
            if ($(this).find('td:first').text().trim() === 'Menu Contribution') {
                $(this).find('td:last').text(formatRupiah(totalMCont));
            }
        });
    }


    // Assuming `table` is your DataTable instance

    function updateLHCM() {
        let totalCont = 0;
        let rowCount = 0;

        // Sum up the Cont column (index 7 in your table, zero-based)
        $('#bahanTableBody tr').each(function() {
            const contText = $(this).find('.cont-col').text();
            const contValue = parseFloat(contText) || 0;
            totalCont += contValue;
            rowCount++;
        });

        const avgCont = rowCount > 0 ? totalCont / rowCount : 0;

        // Update LHCM column based on Cont < avgCont
        $('#bahanTableBody tr').each(function() {
            const contText = $(this).find('.cont-col').text();
            const contValue = parseFloat(contText) || 0;

            const lhcmValue = contValue < avgCont ? 'L' : 'H';
            $(this).find('.lhcm-col').text(lhcmValue);
        });
    }

    function updateAverageContribution() {
        let totalSoldSum = 0;

        $('#bahanTableBody tr').each(function() {
            const totalSold = parseInt($(this).find('.total-sold-col').text()) || 0;
            totalSoldSum += totalSold;
        });

        let avgContributionPercent = 0;
        if (totalSoldSum > 0) {
            avgContributionPercent = (1 / totalSoldSum) * 70 * 100; // multiply by 100 for percentage
        }

        avgContributionPercent = avgContributionPercent.toFixed(2) + '%';

        $('#summaryTable tbody tr').each(function() {
            if ($(this).find('td:first').text().trim() === 'Average Contribution') {
                $(this).find('td:last').text(avgContributionPercent);
            }
        });
    }

    function updateLHMM() {
        // LHMM logic - for example, based on Menu Contribution (m-cont-col) average

        let totalMCont = 0;
        let rowCount = 0;

        $('#bahanTableBody tr').each(function() {
            let mCont = parseFloat($(this).find('.m-cont-col').text()) || 0;
            totalMCont += mCont;
            rowCount++;
        });

        let avgMCont = rowCount > 0 ? totalMCont / rowCount : 0;

        $('#bahanTableBody tr').each(function() {
            let mCont = parseFloat($(this).find('.m-cont-col').text()) || 0;
            let lhmmVal = mCont < avgMCont ? 'L' : 'H';
            $(this).find('.lhmm-col').text(lhmmVal);
        });
    }


    function updateMIClass() {
        // MI Class logic - for example, based on LHCM and LHMM values

        $('#bahanTableBody tr').each(function() {
            let lhcm = $(this).find('.lhcm-col').text().trim();
            let lhmm = $(this).find('.lhmm-col').text().trim();

            let miClass = '-';

            if (lhcm === 'H' && lhmm === 'H') {
                miClass = 'Winner';
            } else if (lhcm === 'H' && lhmm === 'L') {
                miClass = 'Sleeper';
            } else if (lhcm === 'L' && lhmm === 'H') {
                miClass = 'Runner';
            } else if (lhcm === 'L' && lhmm === 'L') {
                miClass = 'Looser';
            }

            $(this).find('.mi-class-col').text(miClass);
        });
    }

    function calculateTotals() {
        let totalQuantity = 0;
        let totalMenuCost = 0;
        let totalSales = 0;

        document.querySelectorAll('#bahanTableBody tr').forEach(row => {
            // Parse values from cells; use 0 fallback
            const qty = parseFloat(row.querySelector('.total-sold-col').textContent) || 0;
            const menuCost = parseFloat(row.querySelector('.menu-cost-col').textContent) || 0;
            const sales = parseFloat(row.querySelector('.total-sales-col').textContent) || 0;

            totalQuantity += qty;
            totalMenuCost += menuCost;
            totalSales += sales;
        });

        // Update the summary table cells by ID
        function formatRupiah(number) {
            return 'Rp ' + number.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // In your totals update:
        document.getElementById('summaryTotalQuantity').textContent = Math.round(totalQuantity);
        document.getElementById('summaryTotalMenuCost').textContent = formatRupiah(totalMenuCost);
        document.getElementById('summaryTotalSales').textContent = formatRupiah(totalSales);

    }


    function updateSummary() {
        // Get Total Sales and Total Menu Cost from summary table
        let totalSalesText = $('#summaryTable tbody tr').filter(function() {
            return $(this).find('td:first').text().trim() === 'Total Sales';
        }).find('td:last').text().replace(/[^0-9]/g, ''); // Remove non-digits

        let totalMenuCostText = $('#summaryTable tbody tr').filter(function() {
            return $(this).find('td:first').text().trim() === 'Total Menu Cost';
        }).find('td:last').text().replace(/[^0-9]/g, '');

        let totalSales = parseInt(totalSalesText) || 0;
        let totalMenuCost = parseInt(totalMenuCostText) || 0;

        // Calculate Potential Food Cost %
        let potentialFoodCostPercent = 0;
        if (totalSales !== 0) {
            potentialFoodCostPercent = ((totalSales - totalMenuCost) / totalSales) * 100;
        }

        // Update the Potential Food Cost row
        $('#summaryTable tbody tr').each(function() {
            if ($(this).find('td:first').text().trim() === 'Potential Food Cost') {
                $(this).find('td:last').text(potentialFoodCostPercent.toFixed(2) + '%');
            }
        });
    }

    function updateTotalMenuMix() {
        let totalMix = 0;

        // add up every “Menu Mix” cell (strip the % sign)
        $('#bahanTableBody .menu-mix-col').each(function() {
            const num = parseFloat($(this).text().replace('%', '')) || 0;
            totalMix += num;
        });

        // drop result into the summary cell
        $('#summaryTotalMenuMix').text(totalMix.toFixed(2) + '%');
    }

    function calculateSimpleTotals() {
        let totalFoodCost = 0;
        let totalSellPrice = 0;
        let totalContribution = 0;

        function parseNumber(value) {
            return parseFloat(value?.toString().replace(/[^0-9.-]+/g, '')) || 0;
        }

        function formatRupiah(number) {
            return 'Rp ' + number.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.querySelectorAll('#bahanTableBody tr').forEach(row => {
            totalFoodCost += parseNumber(row.querySelector('.food-cost-col')?.textContent);
            totalSellPrice += parseNumber(row.querySelector('.sell-price-col')?.textContent);
            totalContribution += parseNumber(row.querySelector('.cont-col')?.textContent);
        });

        document.getElementById('summaryItemFoodCost').textContent = formatRupiah(totalFoodCost);
        document.getElementById('summaryItemSellPrice').textContent = formatRupiah(totalSellPrice);
        document.getElementById('summaryItemContribution').textContent = formatRupiah(totalContribution);
    }

    function calculateFoodCostPercentTotal() {
        let totalFoodCostPercent = 0;

        document.querySelectorAll('#bahanTableBody tr').forEach(row => {
            // Get the text like "12.34%", remove % sign, parse to float
            const percentText = row.querySelector('.food-cost-p-col')?.textContent || '0%';
            const value = parseFloat(percentText.replace('%', '')) || 0;
            totalFoodCostPercent += value;
        });

        // Optional: If you want to show with 2 decimal places and % sign
        document.getElementById('summaryItemFoodCostPercent').textContent = totalFoodCostPercent.toFixed(2) + '%';
    }
</script>








<style>
    /* ✅ Stretch tables full-width */
    .table-container {
        width: 100%;
        margin-bottom: 2rem;
        padding: 0;
    }

    .table-container table {
        width: 100%;
        table-layout: auto;
        /* Or 'fixed' if you want equal cell widths */
    }

    /* ✅ Wrap entries + search nicely */
    .dataTables_wrapper>.d-flex {
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
        display: flex;
        align-items: center;
    }

    /* ✅ Add Bahan buttons align right */
    .add-bahan-wrapper {
        margin-left: auto;
    }

    .add-bahan-wrapper button i {
        margin-right: 5px;
    }

    /* ✅ Table row styling */
    #bahanTable tbody tr:nth-child(even) {
        background-color: #c7e1df;
    }

    #bahanTable tbody tr:hover {
        background-color: #dbe9e8;
    }
</style>
