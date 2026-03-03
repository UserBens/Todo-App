<!DOCTYPE html>
<html>

<head>
    <title>My To Do List</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-lg">
            <div class="card-body">

                <h3 class="mb-4 text-center">📝 My To Do List</h3>

                {{-- Statistik --}}
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <div class="alert alert-primary">
                            Total Task <br>
                            <strong>{{ $tasks->count() }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            Pending <br>
                            <strong>{{ $tasks->where('status', 'pending')->count() }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            Completed <br>
                            <strong>{{ $tasks->where('status', 'completed')->count() }}</strong>
                        </div>
                    </div>
                </div>

                {{--  FORM TAMBAH TASK  --}}
                <div class="card shadow-sm mb-4 rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">➕ Tambah Task Baru</h5>
                    </div>

                    <div class="card-body bg-light">
                        <p class="text-muted">
                            Isi form untuk menambahkan task baru.
                            Pilih tanggal, prioritas, dan kategori dengan benar.
                        </p>

                        <form action="{{ route('tasks.store') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Judul Task</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Contoh: Perbaikan printer ruang TU" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tanggal Target</label>
                                <input type="date" name="due_date" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">Prioritas</label>
                                <select name="priority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="fjm mobile">FJM Mobile</option>
                                    <option value="networking">Networking</option>
                                    <option value="desain">Desain</option>
                                    <option value="website">Website</option>
                                    <option value="hardware">Hardware</option>
                                    <option value="software">Software</option>
                                    <option value="device">Device</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">
                                    + Tambahkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- FORM TAMBAH TASK --}}

                <hr>

                {{--  LIST PENDING  --}}
                <h5 class="mb-3">⏳ Task Pending</h5>

                @forelse ($tasks->where('status','pending') as $task)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <h6 class="mb-1">{{ $task->title }}</h6>

                                <small class="text-muted">
                                    📅 {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                </small>
                                <br>

                                {{-- Badge Priority --}}
                                @if ($task->priority == 'low')
                                    <span class="badge bg-success">Low</span>
                                @elseif($task->priority == 'medium')
                                    <span class="badge bg-warning text-dark">Medium</span>
                                @else
                                    <span class="badge bg-danger">High</span>
                                @endif

                                {{-- Badge Category --}}
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst($task->category) }}
                                </span>
                            </div>

                            <div>
                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">
                                        ✔ Selesai
                                    </button>
                                </form>

                                <form action="{{ route('tasks.delete', $task->id) }}" method="POST"
                                    class="form-delete d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm btn-delete">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada task pending.</p>
                @endforelse
                {{-- LIST PENDING --}}

                {{--  LIST COMPLETED  --}}
                <h5 class="mt-5 mb-3">✅ Task Completed</h5>

                @forelse ($tasks->where('status','completed') as $task)
                    <div class="card mb-3 bg-light border-success">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <h6 class="mb-1 text-decoration-line-through">
                                    {{ $task->title }}
                                </h6>

                                <small class="text-muted">
                                    📅 {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                </small>
                                <br>

                                <span class="badge bg-success">Completed</span>

                                <span class="badge bg-secondary">
                                    {{ ucfirst($task->category) }}
                                </span>
                            </div>

                            <form action="{{ route('tasks.delete', $task->id) }}" method="POST"
                                class="form-delete d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-danger btn-sm btn-delete">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada task selesai.</p>
                @endforelse
                {{-- LIST COMPLETED --}}

            </div>
        </div>

    </div>

    {{-- SWEET ALERT NOTIFICATION --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Event delegation (lebih aman)
            document.addEventListener('click', function(e) {

                if (e.target.classList.contains('btn-delete')) {

                    e.preventDefault(); // cegah apapun dulu

                    let form = e.target.closest('form');

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: "Data yang dihapus tidak akan tampil lagi. Lanjutkan?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

                }

            });

        });
    </script>

</body>

</html>
