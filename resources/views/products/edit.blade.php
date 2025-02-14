@extends('layouts.app')

@section('contents')
    <h1 class="mb-0">Edit Product</h1>
    <hr />

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col">
                <label for="no_mesin">No Mesin</label>
                <input type="text" id="no_mesin" name="no_mesin" class="form-control" value="{{ old('no_mesin', $product->no_mesin) }}" readonly>
            </div>
            <div class="col">
                <label for="nama_mesin">Nama Mesin</label>
                <input type="text" id="nama_mesin" name="nama_mesin" class="form-control" value="{{ old('nama_mesin', $product->nama_mesin) }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" class="form-control" value="{{ old('nama_produk', $product->nama_produk) }}" readonly>
            </div>
            <div class="col">
                <label for="part_no">Part No</label>
                <input type="text" id="part_no" name="part_no" class="form-control" value="{{ old('part_no', $product->part_no) }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="cycle_time">Cycle Time</label>
                <input type="number" id="cycle_time" name="cycle_time" step="0.01" class="form-control" value="{{ old('cycle_time', $product->cycle_time) }}">
            </div>
            <div class="col">
                <label for="cavity">Cavity</label>
                <input type="number" id="cavity" name="cavity" class="form-control" value="{{ old('cavity', $product->cavity) }}">
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary w-100">Update</button>
            </div>
        </div>
    </form>
@endsection