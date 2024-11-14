@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Municipality Details</h2>
                        <a href="{{ route('municipalities.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right"><strong>ID:</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $municipality->id }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Name:</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $municipality->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Code:</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $municipality->code }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Created At:</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $municipality->created_at }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Updated At:</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $municipality->updated_at }}</p>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{ route('municipalities.edit', $municipality) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('municipalities.destroy', $municipality) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this municipality?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
