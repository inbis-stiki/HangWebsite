<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportTransaction
{
    protected $dataTransaksiHarian = array(
        [
            "DATE_TRANS" => "2 SEPTEMBER 2022",
            "NAME_USER" => "Zidan",
            "ROLE_USER" => "JATIM 1",
            "AREA_TRANS" => "MALANG 1",
            "TYPE" => "SPREADING",
            "LOKASI" => "Blimbing",
            "UST" => 24,
            "USU" => 20,
            "USP" => 15,
            "USI" => 20,
            "USTR" => 14,
            "USB" => 22,
            "USK" => 20,
            "USR" => 21,
            "FSU" => 19,
            "FSB" => 20,
            "TOTAL_DISPLAY" => 80,
            "TOTAL_OMSET" => 120000


        ]
    );

    public function styling_default_template($FontSize, $ColorText)
    {
        $styleTitle['font']['bold']                           = true;
        $styleDefault['font']['size']                         = $FontSize;
        $styleDefault['font']['color']['argb']                = $ColorText;
        $styleDefault['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE;
        $styleDefault['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        return $styleDefault;
    }

    public function styling_title_template($ColorFill, $ColorText)
    {
        $styleTitle['font']['bold']                         = true;
        $styleTitle['font']['size']                         = 11;
        $styleTitle['font']['color']['argb']                = $ColorText;
        $styleTitle['fill']['fillType']                     = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
        $styleTitle['fill']['color']['argb']                = $ColorFill;
        $styleTitle['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $styleTitle['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;
        $styleTitle['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;

        return $styleTitle;
    }

    public function styling_content_template($ColorFill, $ColorText)
    {
        $styleContent['font']['size']                         = 10;
        $styleContent['font']['color']['argb']                = $ColorText;
        $styleContent['fill']['fillType']                     = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
        $styleContent['fill']['color']['argb']                = $ColorFill;
        $styleContent['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $styleContent['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
        $styleContent['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        return $styleContent;
    }

    public function generate_transaksi_harian($products, $transDaily, $noTransDaily, $regionalName, $date)
    {
        $colData = ['I', "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB",
            "AC", "AD", "AE"
        ];
        $totProd        = count($products);
        $totDetProd     = [];
        $totAllDisplay  = 0;
        $totAllOmzet    = 0;
        $date    = date_format(date_create($date), 'j F Y');

        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("TRANSAKSI HARIAN");

        $ObjSheet->getColumnDimension('A')->setWidth('18');
        $ObjSheet->getColumnDimension('B')->setWidth('30');
        $ObjSheet->getColumnDimension('C')->setWidth('15');
        $ObjSheet->getColumnDimension('D')->setWidth('18');
        $ObjSheet->getColumnDimension('E')->setWidth('18');

        $ObjSheet->getColumnDimension('F')->setWidth('10');
        $ObjSheet->getColumnDimension('G')->setWidth('10');
        $ObjSheet->getColumnDimension('H')->setWidth('10');

        $ObjSheet->mergeCells('A1:'.$colData[$totProd+2].'2')->setCellValue('A1', "REKAP HARIAN REGIONAL PROMOTION OFFICER")->getStyle('A1:'.$colData[$totProd+2].'2')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('A3:'.$colData[$totProd+2].'4')->setCellValue('A3', "*Uleg dalam satuan Inner dan Pars dalam satuan paket")->getStyle('A3:'.$colData[$totProd+2].'4')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FFFF0000'));

        $ObjSheet->mergeCells('A5:A7')->setCellValue('A5', 'TANGGAL')->getStyle('A5:A7')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));
        $ObjSheet->mergeCells('B5:B7')->setCellValue('B5', 'NAMA')->getStyle('B5:B7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->mergeCells('C5:C7')->setCellValue('C5', 'JABATAN')->getStyle('C5:C7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->mergeCells('D5:D7')->setCellValue('D5', 'AREA')->getStyle('D5:D7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->mergeCells('E5:E7')->setCellValue('E5', 'AKTIVITAS')->getStyle('E5:E7')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('F5:H7')->setCellValue('F5', 'LOKASI')->getStyle('F5:H7')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));

        $ObjSheet->mergeCells($colData[0].'5:'.$colData[$totProd-1].'5')->setCellValue($colData[0].'5', 'ITEM TERJUAL')->getStyle($colData[0].'5:'.$colData[$totProd-1].'5')->applyFromArray($this->styling_title_template('FFFFC000', 'FFFF0000'));

        $colIndex = 0;
        foreach($products as $product) {
            $ObjSheet->getColumnDimension($colData[$colIndex])->setWidth('15');
            $ObjSheet->setCellValue($colData[$colIndex].'6', $product->CODE_PRODUCT)->getStyle($colData[$colIndex]."6")->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
            $ObjSheet->getStyle($colData[$colIndex++].'7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
            $totDetProd[$product->CODE_PRODUCT] = 0;
        }
        
        $ObjSheet->getColumnDimension($colData[$colIndex])->setWidth('20');
        $ObjSheet->mergeCells($colData[$colIndex].'5:'.$colData[$colIndex].'6')->setCellValue($colData[$colIndex].'5', 'TOTAL DISPLAY')->getStyle($colData[$colIndex].'5:'.$colData[$colIndex++].'6')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->getStyle($colData[$colIndex].'7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getColumnDimension($colData[$colIndex])->setWidth('20');
        $ObjSheet->mergeCells($colData[$colIndex].'5:'.$colData[$colIndex].'7')->setCellValue($colData[$colIndex].'5', 'TOTAL OMSET')->getStyle($colData[$colIndex].'5:'.$colData[$colIndex++].'7')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->getColumnDimension($colData[$colIndex])->setWidth('20');
        $ObjSheet->mergeCells($colData[$colIndex].'5:'.$colData[$colIndex].'7')->setCellValue($colData[$colIndex].'5', 'KETERANGAN')->getStyle($colData[$colIndex].'5:'.$colData[$colIndex++].'7')->applyFromArray($this->styling_title_template('FFBFBFBF', 'FF000000'));

        // $ObjSheet->setAutoFilter('A7:V' . (count($this->dataTransaksiHarian) + 7));

        $rowStart = 8;
        foreach ($transDaily as $trans) {
            $ObjSheet->setCellValue('A' . $rowStart, $date)->getStyle('A' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('B' . $rowStart, $trans->NAME_USER)->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart, $trans->NAME_ROLE)->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart, $trans->AREA_TD)->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart, $trans->NAME_TYPE)->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $detLoc = $trans->DETAIL_LOCATION != NULL ? ", ".$trans->DETAIL_LOCATION : "";
            $ObjSheet->setCellValue('F' . $rowStart, $trans->DISTRICT.$detLoc)->mergeCells('F' . $rowStart . ':H' . $rowStart)->getStyle('F' . $rowStart . ':H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $colIndex   = 0;
            $totDisplay = 0;
            foreach ($products as $product) {
                $arrTrans = json_decode(json_encode($trans), true);
                $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $arrTrans[$product->CODE_PRODUCT])->getStyle($colData[$colIndex] . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                
                $totDisplay += $arrTrans[$product->CODE_PRODUCT];
                $totDetProd[$product->CODE_PRODUCT] += $arrTrans[$product->CODE_PRODUCT];
                $colIndex++;
            }
            $totAllDisplay += $totDisplay;

            $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $totDisplay)->getStyle($colData[$colIndex++] . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $trans->TOTAL_TD)->getStyle($colData[$colIndex++] . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $ket = $trans->ISFINISHED_TD == "0" ? "TIDAK TUTUP FAKTUR" : "";
            $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $ket)->getStyle($colData[$colIndex] . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $rowStart++;
            $totAllOmzet += $trans->TOTAL_TD;http://127.0.0.1:8000/cronjob/generate-transaction
        }

        // SUMMARY TOTAL
        $ObjSheet->mergeCells('A'.$rowStart.':H'.$rowStart)->setCellValue("A" . $rowStart, "TOTAL")->getStyle('A'.$rowStart.':H'.$rowStart)->applyFromArray($this->styling_content_template('FF92D050', '00000000'))->getAlignment()->setWrapText(true);
        $colIndex   = 0;
        foreach ($totDetProd as $tot) {
            $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $tot)->getStyle($colData[$colIndex++] . $rowStart)->applyFromArray($this->styling_content_template('FF92D050', '00000000'))->getAlignment()->setWrapText(true);
        }
        $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $totAllDisplay)->getStyle($colData[$colIndex++] . $rowStart)->applyFromArray($this->styling_content_template('FF92D050', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, $totAllOmzet)->getStyle($colData[$colIndex++] . $rowStart)->applyFromArray($this->styling_content_template('FF92D050', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue($colData[$colIndex] . $rowStart, "")->getStyle($colData[$colIndex++] . $rowStart)->applyFromArray($this->styling_content_template('FF92D050', '00000000'))->getAlignment()->setWrapText(true);
        
        // SHEET 2 USER TIDAK TRANSAKSI
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $ObjSheet2 = $spreadsheet->getActiveSheet()->setTitle("USER TIDAK TRANSAKSI");

        $ObjSheet2->getColumnDimension('A')->setWidth('18');
        $ObjSheet2->getColumnDimension('B')->setWidth('30');
        $ObjSheet2->getColumnDimension('C')->setWidth('15');
        $ObjSheet2->getColumnDimension('D')->setWidth('18');

        $ObjSheet2->mergeCells('A1:D2')->setCellValue('A1', "REKAP USER YANG TIDAK MELAKUKAN TRANSAKSI")->getStyle('A1:D2')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet2->mergeCells('A3:D4')->setCellValue('A3', "")->getStyle('A3:D4')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FFFF0000'));

        $ObjSheet2->mergeCells('A5:A7')->setCellValue('A5', 'TANGGAL')->getStyle('A5:A7')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));
        $ObjSheet2->mergeCells('B5:B7')->setCellValue('B5', 'NAMA')->getStyle('B5:B7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet2->mergeCells('C5:C7')->setCellValue('C5', 'JABATAN')->getStyle('C5:C7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet2->mergeCells('D5:D7')->setCellValue('D5', 'AREA')->getStyle('D5:D7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));

        $rowStart = 8;
        if($noTransDaily != null){
            foreach ($noTransDaily as $noTrans) {
                $ObjSheet2->setCellValue('A' . $rowStart, $date)->getStyle('A' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet2->setCellValue('B' . $rowStart, $noTrans->NAME_USER)->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet2->setCellValue('C' . $rowStart, $noTrans->NAME_ROLE)->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet2->setCellValue('D' . $rowStart, $noTrans->NAME_AREA)->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
    
                $rowStart++;
            }
        }


        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'TRANSAKSI HARIAN - '.$regionalName.' - APO SPG - ' . date_format(date_create(date("Y-m-d")), 'j F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
