@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Activity</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('activities.update', $activity->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Activity Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $activity->title) }}" required>
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
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $activity->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $activity->start_date) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $activity->end_date) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $activity->location) }}" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-success">Update Activity</button>
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