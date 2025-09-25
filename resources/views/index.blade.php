<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
        color: var(--text);
      }

      h1 {
        color: var(--primary);
        font-weight: 700;
      }

      p {
        color: #555;
        font-size: 1.1rem;
      }

      .btn-primary {
        background: var(--primary);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all .2s ease;
      }

      .btn-primary:hover {
        background: var(--accent);
        transform: translateY(-2px);
      }

      .container {
        background: #fff;
        border-radius: 14px;
        padding: 3rem;
        box-shadow: 0 8px 20px rgba(0,0,0,.08);
      }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container text-center">
        <h1>Bienvenido</h1>
        <p>Esta es la página de inicio.</p>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
        </div>
    </div>
</body>
</html>
