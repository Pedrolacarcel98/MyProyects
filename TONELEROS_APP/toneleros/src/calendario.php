<?php
require 'verifySession.php';
?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calendario de Actuaciones</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FullCalendar v6 (bundle global) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <!-- Tippy para tooltips -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tippy.js@6/dist/tippy.css">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2"></script>
  <script src="https://cdn.jsdelivr.net/npm/tippy.js@6"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <meta name="robots" content="noindex,nofollow">

  <style>
    :root {
      --fc-border-color: rgb(63 63 70);
      --fc-neutral-bg-color: rgb(39 39 42);
      --fc-page-bg-color: transparent;
      --fc-today-bg-color: rgba(34,197,94,0.10);
      --fc-now-indicator-color: rgb(244 63 94);
      --fc-highlight-color: rgba(161,161,170,0.20);
      --fc-event-bg-color: rgb(63 63 70);
      --fc-event-border-color: rgb(82 82 91);
      --fc-event-text-color: #fff;
    }
    .fc { color: #e4e4e7; }
    .fc-toolbar-title { font-weight: 800; }
    .fc-button-primary { background-color: rgb(24 24 27); border-color: rgb(82 82 91); }
    .fc-button-primary:hover { background-color: rgb(39 39 42); }
    .fc-button-primary:disabled { background-color: rgb(63 63 70); border-color: rgb(82 82 91); }
  </style>
</head>
<body class="h-full min-h-screen bg-zinc-900 text-zinc-100">
  <div class="container mx-auto px-4 py-8">
    <div class="mx-auto max-w-7xl bg-zinc-800/60 rounded-2xl shadow-xl p-6 md:p-8">

      <!-- Barra superior -->
      <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
        <button onclick="history.back()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-zinc-600 hover:bg-zinc-700 transition">
          <i class="fa-solid fa-arrow-left"></i> Volver
        </button>

        <div class="flex-1"></div>

        <div class="flex items-center gap-2">
          <label for="filtroTipo" class="text-sm text-zinc-300">Filtrar por tipo:</label>
          <select id="filtroTipo" class="bg-zinc-900 border border-zinc-600 rounded-lg px-3 py-2 text-sm">
            <option value="">Todos</option>
            <!-- Opciones se llenan dinámicamente al vuelo según eventos recibidos -->
          </select>

          <label class="inline-flex items-center gap-2 ml-4 text-sm">
            <input id="toggleWeekends" type="checkbox" class="accent-emerald-500" checked>
            Mostrar fines de semana
          </label>
        </div>
      </div>

      <!-- Título -->
      <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Calendario de Actuaciones</h1>
        <p class="text-sm text-zinc-300">Pulsa en una actuación para ver el detalle.</p>
      </div>

      <div id="calendar" class="rounded-xl overflow-hidden border border-zinc-700"></div>

      <div class="mt-6 text-xs text-zinc-400 flex flex-wrap items-center gap-3">
        <span class="inline-flex items-center gap-2">
          <span class="inline-block w-3 h-3 rounded bg-zinc-600"></span> Evento
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="inline-block w-3 h-0.5 bg-rose-500"></span> Indicador de ahora
        </span>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const calendarEl = document.getElementById('calendar');
      const filtroTipo = document.getElementById('filtroTipo');
      const toggleWeekends = document.getElementById('toggleWeekends');

      let currentTipo = '';

      function buildEventosUrl() {
        const params = new URLSearchParams();
        if (currentTipo) params.set('tipo', currentTipo);
        return 'eventos.php' + (params.toString() ? ('?' + params.toString()) : '');
      }

      function cargarTiposParaFiltro(eventos) {
        const tipos = Array.from(new Set(eventos.map(e => (e.extendedProps?.tipo || '')).filter(Boolean))).sort();
        // Vaciar excepto "Todos"
        filtroTipo.querySelectorAll('option:not(:first-child)').forEach(o => o.remove());
        tipos.forEach(t => {
          const opt = document.createElement('option');
          opt.value = t;
          opt.textContent = t;
          filtroTipo.appendChild(opt);
        });
      }

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        expandRows: true,
        nowIndicator: true,
        weekends: true,
        locale: 'es',
        firstDay: 1,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
          today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día', list: 'Agenda'
        },
        events: buildEventosUrl, // FullCalendar pedirá a eventos.php
        eventClick: function(info) {
          info.jsEvent.preventDefault();
          // Navegar a detalle usando el id del evento
          const id = info.event.id;
          if (id) window.location.href = 'actuacion.php?id=' + encodeURIComponent(id);
        },
        eventDidMount: function(info) {
          const props = info.event.extendedProps || {};
          const hora = props.hora ? ` · ${props.hora}` : '';
          const dir  = props.direccion ? `<div class="mt-1 text-xs opacity-80">${props.direccion}</div>` : '';
          const tipo = props.tipo ? `<div class="mt-1 text-[11px] uppercase tracking-wide opacity-70">Tipo: ${props.tipo}</div>` : '';
          tippy(info.el, { content: `<div><strong>${info.event.title}</strong>${hora}${dir}${tipo}</div>`, allowHTML: true, theme: 'light-border', placement: 'top' });
        },
        eventContent: function(arg) {
          const title = arg.event.title || '';
          const timeText = arg.timeText ? `<span class="fc-event-time">${arg.timeText}</span>` : '';
          return { html: `<div class="px-1 py-0.5"><div class="text-[11px] font-semibold">${timeText} ${title}</div></div>` };
        },
        // Hook para, tras cargar, poblar el filtro de tipos:
        eventsSet: function(events) {
          cargarTiposParaFiltro(events);
        },
        loading: function(isLoading) {
          document.body.style.cursor = isLoading ? 'progress' : 'default';
        }
      });

      calendar.render();

      filtroTipo.addEventListener('change', () => {
        currentTipo = filtroTipo.value;
        calendar.refetchEvents(); // vuelve a llamar a eventos.php con ?tipo=...
      });

      toggleWeekends.addEventListener('change', () => {
        calendar.setOption('weekends', toggleWeekends.checked);
      });

      window.addEventListener('resize', () => calendar.updateSize());
    });
  </script>
</body>
</html>
