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
        foreach ($rOs as $regional => $areas) {
            $rowIsi = 0;
            $rowHeader = 0;
            $TotalAPO = 0;
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle($regional);

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

            // ISI KONTEN
            $rowIsi += 6;
            $subrow = 0;
            $totalROPS = [0, 0, 0, 0, 0, 0];
            $percentROPS = [0, 0, 0, 0, 0];
            foreach ($rOs[$regional] as $area => $detRO) {
                $detRO = (array)$detRO;

                $TotalAPO = $detRO['TOTALAPO'];
                $TotalData = ($detRO['PS_2-3'] + $detRO['PS_4-5'] + $detRO['PS_6-10'] + $detRO['PS_>11']);
                $totalROPS[0] += $detRO['PS_2-3'];
                $totalROPS[1] += $detRO['PS_4-5'];
                $totalROPS[2] += $detRO['PS_6-10'];
                $totalROPS[3] += $detRO['PS_>11'];
                $totalROPS[4] += $TotalData;
                $totalROPS[5] += $detRO['TOTALPS'];

                $percentROPS[0] = (!empty($TotalData) ? (($detRO['PS_2-3'] / $TotalData) * 100) : 0);
                $percentROPS[1] = (!empty($TotalData) ? (($detRO['PS_4-5'] / $TotalData) * 100) : 0);
                $percentROPS[2] = (!empty($TotalData) ? (($detRO['PS_6-10'] / $TotalData) * 100) : 0);
                $percentROPS[3] = (!empty($TotalData) ? (($detRO['PS_>11'] / $TotalData) * 100) : 0);
                $percentROPS[4] = (!empty($detRO['TOTALPS']) ? (($TotalData / $detRO['TOTALPS']) * 100) : 0);


                $ObjSheet->setCellValue('B' . $rowIsi, $area)->getStyle('B' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi, $detRO['TOTALPS'])->getStyle('C' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi, $TotalData)->getStyle('D' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi, number_format($percentROPS[4], 2, '.', '') . '%')->getStyle('E' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi, '100%')->getStyle('F' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $ObjSheet->setCellValue('H' . $rowIsi, $TotalData)->getStyle('H' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I' . $rowIsi, $detRO['PS_2-3'])->getStyle('I' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi, $detRO['PS_4-5'])->getStyle('J' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi, $detRO['PS_6-10'])->getStyle('K' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi, $detRO['PS_>11'])->getStyle('L' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi, number_format($percentROPS[0], 2, '.', '') . '%')->getStyle('M' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi, number_format($percentROPS[1], 2, '.', '') . '%')->getStyle('N' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O' . $rowIsi, number_format($percentROPS[2], 2, '.', '') . '%')->getStyle('O' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P' . $rowIsi, number_format($percentROPS[3], 2, '.', '') . '%')->getStyle('P' . $rowIsi)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $rowIsi++;
                $subrow = $rowIsi;
            }

            $percentROPS = [0, 0, 0, 0, 0];
            $percentROPS[0] = (!empty($totalROPS[4]) ? (($totalROPS[0] / $totalROPS[4]) * 100) : 0);
            $percentROPS[1] = (!empty($totalROPS[4]) ? (($totalROPS[1] / $totalROPS[4]) * 100) : 0);
            $percentROPS[2] = (!empty($totalROPS[4]) ? (($totalROPS[2] / $totalROPS[4]) * 100) : 0);
            $percentROPS[3] = (!empty($totalROPS[4]) ? (($totalROPS[3] / $totalROPS[4]) * 100) : 0);

            $percentROPS[4] = (!empty($totalROPS[5]) ? (($totalROPS[4] / $totalROPS[5]) * 100) : 0);

            $ObjSheet->setCellValue('B' . $subrow, $regional)->getStyle('B' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . $subrow, $totalROPS[5])->getStyle('C' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . $subrow, $totalROPS[4])->getStyle('D' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . $subrow, number_format($percentROPS[4], 2, '.', '') . '%')->getStyle('E' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F' . $subrow, '100%')->getStyle('F' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->setCellValue('H' . $subrow, $totalROPS[4])->getStyle('H' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I' . $subrow, $totalROPS[0])->getStyle('I' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . $subrow, $totalROPS[1])->getStyle('J' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . $subrow, $totalROPS[2])->getStyle('K' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . $subrow, $totalROPS[3])->getStyle('L' . $subrow)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . $subrow, number_format($percentROPS[0], 2, '.', '') . '%')->getStyle('M' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N' . $subrow, number_format($percentROPS[1], 2, '.', '') . '%')->getStyle('N' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O' . $subrow, number_format($percentROPS[2], 2, '.', '') . '%')->getStyle('O' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P' . $subrow, number_format($percentROPS[3], 2, '.', '') . '%')->getStyle('P' . $subrow)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->mergeCells('R3:R5')->setCellValue('R3', 'JUMLAH PEDAGANG SAYUR RO PEDAGANG SAYUR/APO')->getStyle('R3:R5')->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('S3:S5')->setCellValue('S3', 'JUMLAH APO')->getStyle('S3:S5')->applyFromArray($this->styling_title_template('0000FF', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('T3:T5')->setCellValue('T3', 'RT2 PEDAGANG SAYUR RO/APO')->getStyle('T3:T5')->applyFromArray($this->styling_title_template('00FF00', '000000'))->getAlignment()->setWrapText(true);

            //ISI
            $ObjSheet->setCellValue('R6', $totalROPS[4])->getStyle('R6')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S6', $TotalAPO)->getStyle('S6')->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T6', (!empty($TotalAPO) ? round($totalROPS[4] / $TotalAPO) : 0))->getStyle('T6')->applyFromArray($this->styling_default_template('11', '000000'));

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

            $rowIsi2 = ($rowHeader2 + 4);
            $subrow2 = ($rowHeader2 + 4) + 0;
            $totalRORetail = [0, 0, 0, 0, 0, 0];
            $percentRORetail = [0, 0, 0, 0, 0];
            foreach ($rOs[$regional] as $area => $detRO) {
                $detRO = (array)$detRO;

                $TotalData = ($detRO['Retail_2-3'] + $detRO['Retail_4-5'] + $detRO['Retail_6-10'] + $detRO['Retail_>11']);
                $totalRORetail[0] += $detRO['Retail_2-3'];
                $totalRORetail[1] += $detRO['Retail_4-5'];
                $totalRORetail[2] += $detRO['Retail_6-10'];
                $totalRORetail[3] += $detRO['Retail_>11'];
                $totalRORetail[4] += $TotalData;
                $totalRORetail[5] += $detRO['TOTALRETAIL'];

                $percentRORetail[0] = (!empty($TotalData) ? (($detRO['Retail_2-3'] / $TotalData) * 100) : 0);
                $percentRORetail[1] = (!empty($TotalData) ? (($detRO['Retail_4-5'] / $TotalData) * 100) : 0);
                $percentRORetail[2] = (!empty($TotalData) ? (($detRO['Retail_6-10'] / $TotalData) * 100) : 0);
                $percentRORetail[3] = (!empty($TotalData) ? (($detRO['Retail_>11'] / $TotalData) * 100) : 0);
                $percentRORetail[4] = (!empty($detRO['TOTALRETAIL']) ? (($TotalData / $detRO['TOTALRETAIL']) * 100) : 0);

                $ObjSheet->setCellValue('B' . $rowIsi2, $area)->getStyle('B' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi2, $detRO['TOTALRETAIL'])->getStyle('C' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi2, $TotalData)->getStyle('D' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi2, number_format($percentRORetail[4], 2, '.', '') . '%')->getStyle('E' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi2, '100%')->getStyle('F' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $ObjSheet->setCellValue('H' . $rowIsi2, $TotalData)->getStyle('H' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I' . $rowIsi2, $detRO['Retail_2-3'])->getStyle('I' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi2, $detRO['Retail_4-5'])->getStyle('J' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi2, $detRO['Retail_6-10'])->getStyle('K' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi2, $detRO['Retail_>11'])->getStyle('L' . $rowIsi2)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi2, number_format($percentRORetail[0], 2, '.', '') . '%')->getStyle('M' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi2, number_format($percentRORetail[1], 2, '.', '') . '%')->getStyle('N' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O' . $rowIsi2, number_format($percentRORetail[2], 2, '.', '') . '%')->getStyle('O' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P' . $rowIsi2, number_format($percentRORetail[3], 2, '.', '') . '%')->getStyle('P' . $rowIsi2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $rowIsi2++;
                $subrow2 = $rowIsi2;
            }

            $percentRORetail[4] = (!empty($totalRORetail[5]) ? (($totalRORetail[4] / $totalRORetail[5]) * 100) : 0);

            $ObjSheet->setCellValue('B' . $subrow2, $regional)->getStyle('B' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . $subrow2, $totalRORetail[5])->getStyle('C' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . $subrow2, $totalRORetail[4])->getStyle('D' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . $subrow2, number_format($percentRORetail[4], 2, '.', '') . '%')->getStyle('E' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F' . $subrow2, '100%')->getStyle('F' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->setCellValue('H' . $subrow2, $totalRORetail[4])->getStyle('H' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I' . $subrow2, $totalRORetail[0])->getStyle('I' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . $subrow2, $totalRORetail[1])->getStyle('J' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . $subrow2, $totalRORetail[2])->getStyle('K' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . $subrow2, $totalRORetail[3])->getStyle('L' . $subrow2)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . $subrow2, number_format($percentRORetail[0], 2, '.', '') . '%')->getStyle('M' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N' . $subrow2, number_format($percentRORetail[1], 2, '.', '') . '%')->getStyle('N' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O' . $subrow2, number_format($percentRORetail[2], 2, '.', '') . '%')->getStyle('O' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P' . $subrow2, number_format($percentRORetail[3], 2, '.', '') . '%')->getStyle('P' . $subrow2)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            //ISI
            $ObjSheet->mergeCells('R' . ($rowHeader2 + 1) . ':R' . ($rowHeader2 + 3))->setCellValue('R' . ($rowHeader2 + 1), 'JUMLAH RETAIL RO RETAIL/APO')->getStyle('R' . ($rowHeader2 + 1) . ':R' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('S' . ($rowHeader2 + 1) . ':S' . ($rowHeader2 + 3))->setCellValue('S' . ($rowHeader2 + 1), 'JUMLAH APO')->getStyle('S' . ($rowHeader2 + 1) . ':S' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('0000FF', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('T' . ($rowHeader2 + 1) . ':T' . ($rowHeader2 + 3))->setCellValue('T' . ($rowHeader2 + 1), 'RT2 RETAIL RO/APO')->getStyle('T' . ($rowHeader2 + 1) . ':T' . ($rowHeader2 + 3))->applyFromArray($this->styling_title_template('00FF00', '000000'))->getAlignment()->setWrapText(true);

            $ObjSheet->setCellValue('R' . ($rowHeader2 + 4), $totalRORetail[4])->getStyle('R' . ($rowHeader2 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S' . ($rowHeader2 + 4), $TotalAPO)->getStyle('S' . ($rowHeader2 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T' . ($rowHeader2 + 4), (!empty($TotalAPO) ? round($totalRORetail[4] / $TotalAPO) : 0))->getStyle('T' . ($rowHeader2 + 4))->applyFromArray($this->styling_default_template('11', '000000'));

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

            $rowIsi3 = ($rowHeader3 + 4);
            $subrow3 = ($rowHeader3 + 4) + 0;
            $totalROLoss = [0, 0, 0, 0, 0, 0];
            $percentROLoss = [0, 0, 0, 0, 0];
            foreach ($rOs[$regional] as $area => $detRO) {
                $detRO = (array)$detRO;

                $TotalData = ($detRO['Loss_2-3'] + $detRO['Loss_4-5'] + $detRO['Loss_6-10'] + $detRO['Loss_>11']);
                $totalROLoss[0] += $detRO['Loss_2-3'];
                $totalROLoss[1] += $detRO['Loss_4-5'];
                $totalROLoss[2] += $detRO['Loss_6-10'];
                $totalROLoss[3] += $detRO['Loss_>11'];
                $totalROLoss[4] += $TotalData;
                $totalROLoss[5] += $detRO['TOTALLOSS'];

                $percentROLoss[0] = (!empty($TotalData) ? (($detRO['Loss_2-3'] / $TotalData) * 100) : 0);
                $percentROLoss[1] = (!empty($TotalData) ? (($detRO['Loss_4-5'] / $TotalData) * 100) : 0);
                $percentROLoss[2] = (!empty($TotalData) ? (($detRO['Loss_6-10'] / $TotalData) * 100) : 0);
                $percentROLoss[3] = (!empty($TotalData) ? (($detRO['Loss_>11'] / $TotalData) * 100) : 0);
                $percentROLoss[4] = (!empty($detRO['TOTALLOSS']) ? (($TotalData / $detRO['TOTALLOSS']) * 100) : 0);

                $ObjSheet->setCellValue('B' . $rowIsi3, $area)->getStyle('B' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi3, $detRO['TOTALLOSS'])->getStyle('C' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi3, $TotalData)->getStyle('D' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi3, number_format($percentROPS[4], 2, '.', '') . '%')->getStyle('E' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi3, '100%')->getStyle('F' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $ObjSheet->setCellValue('H' . $rowIsi3, $TotalData)->getStyle('H' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('I' . $rowIsi3, $detRO['Loss_2-3'])->getStyle('I' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi3, $detRO['Loss_4-5'])->getStyle('J' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi3, $detRO['Loss_6-10'])->getStyle('K' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi3, $detRO['Loss_>11'])->getStyle('L' . $rowIsi3)->applyFromArray($this->styling_default_template('11', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi3, number_format($percentROLoss[0], 2, '.', '') . '%')->getStyle('M' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi3, number_format($percentROLoss[1], 2, '.', '') . '%')->getStyle('N' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('O' . $rowIsi3, number_format($percentROLoss[2], 2, '.', '') . '%')->getStyle('O' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
                $ObjSheet->setCellValue('P' . $rowIsi3, number_format($percentROLoss[3], 2, '.', '') . '%')->getStyle('P' . $rowIsi3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

                $rowIsi3++;
                $subrow3 = $rowIsi3;
            }

            $percentROLoss[4] = (!empty($totalROLoss[5]) ? (($totalROLoss[4] / $totalROLoss[5]) * 100) : 0);

            $ObjSheet->setCellValue('B' . $subrow3, $regional)->getStyle('B' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . $subrow3, $totalROLoss[5])->getStyle('C' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . $subrow3, $totalROLoss[4])->getStyle('D' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . $subrow3, number_format($percentROLoss[4], 2, '.', '') . '%')->getStyle('E' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('F' . $subrow3, '100%')->getStyle('F' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            $ObjSheet->setCellValue('H' . $subrow3, $totalROLoss[4])->getStyle('H' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('I' . $subrow3, $totalROLoss[0])->getStyle('I' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . $subrow3, $totalROLoss[1])->getStyle('J' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . $subrow3, $totalROLoss[2])->getStyle('K' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . $subrow3, $totalROLoss[3])->getStyle('L' . $subrow3)->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . $subrow3, number_format($percentROLoss[0], 2, '.', '') . '%')->getStyle('M' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('N' . $subrow3, number_format($percentROLoss[1], 2, '.', '') . '%')->getStyle('N' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('O' . $subrow3, number_format($percentROLoss[2], 2, '.', '') . '%')->getStyle('O' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('P' . $subrow3, number_format($percentROLoss[3], 2, '.', '') . '%')->getStyle('P' . $subrow3)->applyFromArray($this->styling_title_template('00FF00', '000000'));

            //ISI
            $ObjSheet->mergeCells('R' . ($rowHeader3 + 1) . ':R' . ($rowHeader3 + 3))->setCellValue('R' . ($rowHeader3 + 1), 'JUMLAH LOSS RO LOSS/APO')->getStyle('R' . ($rowHeader3 + 1) . ':R' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('FFFF00', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('S' . ($rowHeader3 + 1) . ':S' . ($rowHeader3 + 3))->setCellValue('S' . ($rowHeader3 + 1), 'JUMLAH APO')->getStyle('S' . ($rowHeader3 + 1) . ':S' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('0000FF', '000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('T' . ($rowHeader3 + 1) . ':T' . ($rowHeader3 + 3))->setCellValue('T' . ($rowHeader3 + 1), 'RT2 LOSS RO/APO')->getStyle('T' . ($rowHeader3 + 1) . ':T' . ($rowHeader3 + 3))->applyFromArray($this->styling_title_template('00FF00', '000000'))->getAlignment()->setWrapText(true);

            $ObjSheet->setCellValue('R' . ($rowHeader3 + 4), $totalROLoss[4])->getStyle('R' . ($rowHeader3 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('S' . ($rowHeader3 + 4), $TotalAPO)->getStyle('S' . ($rowHeader3 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('T' . ($rowHeader3 + 4), (!empty($TotalAPO) ? round($totalROLoss[4] / $TotalAPO) : 0))->getStyle('T' . ($rowHeader3 + 4))->applyFromArray($this->styling_default_template('11', '000000'));
        }

        $spreadsheet->removeSheetByIndex(0);

        $fileName = 'Repeat Order APO';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function gen_ro_shop($rOs, $updated_at)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        foreach ($rOs as $area => $item) {
            $rowIsi = 0;
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $area));

            $ObjSheet->getColumnDimension('B')->setWidth('25');
            $ObjSheet->getColumnDimension('C')->setWidth('45');
            $ObjSheet->getColumnDimension('D')->setWidth('20');
            $ObjSheet->getColumnDimension('E')->setWidth('20');
            $ObjSheet->getColumnDimension('F')->setWidth('15');
            $ObjSheet->getColumnDimension('G')->setWidth('20');
            $ObjSheet->getColumnDimension('H')->setWidth('5');
            $ObjSheet->getColumnDimension('I')->setWidth('25');
            $ObjSheet->getColumnDimension('J')->setWidth('45');
            $ObjSheet->getColumnDimension('K')->setWidth('20');
            $ObjSheet->getColumnDimension('L')->setWidth('20');
            $ObjSheet->getColumnDimension('M')->setWidth('15');
            $ObjSheet->getColumnDimension('N')->setWidth('20');

            // PEDAGANG SAYUR
            // HEADER
            $ObjSheet->mergeCells('B2:N3')->setCellValue('B2', $area)->getStyle('B2:N3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $rowIsi += 4;
            $tot_data = 0;
            $ObjSheet->mergeCells('B' . $rowIsi . ':N' . $rowIsi)->setCellValue('B' . $rowIsi, 'DETAIL REPEAT ORDER')->getStyle('B' . $rowIsi . ':M' . $rowIsi)->applyFromArray($this->styling_title_template('FFFF00', '000000'));

            // DETAIL
            $ObjSheet->mergeCells('B' . ($rowIsi + 1) . ':G' . ($rowIsi + 1))->setCellValue('B' . ($rowIsi + 1), 'REPEAT ORDER 2-3')->getStyle('B' . ($rowIsi + 1) . ':G' . ($rowIsi + 1))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('B' . ($rowIsi + 2), 'NAMA TOKO')->getStyle('B' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . ($rowIsi + 2), 'ALAMAT')->getStyle('C' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . ($rowIsi + 2), 'PEMILIK')->getStyle('D' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . ($rowIsi + 2), 'NOMOR TELEPON')->getStyle('E' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('F' . ($rowIsi + 2), 'TOTAL RO')->getStyle('F' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('G' . ($rowIsi + 2), 'TIPE TOKO')->getStyle('G' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));

            $ObjSheet->mergeCells('I' . ($rowIsi + 1) . ':M' . ($rowIsi + 1))->setCellValue('I' . ($rowIsi + 1), 'REPEAT ORDER 4-5')->getStyle('I' . ($rowIsi + 1) . ':N' . ($rowIsi + 1))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('I' . ($rowIsi + 2), 'NAMA TOKO')->getStyle('I' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . ($rowIsi + 2), 'ALAMAT')->getStyle('J' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . ($rowIsi + 2), 'PEMILIK')->getStyle('K' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . ($rowIsi + 2), 'NOMOR TELEPON')->getStyle('L' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . ($rowIsi + 2), 'TOTAL RO')->getStyle('M' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N' . ($rowIsi + 2), 'TIPE TOKO')->getStyle('N' . ($rowIsi + 2))->applyFromArray($this->styling_title_template('00FFFF', '000000'));

            // ISI KONTEN
            $rowIsi2 = ($rowIsi + 3);
            foreach ($item["2-3"] as $itemDet) {
                $ObjSheet->setCellValue('B' . $rowIsi2, $itemDet['NAME_SHOP'])->getStyle('B' . $rowIsi2)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi2, $itemDet['DETLOC_SHOP'])->getStyle('C' . $rowIsi2)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi2, $itemDet['OWNER_SHOP'])->getStyle('D' . $rowIsi2)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi2, $itemDet['TELP_SHOP'])->getStyle('E' . $rowIsi2)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi2, $itemDet['TOTAL_RO'])->getStyle('F' . $rowIsi2)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('G' . $rowIsi2, $itemDet['TYPE_SHOP'])->getStyle('G' . $rowIsi2)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));

                $rowIsi2++;
            }

            $rowIsi3 = ($rowIsi + 3);
            foreach ($item["4-5"] as $itemDet) {
                $ObjSheet->setCellValue('I' . $rowIsi3, $itemDet['NAME_SHOP'])->getStyle('I' . $rowIsi3)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi3, $itemDet['DETLOC_SHOP'])->getStyle('J' . $rowIsi3)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi3, $itemDet['OWNER_SHOP'])->getStyle('K' . $rowIsi3)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi3, $itemDet['TELP_SHOP'])->getStyle('L' . $rowIsi3)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi3, $itemDet['TOTAL_RO'])->getStyle('M' . $rowIsi3)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi3, $itemDet['TYPE_SHOP'])->getStyle('N' . $rowIsi3)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));

                $rowIsi3++;
            }

            $ObjSheet->mergeCells('B' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2) . ':G' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2))->setCellValue('B' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2), 'REPEAT ORDER 6-10')->getStyle('B' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2) . ':G' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('B' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'NAMA TOKO')->getStyle('B' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('C' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'DETLOC_SHOP')->getStyle('C' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('D' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'PEMILIK')->getStyle('D' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('E' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'NOMOR TELEPON')->getStyle('E' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('F' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'TOTAL RO')->getStyle('F' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('G' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'TIPE TOKO')->getStyle('G' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));

            $ObjSheet->mergeCells('I' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2) . ':N' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2))->setCellValue('I' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2), 'REPEAT ORDER >11')->getStyle('I' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2) . ':N' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 2))->applyFromArray($this->styling_title_template('00FF00', '000000'));
            $ObjSheet->setCellValue('I' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'NAMA TOKO')->getStyle('I' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('J' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'DETLOC_SHOP')->getStyle('J' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('K' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'PEMILIK')->getStyle('K' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('L' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'NOMOR TELEPON')->getStyle('L' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('M' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'TOTAL RO')->getStyle('M' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            $ObjSheet->setCellValue('N' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3), 'TIPE TOKO')->getStyle('N' . ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3))->applyFromArray($this->styling_title_template('00FFFF', '000000'));

            $rowIsi4 = ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 4);
            foreach ($item["6-10"] as $itemDet) {
                $ObjSheet->setCellValue('B' . $rowIsi4, $itemDet['NAME_SHOP'])->getStyle('B' . $rowIsi4)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('C' . $rowIsi4, $itemDet['DETLOC_SHOP'])->getStyle('C' . $rowIsi4)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('D' . $rowIsi4, $itemDet['OWNER_SHOP'])->getStyle('D' . $rowIsi4)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('E' . $rowIsi4, $itemDet['TELP_SHOP'])->getStyle('E' . $rowIsi4)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('F' . $rowIsi4, $itemDet['TOTAL_RO'])->getStyle('F' . $rowIsi4)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('G' . $rowIsi4, $itemDet['TYPE_SHOP'])->getStyle('G' . $rowIsi4)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));

                $rowIsi4++;
            }

            $rowIsi5 = ((($rowIsi2 < $rowIsi3) ? $rowIsi3 : $rowIsi2) + 3);
            foreach ($item[">11"] as $itemDet) {
                $ObjSheet->setCellValue('I' . $rowIsi5, $itemDet['NAME_SHOP'])->getStyle('I' . $rowIsi5)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('J' . $rowIsi5, $itemDet['DETLOC_SHOP'])->getStyle('J' . $rowIsi5)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('K' . $rowIsi5, $itemDet['OWNER_SHOP'])->getStyle('K' . $rowIsi5)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('L' . $rowIsi5, $itemDet['TELP_SHOP'])->getStyle('L' . $rowIsi5)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('M' . $rowIsi5, $itemDet['TOTAL_RO'])->getStyle('M' . $rowIsi5)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                $ObjSheet->setCellValue('N' . $rowIsi5, $itemDet['TYPE_SHOP'])->getStyle('N' . $rowIsi5)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));

                $rowIsi5++;
            }
        }

        $spreadsheet->removeSheetByIndex(0);

        $fileName = 'Repeat Order Toko';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function gen_ro_shop_range($rOs, $totMonth)
    {
        $spreadsheet = new Spreadsheet();

        $regional = '';
        $lastRange = '';
        $dataRange = range('G', 'Z');
        $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
        foreach ($rOs as $regional => $detRegional) {
            foreach ($detRegional as $key => $item) {
                $ObjSheet = $spreadsheet->createSheet();
                $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $key));

                $ObjSheet->getColumnDimension('B')->setWidth('25');
                $ObjSheet->getColumnDimension('C')->setWidth('45');
                $ObjSheet->getColumnDimension('D')->setWidth('20');
                $ObjSheet->getColumnDimension('E')->setWidth('20');
                $ObjSheet->getColumnDimension('F')->setWidth('15');

                // HEADER
                $ObjSheet->mergeCells('B2:B3')->setCellValue('B2', 'NAMA TOKO')->getStyle('B2:B3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
                $ObjSheet->mergeCells('C2:C3')->setCellValue('C2', 'ALAMAT')->getStyle('C2:C3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
                $ObjSheet->mergeCells('D2:D3')->setCellValue('D2', 'PEMILIK')->getStyle('D2:D3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
                $ObjSheet->mergeCells('E2:E3')->setCellValue('E2', 'NOMOR TELEPON')->getStyle('E2:E3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
                $ObjSheet->mergeCells('F2:F3')->setCellValue('F2', 'TIPE TOKO')->getStyle('F2:F3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));

                $rowData = 4;
                foreach ($item as $detItem) {
                    // ISI KONTEN
                    $ObjSheet->setCellValue('B' . $rowData, $detItem->NAME_SHOP)->getStyle('B' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                    $ObjSheet->setCellValue('C' . $rowData, $detItem->DETLOC_SHOP)->getStyle('C' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                    $ObjSheet->setCellValue('D' . $rowData, $detItem->OWNER_SHOP)->getStyle('D' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                    $ObjSheet->setCellValue('E' . $rowData, $detItem->TELP_SHOP)->getStyle('E' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                    $ObjSheet->setCellValue('F' . $rowData, $detItem->TYPE_SHOP)->getStyle('F' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));

                    // HEADER
                    $tmpArray = json_decode(json_encode($detItem), true);
                    for ($i = 0; $i < $totMonth; $i++) {
                        $ObjSheet->getColumnDimension($dataRange[$i])->setWidth('20');
                        $keyData = $tmpArray["KEY" . $i];
                        $ObjSheet->setCellValue($dataRange[$i] . '3', $months[(explode(';', $keyData)[0] - 1)] . '' . explode(';', $keyData)[1])->getStyle($dataRange[$i] . '3')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
                        $lastRange = $dataRange[$i];

                        // ISI KONTEN
                        $ObjSheet->setCellValue($dataRange[$i] . '' . $rowData, $tmpArray["VALUE" . $i])->getStyle($dataRange[$i] . '' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'));
                    }
                    $rowData++;
                }
                $ObjSheet->mergeCells($dataRange[0] . '2:' . $lastRange . '2')->setCellValue($dataRange[0] . '2', 'Total RO')->getStyle($dataRange[0] . '2:' . $lastRange . '2')->applyFromArray($this->styling_title_template('00FFFF', '000000'));
            }
        }

        $spreadsheet->removeSheetByIndex(0);

        $fileName = 'Repeat Order Toko ' . $regional;
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
