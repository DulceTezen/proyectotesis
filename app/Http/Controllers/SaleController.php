<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index(Request $request){
        $sales = Sale::active()->when($request->number, function($query, $number){
            return $query->where('number', 'LIKE', '%'.$number.'%');
        })->when($request->date, function($query, $date){
            return $query->whereDate('date', $date);
        })->orderBy('date', 'desc')->paginate(20);

        return view('admin.sales.index', compact('sales'));
    }

    public function edit(Request $request, Sale $sale){
        return view('admin.sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale){
        $request->validate([
            'status' => 'required',
        ]);

        $sale->update($request->all());

        return redirect()->route('sales.index')->with('message', 'Registro actualizado');
    }

    public function destroy(Request $request, Sale $sale){
        $sale->update(['deleted' => 1]);

        return redirect()->route('sales.index')->with('message', 'Registro eliminado');
    }

    public function pdf(Request $request, Sale $sale, Fpdf $fpdf){
        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Image(asset('assets/web/images/logo.png'), 10, 10, 10);
    
        $fpdf->Cell(40, 10);
        $fpdf->Cell(90, 10, 'LEXUS', 0, 0, 'C');
    
        if($sale->voucher == 'Boleta'){
            $fpdf->MultiCell(60, 6, utf8_decode('BOLETA DE VENTA ELECTRÓNICA '.$sale->number), 1, 'C');
        }elseif($sale->voucher == 'Factura'){
            $fpdf->MultiCell(60, 6, utf8_decode('FACTURA ELECTRÓNICA '.$sale->number), 1, 'C');
        }
    
        $fpdf->Ln();
    
        // DATOS CLIENTE
        if($sale->voucher == 'Boleta'){
            $fpdf->SetFont('Arial', 'B', 12);
            $fpdf->Cell(30, 8, utf8_decode('Nombre:'));
            $fpdf->SetFont('Arial', '', 12);
            $fpdf->Cell(160, 8, utf8_decode($sale->client->name.' '.$sale->client->last_name));
    
            $fpdf->Ln();
            $fpdf->SetFont('Arial', 'B', 12);
            $fpdf->Cell(30, 8, 'DNI:');
            $fpdf->SetFont('Arial', '', 12);
            $fpdf->Cell(160, 8, $sale->client->document);

        }elseif($sale->voucher == 'Factura'){
            $fpdf->SetFont('Arial', 'B', 12);
            $fpdf->Cell(30, 8, utf8_decode('Razón social:'));
            $fpdf->SetFont('Arial', '', 12);
            $fpdf->Cell(160, 8, utf8_decode($sale->bussiness_name));
    
            $fpdf->Ln();
            $fpdf->SetFont('Arial', 'B', 12);
            $fpdf->Cell(30, 8, 'RUC:');
            $fpdf->SetFont('Arial', '', 12);
            $fpdf->Cell(160, 8, $sale->bussiness_document);

        }

        $fpdf->Ln();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(30, 8, utf8_decode('Dirección:'));
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->MultiCell(160, 8, utf8_decode($this->pdfText($sale->address)));

        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(30, 8, utf8_decode('Referencia:'));
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->MultiCell(160, 8, utf8_decode($this->pdfText($sale->reference)));
        $fpdf->SetY($fpdf->GetY() - 8);

        // 📞 TELÉFONO
        $fpdf->Ln();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(30, 8, utf8_decode('Teléfono:'));
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->Cell(160, 8, $sale->client->phone ?? '-');
    
        $fpdf->Ln();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(30, 8, utf8_decode('Emisión:'));
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->Cell(160, 8, $sale->date);
    
        $fpdf->Ln();
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(30, 8, utf8_decode('Moneda:'));
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->Cell(160, 8, 'Soles peruanos');
    
        // TABLA
        $fpdf->Ln(20);
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(200, 200, 200);
    
        $fpdf->Cell(100, 8, 'Producto', 1, 0, 'C', 1);
        $fpdf->Cell(30, 8, 'Precio', 1, 0, 'C', 1);
        $fpdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', 1);
        $fpdf->Cell(30, 8, 'Subtotal', 1, 0, 'C', 1);
    
        $fpdf->SetFont('Arial', '', 10);
        $fpdf->Ln();
    
        $subtotalProductos = 0;
    
        foreach($sale->details as $detail){
            $subtotalLinea = $detail->price * $detail->quantity;
            $subtotalProductos += $subtotalLinea;
    
            $fpdf->Cell(100, 8, utf8_decode($detail->product->name), 1);
            $fpdf->Cell(30, 8, 'S/. ' . number_format($detail->price, 2), 1, 0, 'R');
            $fpdf->Cell(30, 8, $detail->quantity, 1, 0, 'R');
            $fpdf->Cell(30, 8, 'S/. ' . number_format($subtotalLinea, 2), 1, 0, 'R');
            $fpdf->Ln();
        }
    
        // DELIVERY (solo en tabla si quieres)
        if ($sale->delivery_price > 0) {
            $fpdf->Cell(100, 8, utf8_decode('Servicio de delivery'), 1);
            $fpdf->Cell(30, 8, 'S/. ' .  number_format($sale->delivery_price, 2), 1, 0, 'R');
            $fpdf->Cell(30, 8, 1, 1, 0, 'R');
            $fpdf->Cell(30, 8, number_format($sale->delivery_price, 2), 1, 0, 'R');
            $fpdf->Ln();
        }
    
        // 🔥 CÁLCULOS CORRECTOS (UN SOLO TOTAL)
        $total = $sale->total;
    
        $subtotal = $total / 1.18;
        $igv = $total - $subtotal;
    
        $subtotal = round($subtotal, 2);
        $igv = round($igv, 2);
    
        // RESUMEN
        $fpdf->Ln(5);
        $fpdf->Cell(190, 0, '', 'T');
        $fpdf->Ln(3);
    
        $fpdf->SetFont('Arial', '', 10);
    
        $fpdf->Cell(150, 6, 'SUBTOTAL', 0, 0, 'R');
        $fpdf->Cell(40, 6, 'S/. ' . number_format($subtotal, 2), 0, 0, 'R');
        $fpdf->Ln();
    
        $fpdf->Cell(150, 6, 'IGV (18%)', 0, 0, 'R');
        $fpdf->Cell(40, 6, 'S/. ' .  number_format($igv, 2), 0, 0, 'R');
        $fpdf->Ln();
    
        if ($sale->delivery_price > 0) {
            $fpdf->Cell(150, 6, 'DELIVERY', 0, 0, 'R');
            $fpdf->Cell(40, 6, 'S/. ' . number_format($sale->delivery_price, 2), 0, 0, 'R');
            $fpdf->Ln();
        }
    
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell(150, 8, 'TOTAL', 0, 0, 'R');
        $fpdf->Cell(40, 8, 'S/. ' . number_format($total, 2), 0, 0, 'R');
    
        // ENVÍO PDF + MAIL
        $pdfContent = $fpdf->Output('S');
    
        \Mail::send([], [], function ($message) use ($sale, $pdfContent) {
            $message->to($sale->client->email)
                ->subject('Tu comprobante de pago - LEXUS')
                ->attachData($pdfContent, "boleta_{$sale->number}.pdf", [
                    'mime' => 'application/pdf',
                ])
                ->html('<p>Estimado cliente, adjuntamos tu comprobante de pago. ¡Gracias por tu compra en LEXUS!</p>');
        });
    
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="boleta_'.$sale->number.'.pdf"');
    }

    private function pdfText($value): string
    {
        $value = trim((string) $value);

        return $value === '' ? '-' : $value;
    }
}
