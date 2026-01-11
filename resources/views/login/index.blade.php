@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">


@section('content')
  <header class="header">
    <div class="header-images">
      <img src="{{ asset('fonts/LOGOS.png') }}" alt="Escudo Lara">
    </div>
  </header>

  <section class="home">
    <div class="form_container">
      <div class="form login_form">
        <form method="POST" action="{{ route('login.autenticar') }}">
          @csrf

          <h2>Iniciar Sesión</h2>

          @if($errors->any())
            <div class="alert alert-danger py-2 text-center"
              style="color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; border-radius: 5px; margin-bottom: 15px; font-size: 14px; padding: 10px;">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          {{-- Usuario --}}
          <div class="input_box">
            <input type="text" name="usuario" placeholder="Usuario" value="{{ old('usuario') }}" required autofocus>
            <i class="uil uil-user email"></i>
          </div>

          {{-- Contraseña --}}
          <div class="input_box">
            <input type="password" id="passwordField" name="password" placeholder="Contraseña" required>
            <i class="uil uil-lock password"></i>
            <i class="uil uil-eye-slash pw_hide" id="eyeIcon"></i>
          </div>

          <button type="submit" class="button">Ingresar</button>
        </form>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const eyeIcon = document.querySelector("#eyeIcon");
      const passwordField = document.querySelector("#passwordField");

      if (!eyeIcon || !passwordField) return;

      eyeIcon.addEventListener("click", () => {
        if (passwordField.type === "password") {
          passwordField.type = "text";
          eyeIcon.classList.replace("uil-eye-slash", "uil-eye");
        } else {
          passwordField.type = "password";
          eyeIcon.classList.replace("uil-eye", "uil-eye-slash");
        }
      });
    });
  </script>

@endsection