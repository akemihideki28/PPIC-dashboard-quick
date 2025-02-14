@extends('layouts.app')

@section('contents')
    <div class="container">
        <h4 class="mb-4">Product Logs</h4>
        <hr />
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #007BFF; color: white;"> <!-- Warna biru Bootstrap -->
                    <th style="padding: 8px; border: 1px solid #ddd;">No</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">No Mesin</th> <!-- Ganti dari Product ID -->
                    <th style="padding: 8px; border: 1px solid #ddd;">Action</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">User</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Timestamp</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $loop->iteration }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->no_mesin }}</td> <!-- Menggunakan No Mesin -->
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->action }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->user }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->created_at }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->detail }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
