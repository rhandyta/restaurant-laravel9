<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;

class ReceiptController extends Controller
{
    protected $pdf;
    public function __construct(Fpdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function header($name)
    {
        $lengthtext = $this->pdf->GetStringWidth('RK BILLABONG PERMAI NO.19-21, Kel. Cimanggis') - 15;
        $this->pdf->Cell(0, 4.5, strtoupper('BILLABONG PERMAI 02124774747'), 0, 1, 'C');
        $this->pdf->Cell(0, 4.5, strtoupper('RK BILLABONG PERMAI NO.19-21, Kel. Cimanggis'), 0, 1, 'C');
        $this->pdf->Cell(0, 4.5, strtoupper('kec. bojonggede, kab. bogor, 16320'), 0, 1, 'C');
        $this->pdf->Cell(0, 4.5, str_repeat('-', $lengthtext), 0, 1, 'C');
        $dateTime = date('d.m.Y-H:i');
        $transactionCashier = '3532/JOHN DOE /01';
        $widthText = $this->pdf->GetStringWidth($transactionCashier);
        $totalCellWidth = 150;
        $spaceInFront = ($totalCellWidth - $widthText) / 2;
        $spaceInBack = $totalCellWidth - $totalCellWidth - $spaceInFront;
        $this->pdf->Cell($spaceInFront, 0);
        $this->pdf->Cell($widthText, 4.5, $dateTime, 0, 0, 'C');
        $this->pdf->Cell($spaceInBack, 0);
        $this->pdf->Cell($spaceInFront, 0);
        $this->pdf->Cell($widthText, 4.5, $transactionCashier, 0, 1, 'C');
        $this->pdf->Cell($spaceInBack, 0, '', 0, 1);
        $this->pdf->Cell(0, 4.5, str_repeat('-', $lengthtext), 0, 1, 'C');
    }

    public function body() 
    {
    
        $data = '[
            {
            "id": 1,
            "product": "Tanggo",
            "price": 25000
            }, 
            {
            "id": 2,
            "product": "Sprit",
            "price": 25000
            }, 
            {
            "id": 3,
            "product": "Banana",
            "price": 25000
            }, 
            {
            "id": 4,
            "product": "Nasi 1kg",
            "price": 25000
            }, 
            {
            "id": 5,
            "product": "Fortune 2L",
            "price": 25000
            }
          ]';
          $decodeJson = json_decode($data, true);
          $i = 0;
          $total = 0;
        while($i < count($decodeJson)) {
            // Jumlah lebar empat sel berurutan
            $totalWidth = 25 * 4; 
            // Hitung posisi X untuk menengahkan baris
            $middleX = ($this->pdf->GetPageWidth() - $totalWidth) / 2;
            // Set posisi X
            $this->pdf->SetX($middleX);
            $this->pdf->Cell(25, 4.5, strtoupper($decodeJson[$i]['product']), 0, 0, 'C');
            $this->pdf->Cell(25, 4.5, '1', 0, 0, 'C');
            $this->pdf->Cell(25, 4.5, $decodeJson[$i]['price'], 0, 0, 'C');
            $this->pdf->Cell(25, 4.5, number_format($decodeJson[$i]['price'], 0, '.'), 0, 0, 'C');
            $this->pdf->Cell(25, 4.5, "", 0, 1, 'C');
            $total += $decodeJson[$i]['price'];
            $i++;
        }
        $this->pdf->Cell(0, 4.5, str_repeat('-', 25), 0, 0, 'C');
    }


    public function receipt()
    {
        $this->pdf->AddPage();
        $this->pdf->SetFont('Helvetica', '', 11);
        $this->header(env('APP_NAME'));
        $this->body();
        $this->pdf->Output();
        exit;
    }
}
