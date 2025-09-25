@extends('layouts.app')

@section('content')
<style>
  :root {
    --primary: #274DF5;   /* Azul principal */
    --accent: #F58E27;    /* Naranja acento */
    --bg: #f7f8fc;
    --text: #333;
  }

  body {
    background: var(--bg);
    font-family: 'Segoe UI', sans-serif;
  }

  .card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
  }

  .card-title {
    color: var(--primary);
    font-weight: bold;
  }

  .form-label {
    font-weight: 500;
    color: var(--text);
  }

  .form-control {
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 0.65rem;
    transition: all .2s ease;
  }

  .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(9, 39, 172, 0.2);
  }

  .btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    padding: 0.75rem;
    transition: all .2s ease;
  }

  .btn-primary:hover {
    background: var(--accent);
    transform: translateY(-2px);
  }
</style>

<div class="row justify-content-center align-items-center min-vh-100">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body p-4">
        <h3 class="card-title mb-4 text-center">Iniciar sesión</h3>

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" required autofocus value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
