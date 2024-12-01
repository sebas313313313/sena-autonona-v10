@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Tables</h2>

    <!-- Basic Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Tabla Básica</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Larry</td>
                        <td>Bird</td>
                        <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Striped Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Tabla con Rayas</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laptop HP</td>
                        <td>Electrónicos</td>
                        <td>$999.99</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>Mouse Logitech</td>
                        <td>Accesorios</td>
                        <td>$29.99</td>
                        <td>50</td>
                    </tr>
                    <tr>
                        <td>Monitor Dell</td>
                        <td>Electrónicos</td>
                        <td>$299.99</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>Teclado Mecánico</td>
                        <td>Accesorios</td>
                        <td>$89.99</td>
                        <td>25</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bordered Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Tabla con Bordes</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Empresa</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">País</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Alfreds Futterkiste</td>
                        <td>Maria Anders</td>
                        <td>Germany</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Editar</button>
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Centro comercial Moctezuma</td>
                        <td>Francisco Chang</td>
                        <td>Mexico</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Editar</button>
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Ernst Handel</td>
                        <td>Roland Mendel</td>
                        <td>Austria</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Editar</button>
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Responsive Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Tabla Responsiva</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Encabezado 1</th>
                            <th scope="col">Encabezado 2</th>
                            <th scope="col">Encabezado 3</th>
                            <th scope="col">Encabezado 4</th>
                            <th scope="col">Encabezado 5</th>
                            <th scope="col">Encabezado 6</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                            <td>Celda</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
