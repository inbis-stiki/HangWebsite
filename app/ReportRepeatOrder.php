<?php
namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportRepeatOrder {
    public function gen_ro_rpo($rOs, $updated_at){
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("REPEAT ORDER");

        $ObjSheet->getColumnDimension('B')->setWidth(15);
        $ObjSheet->getColumnDimension('C')->setWidth(10);
        $ObjSheet->getColumnDimension('E')->setWidth(10);
        $ObjSheet->getColumnDimension('E')->setWidth(10);
        $ObjSheet->getColumnDimension('E')->setWidth(10);

        $rowIsi = 0;
        $rowHeader = 0;
        foreach ($rOs as $regional => $areas) {
            // PEDAGANG SAYUR {{TAHUN}}
            // HEADER
            $rowHeader += 2;
            $ObjSheet->mergeCells('B2:P2')->setCellValue('B2', $regional)->getStyle('B2:P2')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('B3:E3')->setCellValue('B3', 'PEDAGANG SAYUR 2022')->getStyle('B3:E3')->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('B4:B5')->setCellValue('B4', 'AREA')->getStyle('B4:B5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('C4:C5')->setCellValue('C4', 'TOTAL PS')->getStyle('C4:C5')->applyFromArray($this->styling_title_template('0000FF', 'FFFFFF'));
            $ObjSheet->mergeCells('D4:D5')->setCellValue('D4', 'TOTAL RO PS 2022')->getStyle('D4:D5')->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('E4:E5')->setCellValue('E4', '% RO VS TOTAL PS')->getStyle('E4:E5')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            $ObjSheet->mergeCells('F3:F5')->setCellValue('F3', '% RO 2022 VS RO 2021')->getStyle('F3:F5')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            
            $ObjSheet->mergeCells('H3:P3')->setCellValue('H3', 'DETAIL RO PS 2022')->getStyle('H3:P3')->applyFromArray($this->styling_title_template('00FF00', '000000'));        
            $ObjSheet->mergeCells('H4:H5')->setCellValue('H4', 'TOTAL RO')->getStyle('H4:H5')->applyFromArray($this->styling_title_template('FFFF00', '000000'));        
            $ObjSheet->mergeCells('I4:L4')->setCellValue('I4', 'RUTINITAS RO PEDAGANG SAYUR')->getStyle('I4:L4')->applyFromArray($this->styling_title_template('FFC000', '000000'));        
            $ObjSheet->setCellValue('I5', '2-3x')->getStyle('I5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J5', '4-5x')->getStyle('J5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K5', '6-10x')->getStyle('K5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L5', '11x UP')->getStyle('L5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('M4:P4')->setCellValue('M4', '% RUTINITAS RO PEDAGANG SAYUR')->getStyle('M4:P4')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));        
            $ObjSheet->setCellValue('M5', '2-3x')->getStyle('M5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N5', '4-5x')->getStyle('N5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('O5', '6-10x')->getStyle('O5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('P5', '11x UP')->getStyle('P5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
    
            // ISI KONTEN
            $rowIsi += 6;
            $subrow = 0;
            foreach ($areas as $area => $detRO){  
                $detRO = (array)$detRO;

                $ObjSheet->setCellValue('B'.$rowIsi, $area)->getStyle('B'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C'.$rowIsi, '??')->getStyle('C'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D'.$rowIsi, "??")->getStyle('D'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E'.$rowIsi, '50%')->getStyle('E'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F'.$rowIsi, '100%')->getStyle('F'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                
                $ObjSheet->setCellValue('H'.$rowIsi, "??")->getStyle('H'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I'.$rowIsi, $detRO['PS_2-3'])->getStyle('I'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J'.$rowIsi, $detRO['PS_4-5'])->getStyle('J'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K'.$rowIsi, $detRO['PS_6-10'])->getStyle('K'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L'.$rowIsi, $detRO['PS_>11'])->getStyle('L'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M'.$rowIsi, '100%')->getStyle('M'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N'.$rowIsi, '100%')->getStyle('N'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O'.$rowIsi, '100%')->getStyle('O'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P'.$rowIsi, '100%')->getStyle('P'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
                $rowIsi++;
                $subrow = $rowIsi;
            }
            $ObjSheet->setCellValue('B'.$subrow, $regional)->getStyle('B'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C'.$subrow, '11000')->getStyle('C'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D'.$subrow, '1700')->getStyle('D'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E'.$subrow, '50%')->getStyle('E'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F'.$subrow, '100%')->getStyle('F'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            $ObjSheet->setCellValue('H'.$subrow, '1000')->getStyle('H'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I'.$subrow, '900')->getStyle('I'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J'.$subrow, '280')->getStyle('J'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K'.$subrow, '254')->getStyle('K'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L'.$subrow, '250')->getStyle('L'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M'.$subrow, '100%')->getStyle('M'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N'.$subrow, '100%')->getStyle('N'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O'.$subrow, '100%')->getStyle('O'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P'.$subrow, '100%')->getStyle('P'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            $ObjSheet->mergeCells('R3:R5')->setCellValue('R3', 'JUMLAH PEDAGANG SAYUR RO PEDAGANG SAYUR/APO')->getStyle('R3:R5')->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('S3:S5')->setCellValue('S3', 'JUMLAH APO')->getStyle('S3:S5')->applyFromArray($this->styling_title_template('0000FF', '000000'));
            $ObjSheet->mergeCells('T3:T5')->setCellValue('T3', 'RT2 PEDAGANG SAYUR RO/APO')->getStyle('T3:T5')->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            //ISI
            $ObjSheet->setCellValue('R6', '1700')->getStyle('R6')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S6', '15')->getStyle('S6')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T6', '120')->getStyle('T6')->applyFromArray($this->styling_default_template('11', '000000'));
    
    
            // RETAIL {{TAHUN}}
            // HEADER
            $ObjSheet->mergeCells('B24:E24')->setCellValue('B24', 'RETAIL 2022')->getStyle('B24:E24')->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('B25:B26')->setCellValue('B25', 'AREA')->getStyle('B25:B26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('C25:C26')->setCellValue('C25', 'TOTAL PS')->getStyle('C25:C26')->applyFromArray($this->styling_title_template('0000FF', 'FFFFFF'));
            $ObjSheet->mergeCells('D25:D26')->setCellValue('D25', 'TOTAL RO PS 2022')->getStyle('D25:D26')->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('E25:E26')->setCellValue('E25', '% RO VS TOTAL PS')->getStyle('E25:E26')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            $ObjSheet->mergeCells('F24:F26')->setCellValue('F24', '% RO 2022 VS RO 2021')->getStyle('F24:F26')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            
            $ObjSheet->mergeCells('H24:P24')->setCellValue('H24', 'DETAIL RO RETAIL 2022')->getStyle('H24:P24')->applyFromArray($this->styling_title_template('00FF00', '000000'));        
            $ObjSheet->mergeCells('H25:H26')->setCellValue('H25', 'TOTAL RO')->getStyle('H25:H26')->applyFromArray($this->styling_title_template('FFFF00', '000000'));        
            $ObjSheet->mergeCells('I25:L25')->setCellValue('I25', 'RUTINITAS RO PEDAGANG SAYUR')->getStyle('I25:L25')->applyFromArray($this->styling_title_template('FFC000', '000000'));        
            $ObjSheet->setCellValue('I26', '2-3x')->getStyle('I26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J26', '4-5x')->getStyle('J26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K26', '6-10x')->getStyle('K26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L26', '11x UP')->getStyle('L26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('M25:P25')->setCellValue('M25', '% RUTINITAS RO PEDAGANG SAYUR')->getStyle('M25:P25')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));        
            $ObjSheet->setCellValue('M26', '2-3x')->getStyle('M26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N26', '4-5x')->getStyle('N26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('O26', '6-10x')->getStyle('O26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('P26', '11x UP')->getStyle('P26')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
    
            // ISI KONTEN
            $rowIsi += 21;
            $subrow = 0;
            foreach ($areas as $area => $detRO){  
                $detRO = (array)$detRO;

                $ObjSheet->setCellValue('B'.$rowIsi, $area)->getStyle('B'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C'.$rowIsi, '??')->getStyle('C'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D'.$rowIsi, '??')->getStyle('D'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E'.$rowIsi, '50%')->getStyle('E'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F'.$rowIsi, '100%')->getStyle('F'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                
                $ObjSheet->setCellValue('H'.$rowIsi, '50')->getStyle('H'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I'.$rowIsi, $detRO['Retail_2-3'])->getStyle('I'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J'.$rowIsi, $detRO['Retail_4-5'])->getStyle('J'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K'.$rowIsi, $detRO['Retail_6-10'])->getStyle('K'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L'.$rowIsi, $detRO['Retail_>11'])->getStyle('L'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M'.$rowIsi, '100%')->getStyle('M'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N'.$rowIsi, '100%')->getStyle('N'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O'.$rowIsi, '100%')->getStyle('O'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P'.$rowIsi, '100%')->getStyle('P'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
                $rowIsi++;
                $subrow = $rowIsi;
            }
            $ObjSheet->setCellValue('B'.$subrow, $regional)->getStyle('B'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C'.$subrow, '11000')->getStyle('C'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D'.$subrow, '1700')->getStyle('D'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E'.$subrow, '50%')->getStyle('E'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F'.$subrow, '100%')->getStyle('F'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            $ObjSheet->setCellValue('H'.$subrow, '1000')->getStyle('H'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I'.$subrow, '900')->getStyle('I'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J'.$subrow, '280')->getStyle('J'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K'.$subrow, '254')->getStyle('K'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L'.$subrow, '250')->getStyle('L'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M'.$subrow, '100%')->getStyle('M'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N'.$subrow, '100%')->getStyle('N'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O'.$subrow, '100%')->getStyle('O'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P'.$subrow, '100%')->getStyle('P'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            $ObjSheet->mergeCells('R24:R26')->setCellValue('R24', 'JUMLAH RETAIL RO PEDAGANG SAYUR/APO')->getStyle('R24:R26')->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('S24:S26')->setCellValue('S24', 'JUMLAH APO')->getStyle('S24:S26')->applyFromArray($this->styling_title_template('0000FF', '000000'));
            $ObjSheet->mergeCells('T24:T26')->setCellValue('T24', 'RT2 RETAIL RO / APO')->getStyle('T24:T26')->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            //ISI
            $ObjSheet->setCellValue('R27', '1700')->getStyle('R27')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S27', '15')->getStyle('S27')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T27', '120')->getStyle('T27')->applyFromArray($this->styling_default_template('11', '000000'));
    
    
            // LOSS {{TAHUN}}
            // HEADER
            $ObjSheet->mergeCells('B45:E45')->setCellValue('B45', 'LOSS 2022')->getStyle('B45:E46')->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('B46:B47')->setCellValue('B46', 'AREA')->getStyle('B46:B47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('C46:C47')->setCellValue('C46', 'TOTAL PS')->getStyle('C46:C47')->applyFromArray($this->styling_title_template('0000FF', 'FFFFFF'));
            $ObjSheet->mergeCells('D46:D47')->setCellValue('D46', 'TOTAL RO PS 2022')->getStyle('D46:D47')->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('E46:E47')->setCellValue('E46', '% RO VS TOTAL PS')->getStyle('E46:E47')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            $ObjSheet->mergeCells('F45:F47')->setCellValue('F45', '% RO 2022 VS RO 2021')->getStyle('F45:F47')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            
            $ObjSheet->mergeCells('H45:P45')->setCellValue('H45', 'DETAIL RO LOSS 2022')->getStyle('H45:P45')->applyFromArray($this->styling_title_template('00FF00', '000000'));        
            $ObjSheet->mergeCells('H46:H47')->setCellValue('H46', 'TOTAL RO')->getStyle('H46:H47')->applyFromArray($this->styling_title_template('FFFF00', '000000'));        
            $ObjSheet->mergeCells('I46:L47')->setCellValue('I46', 'RUTINITAS RO PEDAGANG SAYUR')->getStyle('I46:L47')->applyFromArray($this->styling_title_template('FFC000', '000000'));        
            $ObjSheet->setCellValue('I47', '2-3x')->getStyle('I47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J47', '4-5x')->getStyle('J47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K47', '6-10x')->getStyle('K47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L47', '11x UP')->getStyle('L47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('M46:P46')->setCellValue('M46', '% RUTINITAS RO PEDAGANG SAYUR')->getStyle('M46:P46')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));        
            $ObjSheet->setCellValue('M47', '2-3x')->getStyle('M47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N47', '4-5x')->getStyle('N47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('O47', '6-10x')->getStyle('O47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('P47', '11x UP')->getStyle('P47')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
    
            // ISI KONTEN
            $rowIsi += 21;
            $subrow = 0;
            foreach ($areas as $area => $detRO){
                $detRO = (array)$detRO;

                $ObjSheet->setCellValue('B'.$rowIsi, $area)->getStyle('B'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C'.$rowIsi, '100')->getStyle('C'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D'.$rowIsi, '50')->getStyle('D'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E'.$rowIsi, '50%')->getStyle('E'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F'.$rowIsi, '100%')->getStyle('F'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                
                $ObjSheet->setCellValue('H'.$rowIsi, '50')->getStyle('H'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I'.$rowIsi, $detRO['Loss_2-3'])->getStyle('I'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J'.$rowIsi, $detRO['Loss_4-5'])->getStyle('J'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K'.$rowIsi, $detRO['Loss_6-10'])->getStyle('K'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L'.$rowIsi, $detRO['Loss_>11'])->getStyle('L'.$rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M'.$rowIsi, '100%')->getStyle('M'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N'.$rowIsi, '100%')->getStyle('N'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O'.$rowIsi, '100%')->getStyle('O'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P'.$rowIsi, '100%')->getStyle('P'.$rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
                $rowIsi++;
                $subrow = $rowIsi;
            }
            $ObjSheet->setCellValue('B'.$subrow, $regional)->getStyle('B'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C'.$subrow, '11000')->getStyle('C'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D'.$subrow, '1700')->getStyle('D'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E'.$subrow, '50%')->getStyle('E'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F'.$subrow, '100%')->getStyle('F'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            $ObjSheet->setCellValue('H'.$subrow, '1000')->getStyle('H'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I'.$subrow, '900')->getStyle('I'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J'.$subrow, '280')->getStyle('J'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K'.$subrow, '254')->getStyle('K'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L'.$subrow, '250')->getStyle('L'.$subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M'.$subrow, '100%')->getStyle('M'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N'.$subrow, '100%')->getStyle('N'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O'.$subrow, '100%')->getStyle('O'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P'.$subrow, '100%')->getStyle('P'.$subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            $ObjSheet->mergeCells('R45:R47')->setCellValue('R45', 'JUMLAH LOSS/APO')->getStyle('R45:R47')->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('S45:S47')->setCellValue('S45', 'JUMLAH APO')->getStyle('S45:S47')->applyFromArray($this->styling_title_template('0000FF', '000000'));
            $ObjSheet->mergeCells('T45:T47')->setCellValue('T45', 'RT2 LOSS/APO')->getStyle('T45:T47')->applyFromArray($this->styling_title_template('00FF00', '000000'));
    
            //ISI
            $ObjSheet->setCellValue('R48', '1700')->getStyle('R48')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S48', '15')->getStyle('S48')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T48', '120')->getStyle('T48')->applyFromArray($this->styling_default_template('11', '000000'));


        }

        $fileName = 'Pedagang Sayur APO';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function styling_default_template($FontSize, $ColorText)
    {
        $styleTitle['font']['bold']                           = true;
        $styleDefault['font']['size']                         = $FontSize;
        $styleDefault['font']['color']['argb']                = $ColorText;
        $styleDefault['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $styleDefault['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;

        return $styleDefault;
    }

    public function styling_default_template_noborder($FontSize, $ColorText)
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
}