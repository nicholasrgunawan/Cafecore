@include('layouts.header')
@section('title', 'Edit Menu')

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
                        <h3 class="card-title">Edit Product</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('menu.update', $menu->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputMenu">Menu</label>
                                <input type="text" class="form-control" id="exampleInputMenu" name="menu"
                                    value="{{ old('menu', $menu->menu) }}" placeholder="Enter name" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputKategori">Kategori</label>
                                <select class="form-control" id="exampleInputKategori" name="kategori" required>
                                    <option value="Japanese Food"
                                        {{ $menu->kategori == 'Japanese Food' ? 'selected' : '' }}>Japanese Food
                                    </option>
                                    <option value="Indonesian Food"
                                        {{ $menu->kategori == 'Indonesian Food' ? 'selected' : '' }}>Indonesian Food
                                    </option>
                                    <option value="Sandwich" {{ $menu->kategori == 'Sandwich' ? 'selected' : '' }}>
                                        Sandwich</option>
                                    <option value="Drinks" {{ $menu->kategori == 'Drinks' ? 'selected' : '' }}>Drinks
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="bahan">Bahan</label>
                                <div id="bahan-wrapper">
                                    @php
                                        // Split the bahan string into an array by comma
                                        $bahanArray = explode(',', $menu->bahan);
                                    @endphp
                            
                                    @foreach ($bahanArray as $index => $bahan)
                                        <div class="bahan-select mb-2 d-flex align-items-center" style="gap: 10px;">
                                            <select class="form-control" name="bahan[]" style="flex: 1;">
                                                <option value="" disabled>-- Pilih Bahan --</option>
                                                @foreach ($bahans as $optionBahan)
                                                    <option value="{{ $optionBahan->id }}"
                                                        {{ $optionBahan->bahan == trim($bahan) ? 'selected' : '' }}>
                                                        {{ $optionBahan->bahan }}</option>
                                                @endforeach
                                            </select>
                                            @if ($index > 0) <!-- Only show the delete button for dynamically added rows -->
                                                <button type="button" class="btn btn-danger btn-sm ms-2 remove-bahan">×</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Add Bahan Button -->
                            <button type="button" id="add-bahan" class="btn btn-sm btn-success mt-2">
                                <i class="fas fa-plus"></i> Tambah Bahan
                            </button>
                            

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Menu</button>
                        </div>
                    </form>

                </div>
                <!-- /.card -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@include('layouts.footer')

<!-- Scripts -->
<script>
    // Add Bahan dynamically
document.getElementById('add-bahan').addEventListener('click', function() {
    const wrapper = document.getElementById('bahan-wrapper');

    const newSelect = document.createElement('div');
    newSelect.classList.add('bahan-select', 'mb-2', 'd-flex', 'align-items-center');
    newSelect.style.gap = "10px"; // Ensures there is space between the select and delete button

    newSelect.innerHTML = `
        <select class="form-control" name="bahan[]" style="flex: 1;">
            <option value="" disabled selected>-- Pilih Bahan --</option>
            @foreach ($bahans as $bahan)
                <option value="{{ $bahan->id }}">{{ $bahan->bahan }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-danger btn-sm remove-bahan">×</button>
    `;

    wrapper.appendChild(newSelect);
});

// Remove Bahan
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-bahan')) {
        e.target.closest('.bahan-select').remove();
    }
});


</script>


<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>
