@extends('layouts.app')

@section('contents')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Product Logs</h4>
            <div>
            <form id="deleteAllForm" action="{{ route('products.logs.delete-all') }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="password" id="deleteAllPassword">
    <button type="button" id="deleteAllBtn" class="btn btn-danger">
        <i class="fas fa-trash-alt"></i> Delete All Logs
    </button>
</form>
<a href="{{ route('logs.download-excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Download Logs
        </a>

            </div>
        </div>
        <hr />
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #007BFF; color: white;">
                    <th style="padding: 8px; border: 1px solid #ddd;">No</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">No Mesin</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Action</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">User</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Timestamp</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Detail</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $loop->iteration }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->no_mesin }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->action }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->user }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->created_at }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $log->detail }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                        <form class="delete-form" action="{{ route('products.logs.delete', $log->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="password" class="delete-password">
    <button type="button" class="btn btn-sm btn-danger delete-btn">
        <i class="fas fa-trash"></i>
    </button>
</form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include Notiflix -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // Delete single log
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                
                Notiflix.Confirm.prompt(
                    'Hapus Log',
                    'Masukkan password:',
                    '',
                    'Hapus',
                    'Batal',
                    function(password) {
                        if (password === '1234') {
                            form.querySelector('.delete-password').value = password;
                            form.submit();
                        } else {
                            Notiflix.Notify.failure('Password salah!');
                        }
                    }
                );
            });
        });

        // Delete all logs
        document.getElementById('deleteAllBtn').addEventListener('click', function() {
            Notiflix.Confirm.prompt(
                'Hapus Semua Log',
                'Masukkan password:',
                '',
                'Hapus Semua',
                'Batal',
                function(password) {
                    if (password === '1234') {
                        document.getElementById('deleteAllPassword').value = password;
                        document.getElementById('deleteAllForm').submit();
                    } else {
                        Notiflix.Notify.failure('Password salah!');
                    }
                }
            );
        });
    </script>
@endsection
