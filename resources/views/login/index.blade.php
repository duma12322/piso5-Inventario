{{-- resources/views/login/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Iniciar Sesión</h4>
                </div>
                <div class="card-body">
                    {{-- Mensaje de error --}}
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login.autenticar') }}">
                        @csrf {{-- Token CSRF obligatorio en Laravel --}}
                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" required autofocus>
                        </div>

                        <div class="form-group mt-2">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection