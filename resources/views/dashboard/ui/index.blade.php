@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">UI Elements</h2>

    <!-- Botones -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Botones</h5>
        </div>
        <div class="card-body">
            <button class="btn btn-primary me-2">Primary</button>
            <button class="btn btn-secondary me-2">Secondary</button>
            <button class="btn btn-success me-2">Success</button>
            <button class="btn btn-danger me-2">Danger</button>
            <button class="btn btn-warning me-2">Warning</button>
            <button class="btn btn-info me-2">Info</button>
        </div>
    </div>

    <!-- Alertas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Alertas</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-primary mb-2">Esta es una alerta primary</div>
            <div class="alert alert-secondary mb-2">Esta es una alerta secondary</div>
            <div class="alert alert-success mb-2">Esta es una alerta success</div>
            <div class="alert alert-danger mb-2">Esta es una alerta danger</div>
            <div class="alert alert-warning mb-2">Esta es una alerta warning</div>
            <div class="alert alert-info mb-2">Esta es una alerta info</div>
        </div>
    </div>

    <!-- Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Card image">
                <div class="card-body">
                    <h5 class="card-title">Card Title</h5>
                    <p class="card-text">Some quick example text to build on the card title.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Card Title</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <p class="card-text">Some quick example text to build on the card title.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Featured
                </div>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
                <div class="card-footer text-muted">
                    2 days ago
                </div>
            </div>
        </div>
    </div>

    <!-- Badges y Progress -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Badges</h5>
                </div>
                <div class="card-body">
                    <h1>Example heading <span class="badge bg-secondary">New</span></h1>
                    <h2>Example heading <span class="badge bg-secondary">New</span></h2>
                    <h3>Example heading <span class="badge bg-secondary">New</span></h3>
                    <h4>Example heading <span class="badge bg-secondary">New</span></h4>
                    <h5>Example heading <span class="badge bg-secondary">New</span></h5>
                    <h6>Example heading <span class="badge bg-secondary">New</span></h6>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Progress Bars</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: 25%"></div>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%"></div>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 75%"></div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
