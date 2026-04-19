<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Ventas por categoría (mensual)
        $ventasPorCategoria = DB::table('sale_details as sd')
            ->join('products as p', 'sd.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->join('sales as s', 'sd.sale_id', '=', 's.id')
            ->select(
                'c.name as categoria', // 👈 alias para usar en Blade
                DB::raw('DATE_FORMAT(s.date, "%Y-%m") as mes'),
                DB::raw('SUM(sd.quantity * sd.price) as total')
            )
            ->groupBy('c.name', 'mes')
            ->orderBy('mes', 'desc')
            ->get();

        // Ventas por marca (mensual)
        $ventasPorMarca = DB::table('sale_details as sd')
            ->join('products as p', 'sd.product_id', '=', 'p.id')
            ->join('brands as b', 'p.brand_id', '=', 'b.id')
            ->join('sales as s', 'sd.sale_id', '=', 's.id')
            ->select(
                'b.name as marca', // 👈 alias para usar en Blade
                DB::raw('DATE_FORMAT(s.date, "%Y-%m") as mes'),
                DB::raw('SUM(sd.quantity * sd.Price) as total')
            )
            ->groupBy('b.name', 'mes')
            ->orderBy('mes', 'desc')
            ->get();

        // Marca más vendida por mes
        $marcaMasVendida = DB::table('sale_details as sd')
            ->join('products as p', 'sd.product_id', '=', 'p.id')
            ->join('brands as b', 'p.brand_id', '=', 'b.id')
            ->join('sales as s', 'sd.sale_id', '=', 's.id')
            ->select(
                DB::raw('DATE_FORMAT(s.date, "%Y-%m") as mes'),
                'b.name as marca',
                DB::raw('SUM(sd.quantity * sd.Price) as total')
            )
            ->groupBy('mes', 'b.name')
            ->orderBy('mes', 'desc')
            ->orderByDesc('total')
            ->get()
            ->groupBy('mes')
            ->map(fn($items) => $items->first());

        // Marca menos vendida por mes
        $marcaMenosVendida = DB::table('sale_details as sd')
            ->join('products as p', 'sd.product_id', '=', 'p.id')
            ->join('brands as b', 'p.brand_id', '=', 'b.id')
            ->join('sales as s', 'sd.sale_id', '=', 's.id')
            ->select(
                DB::raw('DATE_FORMAT(s.date, "%Y-%m") as mes'),
                'b.name as marca',
                DB::raw('SUM(sd.quantity * sd.Price) as total')
            )
            ->groupBy('mes', 'b.name')
            ->orderBy('mes', 'desc')
            ->orderBy('total', 'asc')
            ->get()
            ->groupBy('mes')
            ->map(fn($items) => $items->first());

        return view('admin.reports.index', compact(
            'ventasPorCategoria',
            'ventasPorMarca',
            'marcaMasVendida',
            'marcaMenosVendida'
        ));
    }
}