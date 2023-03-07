<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportRepeatOrder
{
    public function gen_ro_rpo($rOs, $updated_at)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        foreach ($rOs as $regional => $areas) {
            $rowIsi = 0;
            $rowHeader = 0;
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($countSheet++);
            $ObjSheet = $spreadsheet->getActiveSheet()->setTitle($regional);

            $ObjSheet->getColumnDimension('B')->setWidth('25');
            $ObjSheet->getColumnDimension('G')->setWidth('2');
            $ObjSheet->getColumnDimension('Q')->setWidth('10');
            $ObjSheet->getColumnDimension('R')->setWidth('30');
            $ObjSheet->getColumnDimension('S')->setWidth('20');
            $ObjSheet->getColumnDimension('T')->setWidth('30');

            // PEDAGANG SAYUR {{TAHUN}}
            // HEADER
            $rowHeader += 2;
            $ObjSheet->mergeCells('B2:P2')->setCellValue('B2', $regional)->getStyle('B2:P2')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('B3:E3')->setCellValue('B3', 'PEDAGANG SAYUR 2022')->getStyle('B3:E3')->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('B4:B5')->setCellValue('B4', 'AREA')->getStyle('B4:B5')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('C4:C5')->setCellValue('C4', 'TOTAL PS')->getStyle('C4:C5')->applyFromArray($this->styling_title_template('0000FF', 'FFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('D4:D5')->setCellValue('D4', 'TOTAL RO PS 2022')->getStyle('D4:D5')->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('E4:E5')->setCellValue('E4', '% RO VS TOTAL PS')->getStyle('E4:E5')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('F3:F5')->setCellValue('F3', '% RO 2022 VS RO 2021')->getStyle('F3:F5')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'))->getAlignment()->setWrapText(true);

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


            $ObjSheet->mergeCells('R3:R5')->setCellValue('R3', 'JUMLAH PEDAGANG SAYUR RO PEDAGANG SAYUR/APO')->getStyle('R3:R5')->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('S3:S5')->setCellValue('S3', 'JUMLAH APO')->getStyle('S3:S5')->applyFromArray($this->styling_title_template('0000FF', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('T3:T5')->setCellValue('T3', 'RT2 PEDAGANG SAYUR RO/APO')->getStyle('T3:T5')->applyFromArray($this->styling_title_template('00FF00', '000000'))->getAlignment()->setWrapText(true);

            //ISI
            $ObjSheet->setCellValue('R6', '1700')->getStyle('R6')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S6', '15')->getStyle('S6')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T6', '120')->getStyle('T6')->applyFromArray($this->styling_default_template('11', '000000'));

            // ISI KONTEN
            $rowIsi += 6;
            $subrow = 0;
            foreach ($rOs[$regional] as $area => $DetArea) {
                // $detRO = (array)$detRO;

                $ObjSheet->setCellValue('B' . $rowIsi, $area)->getStyle('B' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi, '??')->getStyle('C' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi, "??")->getStyle('D' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi, '50%')->getStyle('E' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi, '100%')->getStyle('F' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $ObjSheet->setCellValue('H' . $rowIsi, "??")->getStyle('H' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I' . $rowIsi, "??")->getStyle('I' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi, "??")->getStyle('J' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi, "??")->getStyle('K' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi, "??")->getStyle('L' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi, '100%')->getStyle('M' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi, '100%')->getStyle('N' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O' . $rowIsi, '100%')->getStyle('O' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P' . $rowIsi, '100%')->getStyle('P' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $rowIsi++;
                $subrow = $rowIsi;
            }
            $ObjSheet->setCellValue('B' . $subrow, $regional)->getStyle('B' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . $subrow, '11000')->getStyle('C' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . $subrow, '1700')->getStyle('D' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . $subrow, '50%')->getStyle('E' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F' . $subrow, '100%')->getStyle('F' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->setCellValue('H' . $subrow, '1000')->getStyle('H' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I' . $subrow, '900')->getStyle('I' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . $subrow, '280')->getStyle('J' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . $subrow, '254')->getStyle('K' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . $subrow, '250')->getStyle('L' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . $subrow, '100%')->getStyle('M' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N' . $subrow, '100%')->getStyle('N' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O' . $subrow, '100%')->getStyle('O' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P' . $subrow, '100%')->getStyle('P' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            // RETAIL {{TAHUN}}
            // HEADER
            $rowHeader2 = $subrow + 2;
            $ObjSheet->mergeCells('B' . $rowHeader2 . ':P' . $rowHeader2)->setCellValue('B' . $rowHeader2, $regional)->getStyle('B' . $rowHeader2 . ':P' . $rowHeader2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('B' . ($rowHeader2 + 1) . ':E' . ($rowHeader2 + 1))->setCellValue('B' . ($rowHeader2 + 1), 'RETAIL 2022')->getStyle('B' . ($rowHeader2 + 1) . ':E' . ($rowHeader2 + 1))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('B' . ($rowHeader2 + 2) . ':B' . ($rowHeader2 + 3))->setCellValue('B' . ($rowHeader2 + 2), 'AREA')->getStyle('B' . ($rowHeader2 + 2) . ':B' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('C' . ($rowHeader2 + 2) . ':C' . ($rowHeader2 + 3))->setCellValue('C' . ($rowHeader2 + 2) . '', 'TOTAL RETAIL')->getStyle('C' . ($rowHeader2 + 2) . ':C' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('0000FF', 'FFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('D' . ($rowHeader2 + 2) . ':D' . ($rowHeader2 + 3))->setCellValue('D' . ($rowHeader2 + 2) . '', 'TOTAL RO RETAIL 2022')->getStyle('D' . ($rowHeader2 + 2) . ':D' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('E' . ($rowHeader2 + 2) . ':E' . ($rowHeader2 + 3))->setCellValue('E' . ($rowHeader2 + 2) . '', '% RO VS TOTAL RETAIL')->getStyle('E' . ($rowHeader2 + 2) . ':E' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('F' . ($rowHeader2 + 1) . ':F' . ($rowHeader2 + 3))->setCellValue('F' . ($rowHeader2 + 1) . '', '% RO 2022 VS RO 2021')->getStyle('F' . ($rowHeader2 + 1) . ':F' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('H' . ($rowHeader2 + 1) . ':P' . ($rowHeader2 + 1))->setCellValue('H' . ($rowHeader2 + 1), 'DETAIL RO RETAIL 2022')->getStyle('H' . ($rowHeader2 + 1) . ':P' . ($rowHeader2 + 1))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('H' . ($rowHeader2 + 2) . ':H' . ($rowHeader2 + 3))->setCellValue('H' . ($rowHeader2 + 2), 'TOTAL RO')->getStyle('H' . ($rowHeader2 + 2) . ':H' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('I' . ($rowHeader2 + 2) . ':L' . ($rowHeader2 + 2))->setCellValue('I' . ($rowHeader2 + 2), 'RUTINITAS RO RETAIL')->getStyle('I' . ($rowHeader2 + 2) . ':L' . ($rowHeader2 + 2))->applyFromArray($this->styling_title_template('FFC000', '000000'));
            $ObjSheet->setCellValue('I' . ($rowHeader2 + 3), '2-3x')->getStyle('I' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . ($rowHeader2 + 3), '4-5x')->getStyle('J' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . ($rowHeader2 + 3), '6-10x')->getStyle('K' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . ($rowHeader2 + 3), '11x UP')->getStyle('L' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('M' . ($rowHeader2 + 2) . ':P' . ($rowHeader2 + 2))->setCellValue('M' . ($rowHeader2 + 2), '% RUTINITAS RO RETAIL')->getStyle('M' . ($rowHeader2 + 2) . ':P' . ($rowHeader2 + 2))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            $ObjSheet->setCellValue('M' . ($rowHeader2 + 3), '2-3x')->getStyle('M' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N' . ($rowHeader2 + 3), '4-5x')->getStyle('N' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('O' . ($rowHeader2 + 3), '6-10x')->getStyle('O' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('P' . ($rowHeader2 + 3), '11x UP')->getStyle('P' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));

            //ISI
            $ObjSheet->mergeCells('R' . ($rowHeader2 + 1) . ':R' . ($rowHeader2 + 3))->setCellValue('R' . ($rowHeader2 + 1), 'JUMLAH RETAIL RO RETAIL/APO')->getStyle('R' . ($rowHeader2 + 1) . ':R' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('S' . ($rowHeader2 + 1) . ':S' . ($rowHeader2 + 3))->setCellValue('S' . ($rowHeader2 + 1), 'JUMLAH APO')->getStyle('S' . ($rowHeader2 + 1) . ':S' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('0000FF', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('T' . ($rowHeader2 + 1) . ':T' . ($rowHeader2 + 3))->setCellValue('T' . ($rowHeader2 + 1), 'RT2 RETAIL RO/APO')->getStyle('T' . ($rowHeader2 + 1) . ':T' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FF00', '000000'))->getAlignment()->setWrapText(true);

            $ObjSheet->setCellValue('R' . ($rowHeader2 + 4), '1700')->getStyle('R' . ($rowHeader2 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S' . ($rowHeader2 + 4), '15')->getStyle('S' . ($rowHeader2 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T' . ($rowHeader2 + 4), '120')->getStyle('T' . ($rowHeader2 + 4))->applyFromArray($this->styling_default_template('11', '000000'));

            $rowIsi2 = ($rowHeader2 + 4);
            $subrow2 = ($rowHeader2 + 4) + 0;
            foreach ($rOs[$regional] as $area => $DetArea) {
                // $detRO = (array)$detRO;

                $ObjSheet->setCellValue('B' . $rowIsi2, $area)->getStyle('B' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi2, '??')->getStyle('C' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi2, "??")->getStyle('D' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi2, '50%')->getStyle('E' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi2, '100%')->getStyle('F' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $ObjSheet->setCellValue('H' . $rowIsi2, "??")->getStyle('H' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I' . $rowIsi2, "??")->getStyle('I' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi2, "??")->getStyle('J' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi2, "??")->getStyle('K' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi2, "??")->getStyle('L' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi2, '100%')->getStyle('M' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi2, '100%')->getStyle('N' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O' . $rowIsi2, '100%')->getStyle('O' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P' . $rowIsi2, '100%')->getStyle('P' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $rowIsi2++;
                $subrow2 = $rowIsi2;
            }
            $ObjSheet->setCellValue('B' . $subrow2, $regional)->getStyle('B' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . $subrow2, '11000')->getStyle('C' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . $subrow2, '1700')->getStyle('D' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . $subrow2, '50%')->getStyle('E' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F' . $subrow2, '100%')->getStyle('F' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->setCellValue('H' . $subrow2, '1000')->getStyle('H' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I' . $subrow2, '900')->getStyle('I' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . $subrow2, '280')->getStyle('J' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . $subrow2, '254')->getStyle('K' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . $subrow2, '250')->getStyle('L' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . $subrow2, '100%')->getStyle('M' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N' . $subrow2, '100%')->getStyle('N' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O' . $subrow2, '100%')->getStyle('O' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P' . $subrow2, '100%')->getStyle('P' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            // LOSS {{TAHUN}}
            // HEADER
            $rowHeader3 = $subrow2 + 2;
            $ObjSheet->mergeCells('B' . $rowHeader3 . ':P' . $rowHeader3)->setCellValue('B' . $rowHeader3, $regional)->getStyle('B' . $rowHeader3 . ':P' . $rowHeader3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('B' . ($rowHeader3 + 1) . ':E' . ($rowHeader3 + 1))->setCellValue('B' . ($rowHeader3 + 1), 'LOSS 2022')->getStyle('B' . ($rowHeader3 + 1) . ':E' . ($rowHeader3 + 1))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('B' . ($rowHeader3 + 2) . ':B' . ($rowHeader3 + 3))->setCellValue('B' . ($rowHeader3 + 2), 'AREA')->getStyle('B' . ($rowHeader3 + 2) . ':B' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('C' . ($rowHeader3 + 2) . ':C' . ($rowHeader3 + 3))->setCellValue('C' . ($rowHeader3 + 2) . '', 'TOTAL LOSS')->getStyle('C' . ($rowHeader3 + 2) . ':C' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('0000FF', 'FFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('D' . ($rowHeader3 + 2) . ':D' . ($rowHeader3 + 3))->setCellValue('D' . ($rowHeader3 + 2) . '', 'TOTAL RO LOSS 2022')->getStyle('D' . ($rowHeader3 + 2) . ':D' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('E' . ($rowHeader3 + 2) . ':E' . ($rowHeader3 + 3))->setCellValue('E' . ($rowHeader3 + 2) . '', '% RO VS TOTAL LOSS')->getStyle('E' . ($rowHeader3 + 2) . ':E' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('F' . ($rowHeader3 + 1) . ':F' . ($rowHeader3 + 3))->setCellValue('F' . ($rowHeader3 + 1) . '', '% RO 2022 VS RO 2021')->getStyle('F' . ($rowHeader3 + 1) . ':F' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('H' . ($rowHeader3 + 1) . ':P' . ($rowHeader3 + 1))->setCellValue('H' . ($rowHeader3 + 1), 'DETAIL RO LOSS 2022')->getStyle('H' . ($rowHeader3 + 1) . ':P' . ($rowHeader3 + 1))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->mergeCells('H' . ($rowHeader3 + 2) . ':H' . ($rowHeader3 + 3))->setCellValue('H' . ($rowHeader3 + 2), 'TOTAL RO')->getStyle('H' . ($rowHeader3 + 2) . ':H' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'));
            $ObjSheet->mergeCells('I' . ($rowHeader3 + 2) . ':L' . ($rowHeader3 + 2))->setCellValue('I' . ($rowHeader3 + 2), 'RUTINITAS RO LOSS')->getStyle('I' . ($rowHeader3 + 2) . ':L' . ($rowHeader3 + 2))->applyFromArray($this->styling_title_template('FFC000', '000000'));
            $ObjSheet->setCellValue('I' . ($rowHeader3 + 3), '2-3x')->getStyle('I' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . ($rowHeader3 + 3), '4-5x')->getStyle('J' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . ($rowHeader3 + 3), '6-10x')->getStyle('K' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . ($rowHeader3 + 3), '11x UP')->getStyle('L' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->mergeCells('M' . ($rowHeader3 + 2) . ':P' . ($rowHeader3 + 2))->setCellValue('M' . ($rowHeader3 + 2), '% RUTINITAS RO LOSS')->getStyle('M' . ($rowHeader3 + 2) . ':P' . ($rowHeader3 + 2))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFF'));
            $ObjSheet->setCellValue('M' . ($rowHeader3 + 3), '2-3x')->getStyle('M' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N' . ($rowHeader3 + 3), '4-5x')->getStyle('N' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('O' . ($rowHeader3 + 3), '6-10x')->getStyle('O' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('P' . ($rowHeader3 + 3), '11x UP')->getStyle('P' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));

            //ISI
            $ObjSheet->mergeCells('R' . ($rowHeader3 + 1) . ':R' . ($rowHeader3 + 3))->setCellValue('R' . ($rowHeader3 + 1), 'JUMLAH LOSS RO LOSS/APO')->getStyle('R' . ($rowHeader3 + 1) . ':R' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('S' . ($rowHeader3 + 1) . ':S' . ($rowHeader3 + 3))->setCellValue('S' . ($rowHeader3 + 1), 'JUMLAH APO')->getStyle('S' . ($rowHeader3 + 1) . ':S' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('0000FF', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('T' . ($rowHeader3 + 1) . ':T' . ($rowHeader3 + 3))->setCellValue('T' . ($rowHeader3 + 1), 'RT2 LOSS RO/APO')->getStyle('T' . ($rowHeader3 + 1) . ':T' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FF00', '000000'))->getAlignment()->setWrapText(true);

            $ObjSheet->setCellValue('R' . ($rowHeader3 + 4), '1700')->getStyle('R' . ($rowHeader3 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S' . ($rowHeader3 + 4), '15')->getStyle('S' . ($rowHeader3 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T' . ($rowHeader3 + 4), '120')->getStyle('T' . ($rowHeader3 + 4))->applyFromArray($this->styling_default_template('11', '000000'));

            $rowIsi3 = ($rowHeader3 + 4);
            $subrow3 = ($rowHeader3 + 4) + 0;
            foreach ($rOs[$regional] as $area => $DetArea) {
                // $detRO = (array)$detRO;

                $ObjSheet->setCellValue('B' . $rowIsi3, $area)->getStyle('B' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi3, '??')->getStyle('C' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi3, "??")->getStyle('D' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi3, '50%')->getStyle('E' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi3, '100%')->getStyle('F' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $ObjSheet->setCellValue('H' . $rowIsi3, "??")->getStyle('H' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I' . $rowIsi3, "??")->getStyle('I' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi3, "??")->getStyle('J' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi3, "??")->getStyle('K' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi3, "??")->getStyle('L' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi3, '100%')->getStyle('M' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi3, '100%')->getStyle('N' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O' . $rowIsi3, '100%')->getStyle('O' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P' . $rowIsi3, '100%')->getStyle('P' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $rowIsi3++;
                $subrow2 = $rowIsi3;
            }
            $ObjSheet->setCellValue('B' . $subrow3, $regional)->getStyle('B' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . $subrow3, '11000')->getStyle('C' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . $subrow3, '1700')->getStyle('D' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . $subrow3, '50%')->getStyle('E' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F' . $subrow3, '100%')->getStyle('F' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->setCellValue('H' . $subrow3, '1000')->getStyle('H' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I' . $subrow3, '900')->getStyle('I' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . $subrow3, '280')->getStyle('J' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . $subrow3, '254')->getStyle('K' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . $subrow3, '250')->getStyle('L' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . $subrow3, '100%')->getStyle('M' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N' . $subrow3, '100%')->getStyle('N' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O' . $subrow3, '100%')->getStyle('O' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P' . $subrow3, '100%')->getStyle('P' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
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
