@extends('layouts.app')

@section('content')
<style>
  :root{
    --primary:#274DF5;   /* Azul principal */
    --accent:#F58E27;    /* Naranja acento */
    --surface:#ffffff;
    --surface-2:#f7f8fc;
    --text:#1f2937;
    --muted:#6b7280;
    --border: rgba(0,0,0,.10);
    --ring: 0 0 0 4px rgba(39,77,245,.18);
    --radius:14px;
  }

  /* Fondo general suave del perfil */
  .profile-theme{ background: var(--surface-2); }

  /* Sidebar */
  .profile-theme aside{
    background: var(--surface);
    border-right: 1px solid var(--border);
  }
  .profile-theme aside .item,
  .profile-theme aside nav button{
    width: 100%; text-align: left;
    padding:.65rem .9rem; border-radius: 12px;
    color: var(--text); border:1px solid transparent;
    transition:.15s ease;
  }
  .profile-theme aside nav button:hover{ background: rgba(39,77,245,.06); }
  .profile-theme aside nav button.bg-gray-200{
    background: rgba(39,77,245,.14) !important;
    border: 1px solid rgba(39,77,245,.35) !important;
    box-shadow: var(--ring);
  }

  /* Tarjetas / contenedores */
  .profile-card{
    background: var(--surface) !important;
    border:1px solid var(--border) !important;
    border-radius: var(--radius) !important;
    box-shadow: 0 8px 20px rgba(0,0,0,.06) !important;
  }

  /* Títulos en azul */
  .section-title, .profile-title{ color: var(--primary); }

  /* Tablas */
  .profile-theme table{ border-color: var(--border); }
  .profile-theme thead.bg-gray-200{
    background: var(--primary) !important;
    color:#fff !important;
  }
  .profile-theme tbody tr:hover{ background: rgba(39,77,245,.04); }
  .profile-theme td, .profile-theme th{ border-color: var(--border); }

  /* Botones */
  .profile-theme .btn{
    border-radius: 12px;
    font-weight: 600;
    padding:.65rem 1rem;
    transition:.15s ease;
  }
  .profile-theme .btn-primary{
    background: var(--primary);
    border: none;
  }
  .profile-theme .btn-primary:hover{
    background: var(--accent);
    transform: translateY(-1px);
  }
  .logout-btn{ color:#dc2626; border-radius:12px; }
  .logout-btn:hover{ background: rgba(239,68,68,.10); }

  /* Inputs (Bootstrap) */
  .profile-theme .form-control, .profile-theme .form-select{
    border-radius: 12px;
    border:1px solid var(--border);
    background:#fff;
    transition:.15s ease;
  }
  .profile-theme .form-control:focus, .profile-theme .form-select:focus{
    border-color: var(--primary);
    box-shadow: var(--ring);
  }
  .profile-theme .form-label{ color: var(--text); font-weight: 600; }

  /* Alerts */
  .profile-theme .alert{ border-radius:12px; }
  .profile-theme .alert-danger{
    border-color: rgba(239,68,68,.35);
    background: rgba(239,68,68,.10);
    color:#b42318;
  }
  .profile-theme .alert-success{
    border-color: rgba(34,197,94,.35);
    background: rgba(34,197,94,.10);
    color:#166534;
  }

  /* Texto auxiliar */
  .muted{ color: var(--muted); }
</style>

<div class="flex min-h-screen profile-theme" x-data="{ tab: 'consulta' }">
  <!-- Menú lateral -->
  <aside class="w-64">
    <div class="p-6 border-b">
      <h2 class="text-lg font-semibold profile-title">Menú</h2>
    </div>
    <nav class="mt-4 space-y-2 px-6">
      <button @click="tab = 'consulta'" class="block w-full text-left px-4 py-2 rounded text-gray-700 font-medium"
              :class="{ 'bg-gray-200': tab === 'consulta' }">
        Consulta de pago
      </button>
      <button @click="tab = 'simulacion'" class="block w-full text-left px-4 py-2 rounded text-gray-700 font-medium"
              :class="{ 'bg-gray-200': tab === 'simulacion' }">
        Simulación
      </button>
      <button @click="tab = 'configuracion'" class="block w-full text-left px-4 py-2 rounded text-gray-700 font-medium"
              :class="{ 'bg-gray-200': tab === 'configuracion' }">
        Configuración
      </button>
      <button @click="tab = 'actividad'" class="block w-full text-left px-4 py-2 rounded text-gray-700 font-medium"
              :class="{ 'bg-gray-200': tab === 'actividad' }">
        Actividad
      </button>

      <!-- Cerrar sesión -->
      <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 logout-btn font-medium">
          Cerrar sesión
        </button>
      </form>
    </nav>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1 p-10 space-y-6">
    <!-- Info de usuario -->
    <div class="bg-white shadow rounded-lg p-6 profile-card">
      <h1 class="text-2xl font-bold mb-4 profile-title">Bienvenido, {{ $user->name }}</h1>
      <div class="space-y-2 text-gray-700">
        <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
        <p><span class="font-semibold">Línea asignada:</span> {{ $user->agente->Linea ?? 'No asignada' }}</p>
      </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 profile-card">
      <!-- Consulta -->
      <div x-show="tab === 'consulta'" x-cloak>
        <h2 class="text-xl font-semibold mb-2 section-title">Consulta de Bono Real</h2>

        <table class="table-auto w-full mt-4 border text-sm">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-2 py-2">Empleado</th>
              <th class="px-2 py-2">Agente</th>
              <th class="px-2 py-2">Meta Mes</th>
              <th class="px-2 py-2">Avance</th>
              <th class="px-2 py-2">% Cumpl.</th>
              <th class="px-2 py-2">Bono sin Acel.</th>
              <th class="px-2 py-2">Bono con Acel.</th>
              <th class="px-2 py-2">Total Bono</th>
              <th class="px-2 py-2">1QA</th>
              <th class="px-2 py-2">2QA</th>
              <th class="px-2 py-2">Total</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bonos as $b)
              <tr class="border-t">
                <td class="px-2 py-2">{{ $b->No_empleados }}</td>
                <td class="px-2 py-2">{{ $b->Agente }}</td>
                <td class="px-2 py-2">${{ number_format($b->Meta_Mes,2) }}</td>
                <td class="px-2 py-2">${{ number_format($b->Total_Avance,2) }}</td>
                <td class="px-2 py-2">{{ number_format($b->Cumplimiento_Meta,2) }}%</td>
                <td class="px-2 py-2">${{ number_format($b->Bono_sin_Acelerador,2) }}</td>
                <td class="px-2 py-2">${{ number_format($b->Bono_con_Acelerador,2) }}</td>
                <td class="px-2 py-2 font-bold">${{ number_format($b->Total_Bono,2) }}</td>
                <td class="px-2 py-2">${{ number_format($b->{'1QA'},2) }}</td>
                <td class="px-2 py-2">${{ number_format($b->{'2QA'},2) }}</td>
                <td class="px-2 py-2 font-bold">${{ number_format($b->Total,2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="px-2 py-3 text-center muted">No hay registros de bono.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Simulación -->
      <div x-show="tab === 'simulacion'" x-cloak>
        <h2 class="text-xl font-semibold mb-2 section-title">Simulación</h2>
        <p class="muted mb-3">Simula tus bonos sin recargar la página.</p>

        <form id="form-simulacion" class="mb-4">
          @csrf
          <div class="row g-3">
            <div class="col-md-2">
              <label class="form-label">Quincena</label>
              <select name="quincena" class="form-select" required>
                <option value="1QA">1QA</option>
                <option value="2QA">2QA</option>
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label">Meta diaria (D)</label>
              <input type="number" step="0.01" name="meta_diaria" class="form-control" required>
            </div>

            <div class="col-md-2">
              <label class="form-label">Días trabajados (E)</label>
              <input type="number" step="1" name="dias_trabajados" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Total avance mes (AGENTE) — G</label>
              <input type="number" step="0.01" name="total_avance_mes" class="form-control" required>
            </div>

            <!-- NUEVO: $G$23 -->
            <div class="col-md-3">
              <label class="form-label">Avance total mes (LÍNEA) — $G$23</label>
              <input type="number" step="0.01" name="avance_total_mes_linea" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Descuentos</label>
              <input type="number" step="0.01" name="descuentos" class="form-control" value="0">
            </div>
          </div>

          <button id="btn-calcular" class="btn btn-primary mt-3">Calcular</button>
        </form>

        <!-- Errores -->
        <div id="errores-simulacion" class="mt-2"></div>

        <!-- Resultado AJAX -->
        <div id="resultado-ajax" class="card mt-3 d-none profile-card">
          <div class="card-body">
            <h5 class="card-title" id="r-titulo" style="color:var(--primary)"></h5>

            <p><strong>Línea:</strong> <span id="r-linea"></span> ·
               <strong>% Línea:</strong> <span id="r-porc-linea"></span>%</p>

            <p><strong>Bolsa 1QA:</strong> $<span id="r-bolsa1"></span> |
               <strong>Bolsa 2QA:</strong> $<span id="r-bolsa2"></span> |
               <strong>Total bolsas:</strong> $<span id="r-bolsat"></span></p>

            <hr>
            <p><strong>Meta mensual (F=D×E):</strong> $<span id="r-meta-m"></span> |
               <strong>Total avance (G):</strong> $<span id="r-total-g"></span> |
               <strong>$G$23 (avance total línea):</strong> $<span id="r-g23"></span></p>

            <p><strong>% Cumplimiento (H=G/F):</strong> <span id="r-cumplimiento"></span>%</p>
            <p><strong>Productividad (J=G/E):</strong> <span id="r-prod"></span></p>
            <p><strong>Participación (K=G/$G$23):</strong> <span id="r-part"></span></p>

            <hr>
            <p><strong>Bono sin acelerador (L):</strong> $<span id="r-bono-s"></span> |
               <strong>Bono con acelerador (M):</strong> $<span id="r-bono-a"></span></p>

            <p><strong>Total bono (N):</strong> $<span id="r-total-bono"></span></p>

            <p><strong>1QA:</strong> $<span id="r-1qa"></span> |
               <strong>2QA:</strong> $<span id="r-2qa"></span></p>

            <p><strong>Descuentos:</strong> $<span id="r-desc"></span> |
               <strong>Total final (quincena seleccionada):</strong> $<span id="r-final"></span></p>
          </div>
        </div>
      </div>

      <!-- Configuración -->
      <div x-show="tab === 'configuracion'" x-cloak>
        <h2 class="text-xl font-semibold mb-2 section-title">Configuración</h2>

        @if (session('status'))
          <div class="mb-4 p-3 alert alert-success">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-4 p-3 alert alert-danger">
            <ul class="list-disc list-inside text-sm m-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('profile.password') }}" class="max-w-md space-y-4">
          @csrf
          @method('PUT')

          <div>
            <label for="current_password" class="block text-sm font-medium form-label">Contraseña actual</label>
            <input id="current_password" name="current_password" type="password" required
              class="mt-1 block w-full form-control sm:text-sm" />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium form-label">Nueva contraseña</label>
            <input id="password" name="password" type="password" required
              class="mt-1 block w-full form-control sm:text-sm" />
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium form-label">Confirmar nueva contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
              class="mt-1 block w-full form-control sm:text-sm" />
          </div>

          <div>
            <button type="submit" class="btn btn-primary">
              Guardar cambios
            </button>
          </div>
        </form>
      </div>

      <!-- Actividad -->
      <div x-show="tab === 'actividad'" x-cloak>
        <h2 class="text-xl font-semibold mb-2 section-title">Actividad</h2>
        <p class="muted">Aquí puedes revisar tus simulaciones recientes.</p>

        <table class="table-auto w-full mt-4 border">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-3 py-2">Fecha</th>
              <th class="px-3 py-2">Quincena</th>
              <th class="px-3 py-2">Línea</th>
              <th class="px-3 py-2">Meta</th>
              <th class="px-3 py-2">Avance</th>
              <th class="px-3 py-2">% Cumpl.</th>
              <th class="px-3 py-2">Bono</th>
              <th class="px-3 py-2">Total final</th>
            </tr>
          </thead>
          <tbody>
            @forelse($simulaciones as $s)
              <tr class="border-t">
                <td class="px-3 py-2">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-3 py-2">{{ $s->quincena }}</td>
                <td class="px-3 py-2">{{ $s->linea }}</td>
                <td class="px-3 py-2">${{ number_format($s->meta_mensual,2) }}</td>
                <td class="px-3 py-2">${{ number_format($s->total_avance,2) }}</td>
                <td class="px-3 py-2">{{ number_format($s->cumplimiento,2) }}%</td>
                <td class="px-3 py-2">${{ number_format($s->bono,2) }}</td>
                <td class="px-3 py-2 font-bold">${{ number_format($s->total_final,2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-3 py-4 text-center muted">
                  No tienes simulaciones registradas aún.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>

{{-- JS AJAX de simulación (TAL CUAL lo compartiste) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form  = document.getElementById('form-simulacion');
  const btn   = document.getElementById('btn-calcular');
  const box   = document.getElementById('resultado-ajax');
  const errEl = document.getElementById('errores-simulacion');

  const CSRF = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '{{ csrf_token() }}';
  const URL_CALC = "{{ route('api.calcular-bono') }}";

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errEl.innerHTML = '';
    btn.disabled = true; btn.textContent = 'Calculando...';

    // Construir payload en JSON
    const fd = new FormData(form);
    const payload = Object.fromEntries(fd.entries());

    try {
      const res = await fetch(URL_CALC, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        let msg = 'No se pudo calcular. Verifica tus datos.';
        try {
          const bad = await res.json();
          if (bad?.errors) {
            msg = Object.values(bad.errors).flat().join('<br>');
          } else if (bad?.message) {
            msg = bad.message;
          }
        } catch (_) {}
        errEl.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        box.classList.add('d-none');
        return;
      }

      const json = await res.json();
      if (!json.ok) {
        const msg = json.message || 'No se pudo calcular. Verifica tus datos.';
        errEl.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
        box.classList.add('d-none');
        return;
      }

      const c = json.calc;

      // Título y línea
      document.getElementById('r-titulo').textContent     = `Resultado (${c.mes} · ${c.quincena})`;
      document.getElementById('r-linea').textContent      = c.linea ?? 'N/D';
      document.getElementById('r-porc-linea').textContent = (Number(c.porcentaje_linea ?? 0) * 100).toFixed(2);

      // Bolsas
      document.getElementById('r-bolsa1').textContent = Number(c.bolsa_linea_1qa ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-bolsa2').textContent = Number(c.bolsa_linea_2qa ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-bolsat').textContent = Number(c.bolsa_linea_total ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});

      // F, G, $G$23
      document.getElementById('r-meta-m').textContent = Number(c.meta_mensual ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-total-g').textContent = Number(c.total_avance ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-g23').textContent = Number(c.g23 ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});

      // H, J, K
      document.getElementById('r-cumplimiento').textContent = Number(c.cumplimiento ?? 0).toFixed(2);
      document.getElementById('r-prod').textContent = Number(c.productividad ?? 0).toFixed(2);
      document.getElementById('r-part').textContent = (Number(c.participacion ?? 0) * 100).toFixed(2) + '%';

      // Bonos
      document.getElementById('r-bono-s').textContent = Number(c.bono_sin_acel ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-bono-a').textContent = Number(c.bono_con_acel ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-total-bono').textContent = Number(c.total_bono ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});

      // Quincenas, descuentos y total final
      document.getElementById('r-1qa').textContent = Number(c.uno_qa ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-2qa').textContent = Number(c.dos_qa ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-desc').textContent = Number(c.descuentos ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
      document.getElementById('r-final').textContent = Number(c.total_final ?? 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});

      box.classList.remove('d-none');
      errEl.innerHTML = '';
    } catch (err) {
      console.error(err);
      errEl.innerHTML = `<div class="alert alert-danger">No se pudo calcular. Verifica tus datos.</div>`;
      box.classList.add('d-none');
    } finally {
      btn.disabled = false; btn.textContent = 'Calcular';
    }
  });
});
</script>
@endsection
