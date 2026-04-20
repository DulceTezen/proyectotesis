@extends('admin.template')

@section('title', 'Reportes')

@section('content')
@php
    $maxMonthly = max(1, (float) ($monthlyTotals->max('total') ?? 0));
    $maxBrand = max(1, (float) ($brandSummary->max('total') ?? 0));
    $maxCategory = max(1, (float) ($categorySummary->max('total') ?? 0));
    $resultLabels = [
        'all' => 'Todas las ventas',
        'top' => 'Ventas mayores',
        'low' => 'Ventas menores',
    ];
@endphp

<div class="col-12 reports-page">
    <div class="reports-hero mb-4">
        <div>
            <span class="reports-kicker">Analitica comercial</span>
            <h2 class="h3 mb-2">Panel de reportes de ventas</h2>
            <p class="mb-0 text-gray-700">
                Filtra ventas por periodo, marca, categoria y estado. Los importes por marca/categoria se calculan desde productos vendidos, sin repartir delivery.
            </p>
        </div>
        <div class="reports-actions">
            <a class="btn btn-outline-secondary" href="{{ route('reports.index') }}">Limpiar filtros</a>
            <a class="btn btn-primary" target="_blank" href="{{ route('reports.pdf', request()->query()) }}">Descargar PDF</a>
        </div>
    </div>

    <form class="reports-filter mb-4" method="GET" action="{{ route('reports.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Anio</label>
                <select class="form-select" name="year">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected((int) $filters['year'] === (int) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Mes</label>
                <select class="form-select" name="month">
                    <option value="">Todos</option>
                    @foreach($months as $number => $month)
                        <option value="{{ $number }}" @selected((int) $filters['month'] === (int) $number)>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="start_date" value="{{ $filters['start_date'] }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="end_date" value="{{ $filters['end_date'] }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Marca</label>
                <select class="form-select" name="brand_id">
                    <option value="">Todas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected((int) $filters['brand_id'] === (int) $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Categoria / publico</label>
                <select class="form-select" name="category_id">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) $filters['category_id'] === (int) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Vista</label>
                <select class="form-select" name="result_type">
                    @foreach($resultLabels as $value => $label)
                        <option value="{{ $value }}" @selected($filters['result_type'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="status">
                    <option value="">Todos los estados</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Aplicar filtros</button>
            </div>
            <div class="col-md-3">
                <div class="reports-filter-note">
                    {{ $filterSummary }}
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi">
                <span>Total vendido</span>
                <strong>S/ {{ number_format($kpis['totalSold'], 2) }}</strong>
                <small>Productos vendidos filtrados</small>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi report-kpi-green">
                <span>Pedidos</span>
                <strong>{{ $kpis['ordersCount'] }}</strong>
                <small>Ventas distintas del periodo</small>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi report-kpi-amber">
                <span>Unidades</span>
                <strong>{{ $kpis['unitsSold'] }}</strong>
                <small>Pares/productos vendidos</small>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi report-kpi-rose">
                <span>Ticket promedio</span>
                <strong>S/ {{ number_format($kpis['averageTicket'], 2) }}</strong>
                <small>Promedio por venta</small>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="report-insight">
                <span>Marca con mayor venta</span>
                <strong>{{ $kpis['topBrand']->brand ?? 'Sin datos' }}</strong>
                <small>S/ {{ number_format($kpis['topBrand']->total ?? 0, 2) }}</small>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="report-insight report-insight-low">
                <span>Marca con menor venta</span>
                <strong>{{ $kpis['lowBrand']->brand ?? 'Sin datos' }}</strong>
                <small>S/ {{ number_format($kpis['lowBrand']->total ?? 0, 2) }}</small>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="report-insight report-insight-category">
                <span>Categoria / publico lider</span>
                <strong>{{ $kpis['topCategory']->category ?? 'Sin datos' }}</strong>
                <small>S/ {{ number_format($kpis['topCategory']->total ?? 0, 2) }}</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-7">
            <section class="report-panel">
                <div class="report-panel-header">
                    <div>
                        <h3>Totales de venta por mes</h3>
                        <p>Tendencia mensual segun filtros activos.</p>
                    </div>
                </div>
                <div class="report-bars">
                    @forelse($monthlyTotals as $month)
                        <div class="report-bar-row">
                            <span>{{ $month->period }}</span>
                            <div class="report-bar-track">
                                <div class="report-bar-fill" style="width: {{ max(4, round(($month->total / $maxMonthly) * 100)) }}%"></div>
                            </div>
                            <strong>S/ {{ number_format($month->total, 2) }}</strong>
                        </div>
                    @empty
                        <div class="report-empty">No hay ventas mensuales para los filtros seleccionados.</div>
                    @endforelse
                </div>
            </section>
        </div>
        <div class="col-xl-5">
            <section class="report-panel">
                <div class="report-panel-header">
                    <div>
                        <h3>Ventas por categoria / publico</h3>
                        <p>Mujer, hombre, ninos, ninas u otras categorias registradas.</p>
                    </div>
                </div>
                <div class="report-bars report-bars-compact">
                    @forelse($categorySummary as $category)
                        <div class="report-bar-row">
                            <span>{{ $category->category }}</span>
                            <div class="report-bar-track">
                                <div class="report-bar-fill report-bar-fill-green" style="width: {{ max(4, round(($category->total / $maxCategory) * 100)) }}%"></div>
                            </div>
                            <strong>S/ {{ number_format($category->total, 2) }}</strong>
                        </div>
                    @empty
                        <div class="report-empty">No hay categorias con ventas para mostrar.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <section class="report-panel">
                <div class="report-panel-header">
                    <div>
                        <h3>Distribucion por marca</h3>
                        <p>Ranking por importe vendido.</p>
                    </div>
                </div>
                <div class="brand-list">
                    @forelse($brandSummary as $brand)
                        <div class="brand-row">
                            <div>
                                <strong>{{ $brand->brand }}</strong>
                                <small>{{ $brand->units }} unidades / {{ $brand->orders }} pedidos</small>
                            </div>
                            <span>S/ {{ number_format($brand->total, 2) }}</span>
                            <div class="brand-meter">
                                <div style="width: {{ max(4, round(($brand->total / $maxBrand) * 100)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="report-empty">No hay marcas con ventas para mostrar.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="col-xl-8">
            <section class="report-panel">
                <div class="report-panel-header report-panel-header-table">
                    <div>
                        <h3>Detalle por marca y categoria</h3>
                        <p>{{ $resultLabels[$filters['result_type']] }} segun el periodo seleccionado.</p>
                    </div>
                    <span class="report-count">{{ $reportRows->count() }} filas</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th>Marca</th>
                                <th>Categoria / publico</th>
                                <th class="text-end">Unidades</th>
                                <th class="text-end">Pedidos</th>
                                <th>Ultima venta</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportRows as $row)
                                <tr>
                                    <td class="fw-bold text-gray-900">{{ $row->brand }}</td>
                                    <td>{{ $row->category }}</td>
                                    <td class="text-end">{{ $row->units }}</td>
                                    <td class="text-end">{{ $row->orders }}</td>
                                    <td>{{ $row->last_sale ? \Carbon\Carbon::parse($row->last_sale)->format('d/m/Y') : '-' }}</td>
                                    <td class="text-end fw-bold">S/ {{ number_format($row->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="report-empty my-3">No se encontraron ventas para los filtros seleccionados.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .reports-page {
        color: #1f2937;
    }

    .reports-hero,
    .reports-filter,
    .report-panel,
    .report-kpi,
    .report-insight {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 14px 34px rgba(31, 41, 55, 0.07);
    }

    .reports-hero {
        align-items: center;
        display: flex;
        gap: 18px;
        justify-content: space-between;
        padding: 24px;
    }

    .reports-kicker {
        color: #0f766e;
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .reports-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .reports-filter {
        padding: 20px;
    }

    .reports-filter-note {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #4b5563;
        font-size: 12px;
        line-height: 1.4;
        min-height: 42px;
        padding: 9px 12px;
    }

    .report-kpi,
    .report-insight {
        height: 100%;
        padding: 18px;
        position: relative;
    }

    .report-kpi:before,
    .report-insight:before {
        background: #7c3aed;
        border-radius: 8px;
        content: "";
        height: 42px;
        position: absolute;
        right: 18px;
        top: 18px;
        width: 6px;
    }

    .report-kpi-green:before,
    .report-insight-category:before,
    .report-bar-fill-green {
        background: #0f766e;
    }

    .report-kpi-amber:before {
        background: #d97706;
    }

    .report-kpi-rose:before,
    .report-insight-low:before {
        background: #be123c;
    }

    .report-kpi span,
    .report-insight span {
        color: #6b7280;
        display: block;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .report-kpi strong,
    .report-insight strong {
        color: #111827;
        display: block;
        font-size: 26px;
        line-height: 1.15;
    }

    .report-insight strong {
        font-size: 22px;
    }

    .report-kpi small,
    .report-insight small,
    .brand-row small {
        color: #6b7280;
        display: block;
        margin-top: 7px;
    }

    .report-panel {
        overflow: hidden;
    }

    .report-panel-header {
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        padding: 18px 20px;
    }

    .report-panel-header h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .report-panel-header p {
        color: #6b7280;
        margin: 4px 0 0;
    }

    .report-count {
        background: #f3f4f6;
        border-radius: 8px;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        padding: 8px 10px;
    }

    .report-bars,
    .brand-list {
        display: grid;
        gap: 14px;
        padding: 20px;
    }

    .report-bars-compact {
        gap: 12px;
    }

    .report-bar-row {
        align-items: center;
        display: grid;
        gap: 12px;
        grid-template-columns: 86px 1fr 112px;
    }

    .report-bar-row span {
        color: #374151;
        font-weight: 700;
    }

    .report-bar-row strong {
        color: #111827;
        text-align: right;
    }

    .report-bar-track,
    .brand-meter {
        background: #eef2f7;
        border-radius: 8px;
        height: 10px;
        overflow: hidden;
    }

    .report-bar-fill,
    .brand-meter div {
        background: #7c3aed;
        border-radius: 8px;
        height: 100%;
    }

    .brand-row {
        display: grid;
        gap: 8px;
    }

    .brand-row > div:first-child {
        display: flex;
        flex-direction: column;
    }

    .brand-row > span {
        color: #111827;
        font-weight: 700;
    }

    .brand-meter div {
        background: #d97706;
    }

    .report-empty {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        color: #64748b;
        padding: 18px;
        text-align: center;
    }

    @media (max-width: 991px) {
        .reports-hero,
        .report-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .reports-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 575px) {
        .report-bar-row {
            grid-template-columns: 1fr;
        }

        .report-bar-row strong {
            text-align: left;
        }
    }
</style>
@endsection
