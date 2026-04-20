<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Sale;
use Carbon\Carbon;

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
        $sale->loadMissing(['client', 'details.product']);

        $fpdf->SetMargins(12, 12, 12);
        $fpdf->SetAutoPageBreak(true, 18);
        $fpdf->AddPage();
        $fpdf->SetTitle($this->pdfEncode($this->voucherTitle($sale).' '.$sale->number));
        $fpdf->SetAuthor($this->pdfEncode('LEXUS'));

        $this->drawDocumentHeader($fpdf, $sale);
        $this->drawCustomerBlock($fpdf, $sale);
        $this->drawItemsTable($fpdf, $sale);
        $this->drawTotalsBlock($fpdf, $sale);
        $this->drawFooter($fpdf);

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

    private function drawDocumentHeader(Fpdf $fpdf, Sale $sale): void
    {
        $x = 12;
        $y = 12;
        $w = 186;

        $fpdf->SetDrawColor(220, 224, 228);
        $fpdf->SetFillColor(247, 248, 250);
        $fpdf->Rect($x, $y, $w, 34, 'FD');

        $logoPath = public_path('assets/web/images/logo.png');
        if (is_file($logoPath)) {
            $fpdf->Image($logoPath, $x + 5, $y + 6, 15);
        }

        $fpdf->SetXY($x + 24, $y + 8);
        $fpdf->SetTextColor(17, 17, 17);
        $fpdf->SetFont('Arial', 'B', 18);
        $fpdf->Cell(70, 7, $this->pdfEncode('LEXUS'), 0, 2, 'L');
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetTextColor(92, 98, 105);
        $fpdf->Cell(78, 5, $this->pdfEncode('Zapatillas originales e importadas'), 0, 2, 'L');
        $fpdf->Cell(78, 5, $this->pdfEncode('Compra online segura y atención personalizada'), 0, 2, 'L');

        $boxW = 62;
        $boxX = $x + $w - $boxW - 5;
        $boxY = $y + 6;

        $fpdf->SetDrawColor(17, 17, 17);
        $fpdf->SetFillColor(255, 255, 255);
        $fpdf->Rect($boxX, $boxY, $boxW, 22, 'FD');
        $fpdf->SetFillColor(214, 51, 132);
        $fpdf->Rect($boxX, $boxY, $boxW, 3, 'F');

        $fpdf->SetXY($boxX + 3, $boxY + 6);
        $fpdf->SetTextColor(17, 17, 17);
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->MultiCell($boxW - 6, 5, $this->pdfEncode($this->voucherTitle($sale)), 0, 'C');

        $fpdf->SetXY($boxX + 3, $boxY + 16);
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->Cell($boxW - 6, 5, $this->pdfEncode($sale->number), 0, 0, 'C');

        $fpdf->SetY($y + 52);
    }

    private function drawCustomerBlock(Fpdf $fpdf, Sale $sale): void
    {
        $rows = $this->customerRows($sale);
        $x = 12;
        $y = $fpdf->GetY();
        $w = 186;
        $labelW = 38;

        $this->drawSectionTitle($fpdf, 'Datos del cliente');
        $fpdf->SetY($y + 8);

        foreach ($rows as $index => $row) {
            $this->drawLabelValueRow(
                $fpdf,
                $row[0],
                $row[1],
                $x,
                $fpdf->GetY(),
                $labelW,
                $w - $labelW,
                $index % 2 === 0
            );
        }

        $fpdf->Ln(10);
    }

    private function drawItemsTable(Fpdf $fpdf, Sale $sale): void
    {
        $this->ensureRoom($fpdf, 32);
        $this->drawSectionTitle($fpdf, 'Detalle de productos');
        $fpdf->Ln(3);
        $this->drawItemsHeader($fpdf);

        foreach($sale->details as $detail){
            $price = (float) $detail->price;
            $quantity = (int) $detail->quantity;
            $lineTotal = $price * $quantity;
            $productName = $detail->product?->name ?? 'Producto no disponible';
            $rowHeight = $this->tableRowHeight($fpdf, [$productName, $this->money($price), (string) $quantity, $this->money($lineTotal)]);

            if ($fpdf->GetY() + $rowHeight > 270) {
                $fpdf->AddPage();
                $this->drawContinuationHeader($fpdf);
                $this->drawItemsHeader($fpdf);
            }

            $this->drawTableRow($fpdf, [$productName, $this->money($price), (string) $quantity, $this->money($lineTotal)], ['L', 'R', 'C', 'R'], $rowHeight);
        }

        if ((float) $sale->delivery_price > 0) {
            $deliveryRow = ['Servicio de delivery', $this->money($sale->delivery_price), '1', $this->money($sale->delivery_price)];
            $rowHeight = $this->tableRowHeight($fpdf, $deliveryRow);

            if ($fpdf->GetY() + $rowHeight > 270) {
                $fpdf->AddPage();
                $this->drawContinuationHeader($fpdf);
                $this->drawItemsHeader($fpdf);
            }

            $this->drawTableRow($fpdf, $deliveryRow, ['L', 'R', 'C', 'R'], $rowHeight);
        }

        $fpdf->Ln(8);
    }

    private function drawTotalsBlock(Fpdf $fpdf, Sale $sale): void
    {
        $this->ensureRoom($fpdf, 46);

        $total = (float) $sale->total;
        $subtotal = round($total / 1.18, 2);
        $igv = round($total - $subtotal, 2);
        $deliveryPrice = (float) $sale->delivery_price;

        $x = 118;
        $y = $fpdf->GetY();
        $w = 80;

        $fpdf->SetDrawColor(220, 224, 228);
        $fpdf->SetFillColor(247, 248, 250);
        $fpdf->Rect($x, $y, $w, $deliveryPrice > 0 ? 37 : 30, 'FD');

        $fpdf->SetXY($x + 5, $y + 5);
        $this->drawTotalLine($fpdf, 'SUBTOTAL', $this->money($subtotal), $w - 10);
        $this->drawTotalLine($fpdf, 'IGV (18%)', $this->money($igv), $w - 10);

        if ($deliveryPrice > 0) {
            $this->drawTotalLine($fpdf, 'DELIVERY', $this->money($deliveryPrice), $w - 10);
        }

        $fpdf->SetX($x + 5);
        $fpdf->SetFillColor(17, 17, 17);
        $fpdf->SetTextColor(255, 255, 255);
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->Cell(34, 9, $this->pdfEncode('TOTAL'), 0, 0, 'L', true);
        $fpdf->Cell($w - 44, 9, $this->pdfEncode($this->money($total)), 0, 1, 'R', true);
        $fpdf->SetTextColor(17, 17, 17);
    }

    private function drawFooter(Fpdf $fpdf): void
    {
        $fpdf->SetY(-22);
        $fpdf->SetDrawColor(220, 224, 228);
        $fpdf->Line(12, $fpdf->GetY(), 198, $fpdf->GetY());
        $fpdf->Ln(4);
        $fpdf->SetFont('Arial', '', 8);
        $fpdf->SetTextColor(92, 98, 105);
        $fpdf->Cell(186, 5, $this->pdfEncode('Gracias por tu compra en LEXUS. Documento generado electrónicamente.'), 0, 0, 'C');
        $fpdf->SetTextColor(17, 17, 17);
    }

    private function drawContinuationHeader(Fpdf $fpdf): void
    {
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetTextColor(17, 17, 17);
        $fpdf->Cell(186, 8, $this->pdfEncode('Detalle de productos - continuación'), 0, 1, 'L');
        $fpdf->Ln(2);
    }

    private function drawSectionTitle(Fpdf $fpdf, string $title): void
    {
        $fpdf->SetX(12);
        $fpdf->SetFillColor(214, 51, 132);
        $fpdf->Rect(12, $fpdf->GetY() + 1, 3, 6, 'F');
        $fpdf->SetX(18);
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->SetTextColor(17, 17, 17);
        $fpdf->Cell(180, 8, $this->pdfEncode(strtoupper($title)), 0, 1, 'L');
    }

    private function drawLabelValueRow(Fpdf $fpdf, string $label, string $value, float $x, float $y, float $labelW, float $valueW, bool $filled): void
    {
        $fpdf->SetFont('Arial', '', 9);
        $lineHeight = 5;
        $rowHeight = max(8, ($this->lineCount($fpdf, $valueW - 6, $value) * $lineHeight) + 3);

        $fpdf->SetDrawColor(231, 234, 237);
        $fpdf->SetFillColor($filled ? 250 : 255, $filled ? 251 : 255, $filled ? 252 : 255);
        $fpdf->Rect($x, $y, $labelW, $rowHeight, 'FD');
        $fpdf->Rect($x + $labelW, $y, $valueW, $rowHeight, 'FD');

        $fpdf->SetXY($x + 3, $y + 2);
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->SetTextColor(68, 74, 80);
        $fpdf->Cell($labelW - 6, $lineHeight, $this->pdfEncode($label), 0, 0, 'L');

        $fpdf->SetXY($x + $labelW + 3, $y + 2);
        $fpdf->SetFont('Arial', '', 9);
        $fpdf->SetTextColor(17, 17, 17);
        $fpdf->MultiCell($valueW - 6, $lineHeight, $this->pdfEncode($this->pdfText($value)), 0, 'L');
        $fpdf->SetY($y + $rowHeight);
    }

    private function drawItemsHeader(Fpdf $fpdf): void
    {
        $widths = [92, 32, 28, 34];
        $headers = ['Producto', 'Precio', 'Cantidad', 'Subtotal'];

        $fpdf->SetX(12);
        $fpdf->SetFillColor(17, 17, 17);
        $fpdf->SetDrawColor(17, 17, 17);
        $fpdf->SetTextColor(255, 255, 255);
        $fpdf->SetFont('Arial', 'B', 9);

        foreach ($headers as $index => $header) {
            $fpdf->Cell($widths[$index], 9, $this->pdfEncode($header), 1, 0, 'C', true);
        }

        $fpdf->Ln();
        $fpdf->SetTextColor(17, 17, 17);
    }

    private function drawTableRow(Fpdf $fpdf, array $values, array $aligns, float $height): void
    {
        $widths = [92, 32, 28, 34];
        $x = 12;
        $y = $fpdf->GetY();

        $fpdf->SetFont('Arial', '', 9);
        $fpdf->SetDrawColor(220, 224, 228);
        $fpdf->SetFillColor(255, 255, 255);

        foreach ($values as $index => $value) {
            $fpdf->Rect($x, $y, $widths[$index], $height, 'D');
            $fpdf->SetXY($x + 2, $y + 2);
            $fpdf->MultiCell($widths[$index] - 4, 5, $this->pdfEncode($this->pdfText($value)), 0, $aligns[$index]);
            $x += $widths[$index];
        }

        $fpdf->SetY($y + $height);
    }

    private function drawTotalLine(Fpdf $fpdf, string $label, string $value, float $width): void
    {
        $fpdf->SetTextColor(68, 74, 80);
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->Cell(34, 7, $this->pdfEncode($label), 0, 0, 'L');
        $fpdf->SetTextColor(17, 17, 17);
        $fpdf->SetFont('Arial', '', 9);
        $fpdf->Cell($width - 34, 7, $this->pdfEncode($value), 0, 1, 'R');
        $fpdf->SetX(123);
    }

    private function customerRows(Sale $sale): array
    {
        if ($sale->voucher === 'Factura') {
            $rows = [
                ['Razón social:', $this->pdfText($sale->bussiness_name)],
                ['RUC:', $this->pdfText($sale->bussiness_document)],
            ];
        } else {
            $rows = [
                ['Nombre:', trim($this->pdfText($sale->client?->name).' '.$this->pdfText($sale->client?->last_name))],
                ['DNI:', $this->pdfText($sale->client?->document)],
            ];
        }

        return array_merge($rows, [
            ['Dirección:', $this->pdfText($sale->address)],
            ['Referencia:', $this->pdfText($sale->reference)],
            ['Teléfono:', $this->pdfText($sale->client?->phone ?? $sale->phone)],
            ['Emisión:', $this->formattedDate($sale->date)],
            ['Moneda:', 'Soles peruanos'],
        ]);
    }

    private function tableRowHeight(Fpdf $fpdf, array $values): float
    {
        $widths = [92, 32, 28, 34];
        $maxLines = 1;

        $fpdf->SetFont('Arial', '', 9);
        foreach ($values as $index => $value) {
            $maxLines = max($maxLines, $this->lineCount($fpdf, $widths[$index] - 4, (string) $value));
        }

        return max(8, ($maxLines * 5) + 4);
    }

    private function lineCount(Fpdf $fpdf, float $width, string $text): int
    {
        $text = str_replace("\r", '', $this->pdfEncode($this->pdfText($text)));
        $lines = 0;

        foreach (explode("\n", $text) as $paragraph) {
            if ($paragraph === '') {
                $lines++;
                continue;
            }

            $line = '';
            $length = strlen($paragraph);

            for ($i = 0; $i < $length; $i++) {
                $candidate = $line.$paragraph[$i];

                if ($fpdf->GetStringWidth($candidate) <= $width) {
                    $line = $candidate;
                    continue;
                }

                $lines++;
                $line = $paragraph[$i];
            }

            $lines++;
        }

        return max(1, $lines);
    }

    private function ensureRoom(Fpdf $fpdf, float $height): void
    {
        if ($fpdf->GetY() + $height > 270) {
            $fpdf->AddPage();
        }
    }

    private function voucherTitle(Sale $sale): string
    {
        return $sale->voucher === 'Factura' ? 'FACTURA ELECTRÓNICA' : 'BOLETA DE VENTA ELECTRÓNICA';
    }

    private function formattedDate($date): string
    {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)->format('d/m/Y H:i');
    }

    private function money($value): string
    {
        return 'S/. '.number_format((float) $value, 2);
    }

    private function pdfText($value): string
    {
        $value = trim((string) $value);

        return $value === '' ? '-' : $value;
    }

    private function pdfEncode($value): string
    {
        $value = (string) $value;

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $value);

            if ($converted !== false) {
                return $converted;
            }
        }

        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
        }

        return strtr($value, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N',
        ]);
    }
}
