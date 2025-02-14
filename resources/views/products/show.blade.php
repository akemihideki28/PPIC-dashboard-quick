@extends('layouts.app')
  
@section('contents')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Detail Product</h1>
    </div>
    <hr />
    <div class="row">
    <div class="col mb-3">
            <label class="form-label">No Mesin</label>
            <input type="text" name="no_mesin" class="form-control" placeholder="No Mesin" value="{{ $product->no_mesin }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Nama Mesin</label>
            <input type="text" name="nama_mesin" class="form-control" placeholder="Nama Mesin" value="{{ $product->nama_mesin }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" value="{{ $product->nama_produk }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Part No</label>
            <input type="text" name="part_no" class="form-control" placeholder="Part No" value="{{ $product->part_no }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Cycle Time</label>
            <input type="text" name="cycle_time" class="form-control" placeholder="Cycle Time" value="{{ $product->cycle_time }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Cavity</label>
            <input type="text" name="cavity" class="form-control" placeholder="Cavity" value="{{ $product->cavity }}" readonly>
        </div>
    </div>
    
    <!-- Menambahkan bagian untuk Shift 1, 2, dan 3 dengan target dan waktu -->
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Created At</label>
            <input type="text" name="created_at" class="form-control" placeholder="Created At" value="{{ $product->created_at }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Updated At</label>
            <input type="text" name="updated_at" class="form-control" placeholder="Updated At" value="{{ $product->updated_at }}" readonly>
        </div>
    </div>
@endsection
