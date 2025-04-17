@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f8ff;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 500;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Add New Activity
                </div>
                <div class="card-body">
                    <!-- <form action="{{ route('activities.store') }}" method="POST"> -->
                    <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Activity Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <label for="type">Type of Activity</label>
                        <select name="type" id="type" class="form-control" required>
                            @php
                            $types = ['seminar','workshop','competition','volunteering','talk','conference','training','webinar','sports','other'];
                            @endphp
                            @foreach ($types as $t)
                            <option value="{{ $t }}" {{ (isset($activity) && $activity->type === $t) ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                            @endforeach
                        </select>


                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="qr_code" class="form-label">QR Code Image (Optional)</label>
                            <input type="file" name="qr_code" id="qr_code" class="form-control" accept="image/*">
                        </div>


                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('activities.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Create Activity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33'
    });
</script>
@endif
@endpush