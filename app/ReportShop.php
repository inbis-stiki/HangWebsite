<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportShop
{   
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

    public function dummyColumn(){
        return ["A", "B", "C", "D", "E", "F", "G"];
    }

    public function generate($param)
    {
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("GENERAL");

        $ObjSheet->getColumnDimension('B')->setWidth('5');
        $ObjSheet->getColumnDimension('C')->setWidth('25');
        $ObjSheet->getColumnDimension('D')->setWidth('20');

        $ObjSheet->getColumnDimension('E')->setWidth('20');
        $ObjSheet->getColumnDimension('F')->setWidth('20');
        $ObjSheet->getColumnDimension('G')->setWidth('20');
        $ObjSheet->getColumnDimension('H')->setWidth('20');

        $ObjSheet->mergeCells('B2:H2')->setCellValue('B2', $param['location']->NAME_LOCATION)->getStyle('B2:H2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('B3:B5')->setCellValue('B3', "NO")->getStyle('B3:B5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C3:C5')->setCellValue('C3', "Area")->getStyle('C3:C5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        
        
        $ObjSheet->mergeCells('D3:D5')->setCellValue('D3', "Pedagang Sayur")->getStyle('D3:D5')->applyFromArray($this->styling_title_template('FFC000', 'FF000000'));
        $ObjSheet->mergeCells('E3:E5')->setCellValue('E3', "Retail")->getStyle('E3:E5')->applyFromArray($this->styling_title_template('E2B602', 'FF000000'));
        $ObjSheet->mergeCells('F3:F5')->setCellValue('F3', "Loss")->getStyle('F3:F5')->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
        $ObjSheet->mergeCells('G3:G5')->setCellValue('G3', "Permanen")->getStyle('G3:G5')->applyFromArray($this->styling_title_template('948A54', 'FF000000'));
        $ObjSheet->mergeCells('H3:H5')->setCellValue('H3', "Total Toko")->getStyle('H3:H5')->applyFromArray($this->styling_title_template('F79646', 'FF000000'));

        $noArea         = 1;
        $rowSheetArea   = 6;
        $totPSArea      = 0;
        $totRetailArea  = 0;
        $totLossArea    = 0;
        $totPermanenArea= 0;
        $totAllTokoArea = 0;
        $countSheet = 1;
        foreach ($param['areas'] as $area) {
            // SHEET GENERAL 
            $totTokoArea    = $area->TOT_PS+$area->TOT_RETAIL+$area->TOT_LOSS+$area->TOT_PERMANEN;
            
            $ObjSheet->mergeCells('B'.$rowSheetArea.':B'.($rowSheetArea+1))->setCellValue('B'.$rowSheetArea.'', $noArea++)->getStyle('B'.$rowSheetArea.':B'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            $ObjSheet->mergeCells('C'.$rowSheetArea.':C'.($rowSheetArea+1))->setCellValue('C'.$rowSheetArea.'', $area->NAME_AREA)->getStyle('C'.$rowSheetArea.':C'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            $ObjSheet->mergeCells('D'.$rowSheetArea.':D'.($rowSheetArea+1))->setCellValue('D'.$rowSheetArea.'', $area->TOT_PS )->getStyle('D'.$rowSheetArea.':D'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            $ObjSheet->mergeCells('E'.$rowSheetArea.':E'.($rowSheetArea+1))->setCellValue('E'.$rowSheetArea.'', $area->TOT_RETAIL)->getStyle('E'.$rowSheetArea.':E'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            $ObjSheet->mergeCells('F'.$rowSheetArea.':F'.($rowSheetArea+1))->setCellValue('F'.$rowSheetArea.'', $area->TOT_LOSS)->getStyle('F'.$rowSheetArea.':F'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            $ObjSheet->mergeCells('G'.$rowSheetArea.':G'.($rowSheetArea+1))->setCellValue('G'.$rowSheetArea.'', $area->TOT_PERMANEN)->getStyle('G'.$rowSheetArea.':G'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            $ObjSheet->mergeCells('H'.$rowSheetArea.':H'.($rowSheetArea+1))->setCellValue('H'.$rowSheetArea.'', $totTokoArea)->getStyle('H'.$rowSheetArea.':H'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
            
            $rowSheetArea       +=2;
            $totPSArea          += $area->TOT_PS;
            $totRetailArea      += $area->TOT_RETAIL;
            $totLossArea        += $area->TOT_LOSS;
            $totPermanenArea    += $area->TOT_PERMANEN;
            $totAllTokoArea     += $totTokoArea;

            // SHEET PER AREA (DISTRICT)
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($countSheet++);
            $ObjSheet2 = $spreadsheet->getActiveSheet()->setTitle($area->NAME_AREA);

            $ObjSheet2->getColumnDimension('B')->setWidth('5');
            $ObjSheet2->getColumnDimension('C')->setWidth('25');
            $ObjSheet2->getColumnDimension('D')->setWidth('20');

            $ObjSheet2->getColumnDimension('E')->setWidth('20');
            $ObjSheet2->getColumnDimension('F')->setWidth('20');
            $ObjSheet2->getColumnDimension('G')->setWidth('20');
            $ObjSheet2->getColumnDimension('H')->setWidth('20');
            $ObjSheet2->getColumnDimension('I')->setWidth('10');
            $ObjSheet2->getColumnDimension('J')->setWidth('10');
            $ObjSheet2->getColumnDimension('K')->setWidth('10');
            $ObjSheet2->getColumnDimension('L')->setWidth('10');
            $ObjSheet2->getColumnDimension('M')->setWidth('10');
            $ObjSheet2->getColumnDimension('N')->setWidth('10');
            $ObjSheet2->getColumnDimension('O')->setWidth('10');
            $ObjSheet2->getColumnDimension('P')->setWidth('10');

            $ObjSheet2->mergeCells('B2:H2')->setCellValue('B2', "AREA ".$area->NAME_AREA)->getStyle('B2:H2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet2->mergeCells('B3:B5')->setCellValue('B3', "NO")->getStyle('B3:B5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
            $ObjSheet2->mergeCells('C3:C5')->setCellValue('C3', "Kecamatan")->getStyle('C3:C5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
            
            
            $ObjSheet2->mergeCells('D3:D5')->setCellValue('D3', "Pedagang Sayur")->getStyle('D3:D5')->applyFromArray($this->styling_title_template('FFC000', 'FF000000'));
            $ObjSheet2->mergeCells('E3:E5')->setCellValue('E3', "Retail")->getStyle('E3:E5')->applyFromArray($this->styling_title_template('E2B602', 'FF000000'));
            $ObjSheet2->mergeCells('F3:F5')->setCellValue('F3', "Loss")->getStyle('F3:F5')->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet2->mergeCells('G3:G5')->setCellValue('G3', "Permanen")->getStyle('G3:G5')->applyFromArray($this->styling_title_template('948A54', 'FF000000'));
            $ObjSheet2->mergeCells('H3:H5')->setCellValue('H3', "Total Toko")->getStyle('H3:H5')->applyFromArray($this->styling_title_template('F79646', 'FF000000'));
            
            $totTypeShops = Shop::getTotTypePerDistrict($area->ID_AREA);

            $no         = 1;
            $rowSheet   = 6;
            $totPS      = 0;
            $totRetail  = 0;
            $totLoss    = 0;
            $totPermanen= 0;
            $totAllToko = 0;
            foreach ($totTypeShops as $totTypeShop) {
                $totToko = $totTypeShop->TOT_PS+$totTypeShop->TOT_RETAIL+$totTypeShop->TOT_LOSS+$totTypeShop->TOT_PERMANEN;
                $ObjSheet2->mergeCells('B'.$rowSheet.':B'.($rowSheet+1))->setCellValue('B'.$rowSheet.'', $no++)->getStyle('B'.$rowSheet.':B'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                $ObjSheet2->mergeCells('C'.$rowSheet.':C'.($rowSheet+1))->setCellValue('C'.$rowSheet.'', $totTypeShop->NAME_DISTRICT)->getStyle('C'.$rowSheet.':C'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                $ObjSheet2->mergeCells('D'.$rowSheet.':D'.($rowSheet+1))->setCellValue('D'.$rowSheet.'', $totTypeShop->TOT_PS )->getStyle('D'.$rowSheet.':D'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                $ObjSheet2->mergeCells('E'.$rowSheet.':E'.($rowSheet+1))->setCellValue('E'.$rowSheet.'', $totTypeShop->TOT_RETAIL)->getStyle('E'.$rowSheet.':E'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                $ObjSheet2->mergeCells('F'.$rowSheet.':F'.($rowSheet+1))->setCellValue('F'.$rowSheet.'', $totTypeShop->TOT_LOSS)->getStyle('F'.$rowSheet.':F'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                $ObjSheet2->mergeCells('G'.$rowSheet.':G'.($rowSheet+1))->setCellValue('G'.$rowSheet.'', $totTypeShop->TOT_PERMANEN)->getStyle('G'.$rowSheet.':G'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                $ObjSheet2->mergeCells('H'.$rowSheet.':H'.($rowSheet+1))->setCellValue('H'.$rowSheet.'', $totToko)->getStyle('H'.$rowSheet.':H'.($rowSheet+1))->applyFromArray($this->styling_title_template('FFFFFF', 'FF000000'));
                
                $rowSheet       +=2;
                $totPS          += $totTypeShop->TOT_PS;
                $totRetail      += $totTypeShop->TOT_RETAIL;
                $totLoss        += $totTypeShop->TOT_LOSS;
                $totPermanen    += $totTypeShop->TOT_PERMANEN;
                $totAllToko     += $totToko;
            }
            $ObjSheet2->mergeCells('B'.$rowSheet.':C'.($rowSheet+1))->setCellValue('B'.$rowSheet.'', "Total")->getStyle('B'.$rowSheet.':C'.($rowSheet+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet2->mergeCells('D'.$rowSheet.':D'.($rowSheet+1))->setCellValue('D'.$rowSheet.'', $totPS )->getStyle('D'.$rowSheet.':D'.($rowSheet+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet2->mergeCells('E'.$rowSheet.':E'.($rowSheet+1))->setCellValue('E'.$rowSheet.'', $totRetail)->getStyle('E'.$rowSheet.':E'.($rowSheet+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet2->mergeCells('F'.$rowSheet.':F'.($rowSheet+1))->setCellValue('F'.$rowSheet.'', $totLoss)->getStyle('F'.$rowSheet.':F'.($rowSheet+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet2->mergeCells('G'.$rowSheet.':G'.($rowSheet+1))->setCellValue('G'.$rowSheet.'', $totPermanen)->getStyle('G'.$rowSheet.':G'.($rowSheet+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet2->mergeCells('H'.$rowSheet.':H'.($rowSheet+1))->setCellValue('H'.$rowSheet.'', $totAllToko)->getStyle('H'.$rowSheet.':H'.($rowSheet+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
        }
            $ObjSheet->mergeCells('B'.$rowSheetArea.':C'.($rowSheetArea+1))->setCellValue('B'.$rowSheetArea.'', "Total")->getStyle('B'.$rowSheetArea.':C'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet->mergeCells('D'.$rowSheetArea.':D'.($rowSheetArea+1))->setCellValue('D'.$rowSheetArea.'', $totPSArea )->getStyle('D'.$rowSheetArea.':D'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet->mergeCells('E'.$rowSheetArea.':E'.($rowSheetArea+1))->setCellValue('E'.$rowSheetArea.'', $totRetailArea)->getStyle('E'.$rowSheetArea.':E'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet->mergeCells('F'.$rowSheetArea.':F'.($rowSheetArea+1))->setCellValue('F'.$rowSheetArea.'', $totLossArea)->getStyle('F'.$rowSheetArea.':F'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet->mergeCells('G'.$rowSheetArea.':G'.($rowSheetArea+1))->setCellValue('G'.$rowSheetArea.'', $totPermanenArea)->getStyle('G'.$rowSheetArea.':G'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));
            $ObjSheet->mergeCells('H'.$rowSheetArea.':H'.($rowSheetArea+1))->setCellValue('H'.$rowSheetArea.'', $totAllTokoArea)->getStyle('H'.$rowSheetArea.':H'.($rowSheetArea+1))->applyFromArray($this->styling_title_template('00B050', 'FF000000'));

        $fileName = 'Report Toko - '.$param['location']->NAME_LOCATION."_".date('j F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
