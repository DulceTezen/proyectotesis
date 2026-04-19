@extends('admin.template')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 font-weight-bold text-dark">📊 Panel de Reportes Mensuales</h1>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-download"></i> Descargar PDF
        </button>
    </div>

    {{-- Sección de KPIs (Tarjetas de resumen) --}}
    <div class="row mb-4">
        @foreach($marcaMasVendida->take(1) as $mes => $marca)
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white p-3 mb-3" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-uppercase small mb-1">Top Marca ({{ $mes }})</p>
                        <h4 class="mb-0">{{ $marca->marca }}</h4>
                        <span class="font-weight-bold">S/ {{ number_format($marca->total, 2) }}</span>
                    </div>
                    <i class="fas fa-trophy fa-2x opacity-5"></i>
                </div>
            </div>
        </div>
        @endforeach

        @foreach($marcaMenosVendida->take(1) as $mes => $marca)
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm bg-white p-3 mb-3 border-left-danger">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Menos Vendida</p>
                        <h4 class="mb-0 text-danger">{{ $marca->marca }}</h4>
                        <span class="text-dark">Bajo rendimiento</span>
                    </div>
                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Gráfico de Categorías --}}
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold d-flex align-items-center">
                    <i class="fas fa-tags mr-2 text-primary"></i> Ventas por Categoría
                </div>
                <div class="card-body">
                   <canvas id="chartCategorias"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de Marcas (Circular para variar) --}}
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fas fa-apple-alt mr-2 text-success"></i> Distribución por Marcas
                </div>
                <div class="card-body">
                 <canvas id="chartMarcas"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla Detallada con Tabs para no saturar --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-table mr-2"></i> Detalles de Ventas
        </div>
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#tab-categorias">Por Categoría</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#tab-marcas">Por Marca</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-categorias">
                    <table class="table table-hover datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>Mes</th>
                                <th>Categoría</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventasPorCategoria as $venta)
                            <tr>
                                <td><span class="badge badge-info">{{ $venta->mes }}</span></td>
                                <td>{{ $venta->categoria }}</td>
                                <td class="font-weight-bold text-right">S/ {{ number_format($venta->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab-marcas">
                    <table class="table table-hover datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>Mes</th>
                                <th>Marca</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventasPorMarca as $venta)
                            <tr>
                                <td><span class="badge badge-primary">{{ $venta->mes }}</span></td>
                                <td>{{ $venta->marca }}</td>
                                <td class="font-weight-bold text-right">S/ {{ number_format($venta->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const ventasCategoria = @json($ventasPorCategoria);
    const ventasMarca = @json($ventasPorMarca);
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Añadimos DataTables para tablas inteligentes --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json" },
            "pageLength": 5
        });
    });

    // Configuración de Gráfico de Categorías (Barras con Gradiente)
    const ctxCat = document.getElementById('chartCategorias').getContext('2d');
    const gradCat = ctxCat.createLinearGradient(0, 0, 0, 400);
    gradCat.addColorStop(0, 'rgba(78, 115, 223, 1)');
    gradCat.addColorStop(1, 'rgba(78, 115, 223, 0.1)');

    new Chart(ctxCat, {
        type: 'bar',
        data: {
            labels: @json($ventasPorCategoria->pluck('categoria')),
            datasets: [{
                label: 'Soles (S/)',
                data: @json($ventasPorCategoria->pluck('total')),
                backgroundColor: gradCat,
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { grid: { display: false } } }
        }
    });

    // Gráfico de Marcas (Donut para dinamismo)
    new Chart(document.getElementById('chartMarcas'), {
        type: 'doughnut',
        data: {
            labels: @json($ventasPorMarca->pluck('marca')),
            datasets: [{
                data: @json($ventasPorMarca->pluck('total')),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                hoverOffset: 20
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true } }
            }
        }
    });
</script>

<style>
    .border-left-danger { border-left: 5px solid #e74a3b !important; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
    .nav-pills .nav-link.active { background-color: #4e73df; }
</style>
@endsection