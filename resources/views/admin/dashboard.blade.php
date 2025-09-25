@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ tab: 'usuarios' }">
  <!-- Menú lateral del admin -->
  <aside class="w-64 bg-white border-r shadow-sm">
    <div class="p-6 border-b">
      <h2 class="text-lg font-semibold text-gray-800">Panel de Administración</h2>
    </div>
    <nav class="mt-4 space-y-2 px-6">
      <button @click="tab = 'usuarios'" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 text-gray-700 font-medium"
        :class="{ 'bg-gray-200': tab === 'usuarios' }">
        Gestión de usuarios
      </button>

      <button @click="tab = 'subir_excel'" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 text-gray-700 font-medium"
        :class="{ 'bg-gray-200': tab === 'subir_excel' }">
        Carga de archivos
      </button>

      <button @click="tab = 'configuracion'" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 text-gray-700 font-medium"
        :class="{ 'bg-gray-200': tab === 'configuracion' }">
        Configuración del sistema
      </button>

      <!-- Cerrar sesión -->
      <form method="POST" action="{{ route('logout') }}" class="pt-6">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded font-medium">
          Cerrar sesión
        </button>
      </form>
    </nav>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1 p-10">
    <div class="bg-white shadow rounded-lg p-6">
      <h1 class="text-2xl font-bold text-gray-800">Bienvenido, {{ Auth::user()->name }}</h1>
      <p class="mt-2 text-gray-600">Este es el panel exclusivo de administración.</p>
    </div>

    <!-- Secciones dinámicas -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
      <!-- Usuarios -->
      <div x-show="tab === 'usuarios'" x-cloak>
        <h2 class="text-xl font-semibold mb-2 text-gray-800">Gestión de usuarios</h2>

        <!-- Tabla: Usuarios -->
        <div class="bg-white shadow rounded-lg p-6 mt-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Usuarios Registrados</h2>
          <table class="table-auto w-full text-sm text-gray-700">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Email</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $u)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-2">{{ $u->id }}</td>
                  <td class="px-4 py-2">{{ $u->name }}</td>
                  <td class="px-4 py-2">{{ $u->email }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Tabla: Agentes -->
        <div class="bg-white shadow rounded-lg p-6 mt-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Agentes</h2>
          <table class="table-auto w-full text-sm text-gray-700">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Línea</th>
              </tr>
            </thead>
            <tbody>
              @foreach($agentes as $a)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-2">{{ $a->email }}</td>
                  <td class="px-4 py-2">{{ $a->Linea }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Carga de archivos -->
      <!-- Carga de archivos -->
<div x-show="tab === 'subir_excel'" x-cloak class="bg-white shadow rounded-lg p-6 space-y-6">
  <h3 class="text-lg font-semibold text-gray-800 mb-2">Importar archivo Excel</h3>

  {{-- Mensajes de éxito --}}
  @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  {{-- Resumen de importación opcional (lo llena el controlador) --}}
  @if(session('import_summary'))
    @php($sum = session('import_summary'))
    <div class="bg-blue-50 text-blue-800 px-4 py-3 rounded mb-4">
      <p><strong>Archivo:</strong> {{ $sum['archivo'] ?? 'N/D' }}</p>
      <p><strong>Hoja:</strong> {{ $sum['hoja'] ?? 'Activa' }}</p>
      <p><strong>Filas leídas:</strong> {{ $sum['rows_read'] ?? 0 }}</p>
      <p><strong>Filas guardadas:</strong> {{ $sum['rows_saved'] ?? 0 }}</p>
      <p><strong>Filas omitidas (sin email):</strong> {{ $sum['rows_skipped'] ?? 0 }}</p>
    </div>
  @endif

  {{-- Errores --}}
  @if($errors->any())
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('admin.excel.importar') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-700">Selecciona el archivo Excel</label>
      <input
        type="file"
        name="file"
        required
        accept=".xlsx,.xls,.csv"  {{-- 👈 restringe tipos --}}
        class="mt-1 block w-full text-sm border border-gray-300 rounded px-3 py-2"
      >
      <p class="text-xs text-gray-500 mt-1">
        Formatos permitidos: .xlsx, .xls, .csv. La primera fila debe contener encabezados (incluyendo la columna de email).
      </p>
    </div>

    <div class="flex items-center gap-3">
      <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        Subir y guardar
      </button>

      {{-- (Opcional) Check para limpiar importaciones anteriores del mismo archivo --}}
      <label class="inline-flex items-center text-sm text-gray-700">
        <input type="checkbox" name="replace_previous" value="1" class="mr-2">
        Reemplazar importaciones previas con el mismo nombre de archivo
      </label>
    </div>
  </form>
</div>


      <!-- Configuración -->
      <div x-show="tab === 'configuracion'" x-cloak>
        <h2 class="text-xl font-semibold mb-2 text-gray-800">Configuración del sistema</h2>
        <p class="text-gray-600">Opciones de personalización global del sitio.</p>
      </div>
    </div>
  </main>
</div>
@endsection
