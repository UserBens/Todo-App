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
                <form action="{{ route('logout') }}" method="POST" class="text-end mb-3">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                </form>

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
                <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf

                    {{-- Judul --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Judul Task</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="Contoh: Perbaikan printer ruang TU">

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Due Date --}}
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Tanggal Target</label>
                        <input type="date" name="due_date"
                            class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">

                        @error('due_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Prioritas</label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>

                        @error('priority')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach (['fjm mobile', 'networking', 'desain', 'website', 'hardware', 'software', 'device'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>

                        @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- PIC --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">PIC</label>
                        <input type="text" name="pic" class="form-control @error('pic') is-invalid @enderror"
                            value="{{ old('pic') }}" placeholder="Contoh: Budi">

                        @error('pic')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Upload --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Upload (PDF/Image)</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">

                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            + Tambahkan
                        </button>
                    </div>
                </form>
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

                                @if ($task->file)
                                    <br>
                                    <a href="{{ asset('storage/' . $task->file) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary mt-2">
                                        Lihat File
                                    </a>
                                @endif
                            </div>

                            <div>
                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST"
                                    class="d-inline">
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
