<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $report = $this->buildReport($filters);

        return view('admin.reports.index', array_merge($report, [
            'filters' => $filters,
            'brands' => Brand::active()->orderBy('name')->get(),
            'categories' => Category::active()->orderBy('name')->get(),
            'months' => $this->months(),
            'years' => $this->availableYears(),
            'statuses' => $this->statuses(),
        ]));
    }

    public function pdf(Request $request, Fpdf $fpdf)
    {
        $filters = $this->resolveFilters($request);
        $report = $this->buildReport($filters);

        $fpdf->AddPage();
        $fpdf->SetMargins(12, 12, 12);
        $fpdf->SetFont('Arial', 'B', 15);
        $fpdf->Cell(0, 8, 'LEXUS - Reporte de ventas', 0, 1, 'C');

        $fpdf->SetFont('Arial', '', 9);
        $fpdf->Cell(0, 6, utf8_decode('Filtros: '.$this->filterSummary($filters)), 0, 1, 'C');
        $fpdf->Ln(4);

        $fpdf->SetFillColor(238, 242, 247);
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->Cell(47, 7, 'Total vendido', 1, 0, 'C', true);
        $fpdf->Cell(47, 7, 'Pedidos', 1, 0, 'C', true);
        $fpdf->Cell(47, 7, 'Unidades', 1, 0, 'C', true);
        $fpdf->Cell(47, 7, 'Ticket prom.', 1, 1, 'C', true);
        $fpdf->SetFont('Arial', '', 10);
        $fpdf->Cell(47, 8, 'S/ '.number_format($report['kpis']['totalSold'], 2), 1, 0, 'C');
        $fpdf->Cell(47, 8, $report['kpis']['ordersCount'], 1, 0, 'C');
        $fpdf->Cell(47, 8, $report['kpis']['unitsSold'], 1, 0, 'C');
        $fpdf->Cell(47, 8, 'S/ '.number_format($report['kpis']['averageTicket'], 2), 1, 1, 'C');

        $fpdf->Ln(5);
        $this->pdfMetricLine($fpdf, 'Marca con mayor venta', $report['kpis']['topBrand']);
        $this->pdfMetricLine($fpdf, 'Marca con menor venta', $report['kpis']['lowBrand']);
        $this->pdfMetricLine($fpdf, 'Categoria lider', $report['kpis']['topCategory']);

        $fpdf->Ln(5);
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->Cell(0, 7, 'Totales por mes', 0, 1);
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->Cell(45, 7, 'Mes', 1);
        $fpdf->Cell(40, 7, 'Total', 1);
        $fpdf->Cell(40, 7, 'Unidades', 1);
        $fpdf->Cell(40, 7, 'Pedidos', 1, 1);
        $fpdf->SetFont('Arial', '', 9);
        foreach ($report['monthlyTotals']->take(8) as $month) {
            $fpdf->Cell(45, 7, $month->period, 1);
            $fpdf->Cell(40, 7, 'S/ '.number_format($month->total, 2), 1, 0, 'R');
            $fpdf->Cell(40, 7, $month->units, 1, 0, 'R');
            $fpdf->Cell(40, 7, $month->orders, 1, 1, 'R');
        }

        $fpdf->Ln(5);
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->Cell(0, 7, 'Detalle por marca y categoria', 0, 1);
        $this->pdfReportTableHeader($fpdf);
        foreach ($report['reportRows'] as $row) {
            $this->pdfEnsureRoom($fpdf, 9);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->Cell(44, 7, utf8_decode($this->limitPdfText($row->brand, 24)), 1);
            $fpdf->Cell(44, 7, utf8_decode($this->limitPdfText($row->category, 24)), 1);
            $fpdf->Cell(25, 7, $row->units, 1, 0, 'R');
            $fpdf->Cell(25, 7, $row->orders, 1, 0, 'R');
            $fpdf->Cell(42, 7, 'S/ '.number_format($row->total, 2), 1, 1, 'R');
        }

        return response($fpdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="reporte_ventas.pdf"');
    }

    private function buildReport(array $filters): array
    {
        $base = $this->reportBaseQuery($filters);

        $reportRows = (clone $base)
            ->select(
                'b.name as brand',
                'c.name as category',
                DB::raw('SUM(sd.quantity) as units'),
                DB::raw('COUNT(DISTINCT s.id) as orders'),
                DB::raw('SUM(sd.quantity * sd.price) as total'),
                DB::raw('MAX(s.date) as last_sale')
            )
            ->groupBy('b.id', 'b.name', 'c.id', 'c.name');

        if ($filters['result_type'] === 'low') {
            $reportRows->orderBy('total')->limit(10);
        } elseif ($filters['result_type'] === 'top') {
            $reportRows->orderByDesc('total')->limit(10);
        } else {
            $reportRows->orderByDesc('total');
        }

        $reportRows = $reportRows->get();
        $brandSummary = $this->summaryBy($base, 'b.id', 'b.name', 'brand');
        $categorySummary = $this->summaryBy($base, 'c.id', 'c.name', 'category');
        $monthlyTotals = $this->monthlyTotals($base);
        $saleIds = (clone $base)->distinct()->pluck('s.id');

        return [
            'reportRows' => $reportRows,
            'brandSummary' => $brandSummary,
            'categorySummary' => $categorySummary,
            'monthlyTotals' => $monthlyTotals,
            'kpis' => [
                'totalSold' => (float) $reportRows->sum('total'),
                'ordersCount' => $saleIds->count(),
                'unitsSold' => (int) $reportRows->sum('units'),
                'averageTicket' => $this->averageTicket($saleIds),
                'topBrand' => $brandSummary->sortByDesc('total')->first(),
                'lowBrand' => $brandSummary->sortBy('total')->first(),
                'topCategory' => $categorySummary->sortByDesc('total')->first(),
            ],
            'filterSummary' => $this->filterSummary($filters),
        ];
    }

    private function reportBaseQuery(array $filters): Builder
    {
        return DB::table('sale_details as sd')
            ->join('sales as s', 'sd.sale_id', '=', 's.id')
            ->join('products as p', 'sd.product_id', '=', 'p.id')
            ->join('brands as b', 'p.brand_id', '=', 'b.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->where('s.deleted', 0)
            ->when($filters['brand_id'], fn ($query, $brandId) => $query->where('b.id', $brandId))
            ->when($filters['category_id'], fn ($query, $categoryId) => $query->where('c.id', $categoryId))
            ->when($filters['status'], fn ($query, $status) => $query->where('s.status', $status))
            ->when($filters['start_date'], fn ($query, $date) => $query->whereDate('s.date', '>=', $date))
            ->when($filters['end_date'], fn ($query, $date) => $query->whereDate('s.date', '<=', $date))
            ->when(!$filters['start_date'] && !$filters['end_date'] && $filters['year'], fn ($query) => $query->whereYear('s.date', $filters['year']))
            ->when(!$filters['start_date'] && !$filters['end_date'] && $filters['month'], fn ($query) => $query->whereMonth('s.date', $filters['month']));
    }

    private function summaryBy(Builder $base, string $idColumn, string $nameColumn, string $alias): Collection
    {
        return (clone $base)
            ->select(
                DB::raw($nameColumn.' as '.$alias),
                DB::raw('SUM(sd.quantity) as units'),
                DB::raw('COUNT(DISTINCT s.id) as orders'),
                DB::raw('SUM(sd.quantity * sd.price) as total')
            )
            ->groupBy($idColumn, $nameColumn)
            ->orderByDesc('total')
            ->get();
    }

    private function monthlyTotals(Builder $base): Collection
    {
        return (clone $base)
            ->select(
                DB::raw('DATE_FORMAT(s.date, "%Y-%m") as period'),
                DB::raw('SUM(sd.quantity * sd.price) as total'),
                DB::raw('SUM(sd.quantity) as units'),
                DB::raw('COUNT(DISTINCT s.id) as orders')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function averageTicket(Collection $saleIds): float
    {
        if ($saleIds->isEmpty()) {
            return 0;
        }

        return (float) DB::table('sales')
            ->whereIn('id', $saleIds->all())
            ->avg('total');
    }

    private function resolveFilters(Request $request): array
    {
        return [
            'year' => $request->filled('year') ? (int) $request->year : (int) now()->year,
            'month' => $request->filled('month') ? (int) $request->month : null,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'brand_id' => $request->filled('brand_id') ? (int) $request->brand_id : null,
            'category_id' => $request->filled('category_id') ? (int) $request->category_id : null,
            'result_type' => in_array($request->input('result_type'), ['all', 'top', 'low'], true) ? $request->input('result_type') : 'all',
            'status' => in_array($request->input('status'), $this->statuses(), true) ? $request->input('status') : null,
        ];
    }

    private function availableYears(): Collection
    {
        $years = DB::table('sales')
            ->where('deleted', 0)
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return $years->isEmpty() ? collect([now()->year]) : $years;
    }

    private function months(): array
    {
        return [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
    }

    private function statuses(): array
    {
        return ['Pendiente', 'En proceso', 'Completado', 'Rechazado'];
    }

    private function resultTypeLabels(): array
    {
        return [
            'all' => 'todas las ventas',
            'top' => 'ventas mayores',
            'low' => 'ventas menores',
        ];
    }

    private function filterSummary(array $filters): string
    {
        $parts = [];

        if ($filters['start_date'] || $filters['end_date']) {
            $parts[] = 'rango '.($filters['start_date'] ?: 'inicio').' a '.($filters['end_date'] ?: 'hoy');
        } else {
            $parts[] = 'anio '.$filters['year'];
            if ($filters['month']) {
                $parts[] = 'mes '.$this->months()[$filters['month']];
            }
        }

        if ($filters['brand_id']) {
            $parts[] = 'marca '.(Brand::whereKey($filters['brand_id'])->value('name') ?: '#'.$filters['brand_id']);
        }

        if ($filters['category_id']) {
            $parts[] = 'categoria '.(Category::whereKey($filters['category_id'])->value('name') ?: '#'.$filters['category_id']);
        }

        $parts[] = 'vista '.$this->resultTypeLabels()[$filters['result_type']];
        $parts[] = $filters['status'] ? 'estado '.$filters['status'] : 'todos los estados';

        return implode(' | ', $parts);
    }

    private function pdfMetricLine(Fpdf $fpdf, string $label, $metric): void
    {
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->Cell(48, 7, utf8_decode($label.':'), 0);
        $fpdf->SetFont('Arial', '', 9);

        if (!$metric) {
            $fpdf->Cell(0, 7, '-', 0, 1);
            return;
        }

        $name = $metric->brand ?? $metric->category ?? '-';
        $fpdf->Cell(0, 7, utf8_decode($name.' - S/ '.number_format($metric->total, 2)), 0, 1);
    }

    private function pdfReportTableHeader(Fpdf $fpdf): void
    {
        $fpdf->SetFont('Arial', 'B', 8);
        $fpdf->Cell(44, 7, 'Marca', 1);
        $fpdf->Cell(44, 7, 'Categoria', 1);
        $fpdf->Cell(25, 7, 'Unidades', 1);
        $fpdf->Cell(25, 7, 'Pedidos', 1);
        $fpdf->Cell(42, 7, 'Total', 1, 1);
    }

    private function pdfEnsureRoom(Fpdf $fpdf, int $height): void
    {
        if ($fpdf->GetY() + $height > 275) {
            $fpdf->AddPage();
            $this->pdfReportTableHeader($fpdf);
        }
    }

    private function limitPdfText(string $text, int $length): string
    {
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length - 3).'...' : $text;
    }
}
