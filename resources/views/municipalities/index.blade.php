@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Municipalities</h2>
                        <a href="{{ route('municipalities.create') }}" class="btn btn-primary">Create New Municipality</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($municipalities as $municipality)
                                <tr>
                                    <td>{{ $municipality->id }}</td>
                                    <td>{{ $municipality->name }}</td>
                                    <td>{{ $municipality->code }}</td>
                                    <td>
                                        <a href="{{ route('municipalities.show', $municipality) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('municipalities.edit', $municipality) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('municipalities.destroy', $municipality) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this municipality?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
