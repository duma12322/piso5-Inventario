@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@section('content')
<!-- Header -->
<header class="header">
  <div class="header-images">
    <img src="{{ asset('fonts/LOGOS.png') }}" alt="Escudo Lara" class="left">
    <!-- <img src="{{ asset('fonts/Gobernacion-del-estado-Lara.jpg') }}" alt="Decoración" class="center">
    <img src="{{ asset('fonts/OFICINA12.jpg') }}" alt="Bandera Venezuela" class="right"> -->
  </div>
  <!-- <h1 class="nav_logo">Gobernación del Estado Lara</h1> -->
</header>


<!-- Sección principal -->
<section class="home">
  <div class="form_container">
    {{-- Botón cerrar (si luego usas JS modal) --}}
    <i class="uil uil-times form_close"></i>

    {{-- Formulario de Login --}}
    <div class="form login_form">
      <form method="POST" action="{{ route('login.autenticar') }}">
        @csrf

        <h2>Iniciar Sesión</h2>

        {{-- Mostrar error si existe --}}
        @if(session('error'))
          <div class="alert alert-danger mt-2 text-center">
            {{ session('error') }}
          </div>
        @endif

        {{-- Usuario --}}
        <div class="input_box">
          <input type="text" name="usuario" placeholder="Usuario" value="{{ old('usuario') }}" required autofocus>
          <i class="uil uil-user email"></i>
        </div>

        {{-- Contraseña --}}
        <div class="input_box">
          <input type="password" name="password" placeholder="Contraseña" required>
          <i class="uil uil-lock password"></i>
          <i class="uil uil-eye-slash pw_hide"></i>
        </div>

        <!-- <div class="option_field">
          <span class="checkbox">
            <input type="checkbox" id="remember" />
            <label for="remember">Recordarme</label>
          </span>
          <a href="#" class="forgot_pw">¿Olvidaste tu contraseña?</a>
        </div> -->

        <button type="submit" class="button">Ingresar</button>

        <!-- <div class="login_signup">
          ¿No tienes una cuenta? <a href="#">Regístrate</a> -->
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
