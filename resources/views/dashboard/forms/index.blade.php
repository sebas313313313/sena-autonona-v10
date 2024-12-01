@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Forms</h2>

    <div class="row">
        <!-- Formulario Básico -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Formulario Básico</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">Nunca compartiremos tu email con nadie más.</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Recordarme</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formulario con Validación -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Formulario con Validación</h5>
                </div>
                <div class="card-body">
                    <form class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="validationCustom01" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="validationCustom01" required>
                            <div class="valid-feedback">¡Se ve bien!</div>
                            <div class="invalid-feedback">Por favor ingresa un nombre.</div>
                        </div>
                        <div class="mb-3">
                            <label for="validationCustom02" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="validationCustom02" required>
                            <div class="valid-feedback">¡Se ve bien!</div>
                            <div class="invalid-feedback">Por favor ingresa un apellido.</div>
                        </div>
                        <div class="mb-3">
                            <label for="validationCustomUsername" class="form-label">Usuario</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                <input type="text" class="form-control" id="validationCustomUsername" 
                                       aria-describedby="inputGroupPrepend" required>
                                <div class="invalid-feedback">Por favor elige un nombre de usuario.</div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Enviar formulario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Elementos de Formulario Adicionales -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Elementos de Formulario Adicionales</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="exampleSelect" class="form-label">Select Example</label>
                                <select class="form-select" id="exampleSelect">
                                    <option selected>Selecciona una opción</option>
                                    <option value="1">Uno</option>
                                    <option value="2">Dos</option>
                                    <option value="3">Tres</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleDataList" class="form-label">Datalist Example</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Escribe para buscar...">
                                <datalist id="datalistOptions">
                                    <option value="San Francisco">
                                    <option value="New York">
                                    <option value="Seattle">
                                    <option value="Los Angeles">
                                    <option value="Chicago">
                                </datalist>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="customRange3" class="form-label">Range Example</label>
                                <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange3">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Radio Buttons</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Default radio
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Default checked radio
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Textarea Example</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">File Input Example</label>
                            <input class="form-control" type="file" id="formFile">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
@endpush
@endsection
