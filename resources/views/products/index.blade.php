@extends('layouts.app')

@section('contents')
    <style>
        body {
            background: #f5f7fa;
        }

        .content-wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }

        /* Tombol */
        .btn {
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            padding: 7px 12px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border: none;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background-color: #219150;
        }

        .btn-secondary {
            background-color: #7f8c8d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #626e70;
        }

        .btn-warning {
            background-color: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background-color: #e08e0b;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-group .btn {
            margin-right: 5px;
        }
    </style>

    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Injection Molding</h4>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    Tambah
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Download
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('products.download.excel') }}">
                            Download Excel
                        </a>
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
                                <div class="btn-group">
                                    <a href="{{ route('products.show', $rs->id) }}" class="btn btn-secondary">
                                        Detail
                                    </a>
                                    <a href="#" onclick="confirmEdit('{{ route('products.edit', $rs->id) }}')" class="btn btn-warning">
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $rs->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this);">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">
                                            Delete
                                        </button>
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
            event.preventDefault();

            Notiflix.Confirm.prompt(
                "Konfirmasi Hapus",
                "Masukkan password untuk menghapus:",
                "",
                "Konfirmasi",
                "Batal",
                function(password) {
                    if (password === "1234") {
                        Notiflix.Notify.success("Data berhasil dihapus!");
                        setTimeout(() => form.submit(), 500);
                    } else {
                        Notiflix.Notify.failure("Password salah! Menghapus dibatalkan.");
                    }
                }
            );
        }
    </script>
@endsection
