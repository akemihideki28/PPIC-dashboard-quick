@extends('layouts.app')

@section('contents')
    <h1 class="mb-0">Tambah Data Injection</h1>
    <hr />
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <label for="no_mesin">No Mesin</label>
                <input type="text" id="no_mesin" name="no_mesin" class="form-control" placeholder="No Mesin" required value="{{ old('no_mesin') }}" onchange="fillData(this)">
                <select id="no_mesin_dropdown" name="no_mesin_dropdown" class="form-control mt-2" style="display: none;" onchange="syncInput('no_mesin'); fillDataDropdown(this)">
                    <option value="">Pilih No Mesin</option>
                    @foreach($products as $product)
                        <option value="{{ $product->no_mesin }}" {{ old('no_mesin') == $product->no_mesin ? 'selected' : '' }}>{{ $product->no_mesin }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-link p-0 mt-2" onclick="toggleInputDropdown('no_mesin')">
                    <i class="fas fa-chevron-down"></i> Pilih dari Dropdown
                </button>
                @error('no_mesin')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="nama_mesin">Nama Mesin</label>
                <input type="text" id="nama_mesin" name="nama_mesin" class="form-control" placeholder="Nama Mesin" required value="{{ old('nama_mesin') }}" readonly>
                <select id="nama_mesin_dropdown" name="nama_mesin_dropdown" class="form-control mt-2" style="display: none;" onchange="syncInput('nama_mesin')">
                    <option value="">Pilih Nama Mesin</option>
                    @foreach($products as $product)
                        <option value="{{ $product->nama_mesin }}" {{ old('nama_mesin') == $product->nama_mesin ? 'selected' : '' }}>{{ $product->nama_mesin }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-link p-0 mt-2" onclick="toggleInputDropdown('nama_mesin')">
                    <i class="fas fa-chevron-down"></i> Pilih dari Dropdown
                </button>
                @error('nama_mesin')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" class="form-control" placeholder="Nama Produk" required value="{{ old('nama_produk') }}">
                <select id="nama_produk_dropdown" name="nama_produk_dropdown" class="form-control mt-2" style="display: none;" onchange="syncInput('nama_produk')">
                    <option value="">Pilih Nama Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->nama_produk }}" {{ old('nama_produk') == $product->nama_produk ? 'selected' : '' }}>{{ $product->nama_produk }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-link p-0 mt-2" onclick="toggleInputDropdown('nama_produk')">
                    <i class="fas fa-chevron-down"></i> Pilih dari Dropdown
                </button>
                @error('nama_produk')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="part_no">Part No</label>
                <input type="text" id="part_no" name="part_no" class="form-control" placeholder="Part No" required value="{{ old('part_no') }}">
                <select id="part_no_dropdown" name="part_no_dropdown" class="form-control mt-2" style="display: none;" onchange="syncInput('part_no')">
                    <option value="">Pilih Part No</option>
                    @foreach($products as $product)
                        <option value="{{ $product->part_no }}" {{ old('part_no') == $product->part_no ? 'selected' : '' }}>{{ $product->part_no }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-link p-0 mt-2" onclick="toggleInputDropdown('part_no')">
                    <i class="fas fa-chevron-down"></i> Pilih dari Dropdown
                </button>
                @error('part_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="cycle_time">Cycle Time</label>
                <input type="number" id="cycle_time" name="cycle_time" step="0.01" class="form-control" placeholder="Cycle Time (detik)" required value="{{ old('cycle_time') }}">
                @error('cycle_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="cavity">Cavity</label>
                <input type="number" id="cavity" name="cavity" class="form-control" placeholder="Cavity" required value="{{ old('cavity') }}">
                @error('cavity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <script>
    var existingMachines = @json($products->keyBy('no_mesin'));

    function toggleInputDropdown(field) {
        var inputField = document.getElementById(field);
        var dropdownField = document.getElementById(field + '_dropdown');

        if (inputField.style.display === 'none') {
            inputField.style.display = 'block';
            dropdownField.style.display = 'none';
        } else {
            inputField.style.display = 'none';
            dropdownField.style.display = 'block';
        }
    }

    function syncInput(field) {
        var inputField = document.getElementById(field);
        var dropdownField = document.getElementById(field + '_dropdown');
        inputField.value = dropdownField.value;
    }

    function fillData(inputElement) {
        var noMesin = inputElement.value.trim();
        var namaMesinInput = document.getElementById('nama_mesin');
        var partNoInput = document.getElementById('part_no');

        var namaMesinDropdown = document.getElementById('nama_mesin_dropdown');
        var partNoDropdown = document.getElementById('part_no_dropdown');

        var namaMesinButton = namaMesinDropdown.nextElementSibling;
        var partNoButton = partNoDropdown.nextElementSibling;

        if (existingMachines.hasOwnProperty(noMesin)) {
            // Tampilkan popup alert
            alert("No Mesin yang dimasukkan sudah ada. Nama Mesin & Part No akan otomatis terisi dan terkunci.");
            namaMesinInput.value = existingMachines[noMesin].nama_mesin;
            namaMesinInput.setAttribute('readonly', true);
            partNoInput.value = existingMachines[noMesin].part_no;
            partNoInput.setAttribute('readonly', true);

            // Sembunyikan dropdown & tombol dropdown
            namaMesinDropdown.style.display = 'none';
            namaMesinButton.style.display = 'none';
            partNoDropdown.style.display = 'none';
            partNoButton.style.display = 'none';
        } else {
            // Jika nomor mesin baru, kosongkan nama mesin & part no, lalu izinkan input manual
            namaMesinInput.value = '';
            namaMesinInput.removeAttribute('readonly');
            partNoInput.value = '';
            partNoInput.removeAttribute('readonly');

            // Tampilkan dropdown & tombol dropdown
            namaMesinDropdown.style.display = 'block';
            namaMesinButton.style.display = 'inline';
            partNoDropdown.style.display = 'block';
            partNoButton.style.display = 'inline';
        }
    }

    function fillDataDropdown(selectElement) {
        var noMesin = selectElement.value;
        var namaMesinInput = document.getElementById('nama_mesin');
        var partNoInput = document.getElementById('part_no');

        var namaMesinDropdown = document.getElementById('nama_mesin_dropdown');
        var partNoDropdown = document.getElementById('part_no_dropdown');

        var namaMesinButton = namaMesinDropdown.nextElementSibling;
        var partNoButton = partNoDropdown.nextElementSibling;

        if (existingMachines.hasOwnProperty(noMesin)) {
            // Isi Nama Mesin & Part No lalu kunci
            namaMesinInput.value = existingMachines[noMesin].nama_mesin;
            namaMesinInput.setAttribute('readonly', true);
            partNoInput.value = existingMachines[noMesin].part_no;
            partNoInput.setAttribute('readonly', true);

            // Sembunyikan dropdown & tombol dropdown
            namaMesinDropdown.style.display = 'none';
            namaMesinButton.style.display = 'none';
            partNoDropdown.style.display = 'none';
            partNoButton.style.display = 'none';
        }
    }
</script>

@endsection
