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
                    <form method="POST" action="{{ route('kategori_menu.update', $kategori_menu->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputMenu">Menu</label>
                                <input type="text" class="form-control" id="exampleInputKategori" name="kategori"
                                    value="{{ old('kategori_menu', $kategori_menu->kategori) }}" placeholder="Enter name" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputDesc">Description</label>
                                <input type="text" class="form-control" id="exampleInputDesc" name="desc"
                                    value="{{ old('kategori_menu', $kategori_menu->desc) }}" placeholder="Enter description" required>
                            </div>

                            

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




<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>
