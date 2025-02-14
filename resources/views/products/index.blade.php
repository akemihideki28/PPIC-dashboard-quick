@extends('layouts.app')

@section('contents')
    <style>
        body {
            background: linear-gradient(to bottom, #E3F2FD, #B0BEC5);
        }

        .content-wrapper {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #E8F5E9;
        }
        tr:hover {
            background-color: #C8E6C9;
        }

        .btn-group .btn {
            margin-right: 8px;
        }
    </style>

    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Injection Molding</h4>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Tambah</a>
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Download
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('products.download.excel') }}">Download Excel</a>
                    </div>
                </div>
            </div>
        </div>

        <hr />

        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Mesin</th>
                    <th>Mesin</th>
                    <th>Nama Produk</th>
                    <th>Part No</th>
                    <th>Cycle Time</th>
                    <th>Cavity</th>
                    <th>Added Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($product->count() > 0)
                    @foreach($product as $rs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rs->no_mesin }}</td>
                            <td>{{ $rs->nama_mesin }}</td>
                            <td>{{ $rs->nama_produk }}</td>
                            <td>{{ $rs->part_no }}</td>
                            <td>{{ gmdate("H:i:s", $rs->cycle_time) }}</td>
                            <td>{{ $rs->cavity }}</td>
                            <td>{{ $rs->created_at }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('products.show', $rs->id) }}" class="btn btn-secondary">Detail</a>
                                    <a href="#" onclick="confirmEdit('{{ route('products.edit', $rs->id) }}')" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('products.destroy', $rs->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this);">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="9">Data Belum Ada</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Notiflix Library -->
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>

    <script>
        function confirmEdit(editUrl) {
            Notiflix.Confirm.prompt(
                "Konfirmasi Edit",
                "Masukkan password untuk mengedit:",
                "",
                "Konfirmasi",
                "Batal",
                function(password) {
                    if (password === "1234") {
                        window.location.href = editUrl;
                    } else {
                        Notiflix.Notify.failure("Password salah! Mengedit dibatalkan.");
                    }
                }
            );
        }

        function confirmDelete(event, form) {
            event.preventDefault(); // Mencegah pengiriman form langsung

            Notiflix.Confirm.prompt(
                "Konfirmasi Hapus",
                "Masukkan password untuk menghapus:",
                "",
                "Konfirmasi",
                "Batal",
                function(password) {
                    if (password === "1234") {
                        Notiflix.Notify.success("Data berhasil dihapus!");
                        setTimeout(() => form.submit(), 500); // Menunggu animasi sebelum submit
                    } else {
                        Notiflix.Notify.failure("Password salah! Menghapus dibatalkan.");
                    }
                }
            );
        }
    </script>
@endsection
