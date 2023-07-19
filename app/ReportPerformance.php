<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportPerformance extends Model
{
    public function gen_performance_nonust($rOs, $year)
    {
        $spreadsheet = new Spreadsheet();
        $dataRange = range('G', 'R');
        $months = array(
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
        );
        foreach ($rOs as $keyMain => $item) {
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $keyMain));

            $ObjSheet->getColumnDimension('A')->setWidth('5');
            $ObjSheet->getColumnDimension('B')->setWidth('8');
            $ObjSheet->getColumnDimension('C')->setWidth('8');
            $ObjSheet->getColumnDimension('D')->setWidth('8');

            $ObjSheet->getColumnDimension('E')->setWidth('25');
            $ObjSheet->getColumnDimension('F')->setWidth('13');

            $ObjSheet->getColumnDimension('G')->setWidth('9');
            $ObjSheet->getColumnDimension('G')->setWidth('9');
            $ObjSheet->getColumnDimension('H')->setWidth('9');
            $ObjSheet->getColumnDimension('I')->setWidth('9');
            $ObjSheet->getColumnDimension('J')->setWidth('9');
            $ObjSheet->getColumnDimension('K')->setWidth('9');
            $ObjSheet->getColumnDimension('L')->setWidth('9');
            $ObjSheet->getColumnDimension('M')->setWidth('9');
            $ObjSheet->getColumnDimension('N')->setWidth('9');
            $ObjSheet->getColumnDimension('O')->setWidth('9');
            $ObjSheet->getColumnDimension('P')->setWidth('9');
            $ObjSheet->getColumnDimension('Q')->setWidth('9');
            $ObjSheet->getColumnDimension('R')->setWidth('9');
            $ObjSheet->getColumnDimension('S')->setWidth('9');

            $ObjSheet->getRowDimension('4')->setRowHeight('32');

            // HEADER
            $ObjSheet->mergeCells('B3:D3')->setCellValue('B3', 'NON UST')->getStyle('B3:D3')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->setCellValue('B4', '2021')->getStyle('B4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->setCellValue('C4', '2022')->getStyle('C4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->setCellValue('D4', '2023')->getStyle('D4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->mergeCells('E3:E4')->setCellValue('E3', 'Area')->getStyle('E3:E4')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
            $ObjSheet->mergeCells('F3:F4')->setCellValue('F3', 'TARGET STANDAR')->getStyle('F3:F4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('G3:T3')->setCellValue('G3', 'REAL OMSET NON UST ' . $year)->getStyle('G3:T3')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));

            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . '4', $months[$key])->getStyle($detItem . '4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            }

            $ObjSheet->setCellValue('S4', 'RT ' . $year)->getStyle('S4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('T4', '% RT2 VS TGT')->getStyle('T4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            // ISI KONTEN
            $rowIsi1 = 5;
            $tot_vs_tgt = 0;
            foreach ($item as $itemArea) {
                // dd($itemArea);
                $ObjSheet->setCellValue('B' . $rowIsi1, $itemArea['TOT_1'])->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('C' . $rowIsi1, $itemArea['TOT_2'])->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('D' . $rowIsi1, $itemArea['TOT_3'])->getStyle('D' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('E' . $rowIsi1, $itemArea['AREA'])->getStyle('E' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue('F' . $rowIsi1, 300)->getStyle('F' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');

                foreach ($dataRange as $key => $detItem) {
                    $ObjSheet->setCellValue($detItem . $rowIsi1, $itemArea['MONTH'][$key])->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                }

                $vs_tgt = (($itemArea['TOTAL_RT'] / 300) * 100);
                $tot_vs_tgt += $vs_tgt;
                $ObjSheet->setCellValue('S' . $rowIsi1, $itemArea['TOTAL_RT'])->getStyle('S' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('T' . $rowIsi1, number_format($vs_tgt, 2, '.', '') . '%')->getStyle('T' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'))->getAlignment()->setHorizontal('center');

                $rowIsi1++;
            }

            // FOOTER
            $ObjSheet->setCellValue('B' . $rowIsi1, '=SUM(B5:B' . ($rowIsi1 - 1) . ')')->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('C' . $rowIsi1, '=SUM(C5:C' . ($rowIsi1 - 1) . ')')->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('D' . $rowIsi1, '=SUM(D5:D' . ($rowIsi1 - 1) . ')')->getStyle('D' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('E' . $rowIsi1, $keyMain)->getStyle('E' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('left')->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowIsi1, '=SUM(F5:F' . ($rowIsi1 - 1) . ')')->getStyle('F' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . $rowIsi1, '=SUM(' . $detItem . '5:' . $detItem . ($rowIsi1 - 1) . ')')->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            }
            $ObjSheet->setCellValue('S' . $rowIsi1, '=SUM(S5:S' . ($rowIsi1 - 1) . ')')->getStyle('S' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('T' . $rowIsi1, number_format($tot_vs_tgt, 2, '.', '') . '%')->getStyle('T' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
        }

        $spreadsheet->removeSheetByIndex(0);
        $fileName = 'Performance NON UST APO ' . $year;

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function gen_performance_geprek($rOs, $year)
    {
        $spreadsheet = new Spreadsheet();
        $dataRange = range('D', 'O');
        $months = array(
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
        );
        foreach ($rOs as $keyMain => $item) {
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $keyMain));

            $ObjSheet->getColumnDimension('A')->setWidth('5');

            $ObjSheet->getColumnDimension('B')->setWidth('25');
            $ObjSheet->getColumnDimension('C')->setWidth('13');

            $ObjSheet->getColumnDimension('D')->setWidth('9');
            $ObjSheet->getColumnDimension('E')->setWidth('9');
            $ObjSheet->getColumnDimension('F')->setWidth('9');
            $ObjSheet->getColumnDimension('G')->setWidth('9');
            $ObjSheet->getColumnDimension('H')->setWidth('9');
            $ObjSheet->getColumnDimension('I')->setWidth('9');
            $ObjSheet->getColumnDimension('J')->setWidth('9');
            $ObjSheet->getColumnDimension('K')->setWidth('9');
            $ObjSheet->getColumnDimension('L')->setWidth('9');
            $ObjSheet->getColumnDimension('M')->setWidth('9');
            $ObjSheet->getColumnDimension('N')->setWidth('9');
            $ObjSheet->getColumnDimension('O')->setWidth('9');

            $ObjSheet->getColumnDimension('P')->setWidth('15');
            $ObjSheet->getColumnDimension('Q')->setWidth('15');

            $ObjSheet->getRowDimension('4')->setRowHeight('32');

            // HEADER
            $ObjSheet->mergeCells('B3:B4')->setCellValue('B3', 'Area')->getStyle('B3:B4')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
            $ObjSheet->mergeCells('C3:C4')->setCellValue('C3', 'TARGET STANDAR')->getStyle('C3:C4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('D3:Q3')->setCellValue('D3', 'REAL OMSET NON UGP ' . $year)->getStyle('D3:Q3')->applyFromArray($this->styling_title_template('FFFE00FE', 'FFFFFFFF'));

            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . '4', $months[$key])->getStyle($detItem . '4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            }

            $ObjSheet->setCellValue('P4', 'RT ' . $year)->getStyle('P4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('Q4', '% RT2 VS TGT')->getStyle('Q4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            // ISI KONTEN
            $rowIsi1 = 5;
            $tot_vs_tgt = 0;
            foreach ($item as $itemArea) {
                // dd($itemArea);
                $ObjSheet->setCellValue('B' . $rowIsi1, $itemArea['AREA'])->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue('C' . $rowIsi1, 75)->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');

                foreach ($dataRange as $key => $detItem) {
                    $ObjSheet->setCellValue($detItem . $rowIsi1, $itemArea['MONTH'][$key])->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                }

                $vs_tgt = (($itemArea['TOTAL_RT'] / 75) * 100);
                $tot_vs_tgt += $vs_tgt;
                $ObjSheet->setCellValue('P' . $rowIsi1, $itemArea['TOTAL_RT'])->getStyle('P' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('Q' . $rowIsi1, number_format($vs_tgt, 2, '.', '') . '%')->getStyle('Q' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'))->getAlignment()->setHorizontal('center');

                $rowIsi1++;
            }

            // FOOTER
            $ObjSheet->setCellValue('B' . $rowIsi1, $keyMain)->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('left')->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowIsi1, '=SUM(F5:F' . ($rowIsi1 - 1) . ')')->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . $rowIsi1, '=SUM(' . $detItem . '5:' . $detItem . ($rowIsi1 - 1) . ')')->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            }
            $ObjSheet->setCellValue('P' . $rowIsi1, '=SUM(S5:S' . ($rowIsi1 - 1) . ')')->getStyle('P' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('Q' . $rowIsi1, number_format($tot_vs_tgt, 2, '.', '') . '%')->getStyle('Q' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
        }

        $spreadsheet->removeSheetByIndex(0);
        $fileName = 'Performance NON UGP APO ' . $year;

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function gen_performance_rendang($rOs, $year)
    {
        $spreadsheet = new Spreadsheet();
        $dataRange = range('D', 'O');
        $months = array(
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
        );
        foreach ($rOs as $keyMain => $item) {
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $keyMain));

            $ObjSheet->getColumnDimension('A')->setWidth('5');

            $ObjSheet->getColumnDimension('B')->setWidth('25');
            $ObjSheet->getColumnDimension('C')->setWidth('13');

            $ObjSheet->getColumnDimension('D')->setWidth('9');
            $ObjSheet->getColumnDimension('E')->setWidth('9');
            $ObjSheet->getColumnDimension('F')->setWidth('9');
            $ObjSheet->getColumnDimension('G')->setWidth('9');
            $ObjSheet->getColumnDimension('H')->setWidth('9');
            $ObjSheet->getColumnDimension('I')->setWidth('9');
            $ObjSheet->getColumnDimension('J')->setWidth('9');
            $ObjSheet->getColumnDimension('K')->setWidth('9');
            $ObjSheet->getColumnDimension('L')->setWidth('9');
            $ObjSheet->getColumnDimension('M')->setWidth('9');
            $ObjSheet->getColumnDimension('N')->setWidth('9');
            $ObjSheet->getColumnDimension('O')->setWidth('9');

            $ObjSheet->getColumnDimension('P')->setWidth('15');
            $ObjSheet->getColumnDimension('Q')->setWidth('15');

            $ObjSheet->getRowDimension('4')->setRowHeight('32');

            // HEADER
            $ObjSheet->mergeCells('B3:B4')->setCellValue('B3', 'Area')->getStyle('B3:B4')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
            $ObjSheet->mergeCells('C3:C4')->setCellValue('C3', 'TARGET STANDAR')->getStyle('C3:C4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('D3:Q3')->setCellValue('D3', 'REAL OMSET NON URD ' . $year)->getStyle('D3:Q3')->applyFromArray($this->styling_title_template('FF833C0C', 'FFFFFFFF'));

            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . '4', $months[$key])->getStyle($detItem . '4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            }

            $ObjSheet->setCellValue('P4', 'RT ' . $year)->getStyle('P4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('Q4', '% RT2 VS TGT')->getStyle('Q4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            // ISI KONTEN
            $rowIsi1 = 5;
            $tot_vs_tgt = 0;
            foreach ($item as $itemArea) {
                // dd($itemArea);
                $ObjSheet->setCellValue('B' . $rowIsi1, $itemArea['AREA'])->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue('C' . $rowIsi1, 75)->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');

                foreach ($dataRange as $key => $detItem) {
                    $ObjSheet->setCellValue($detItem . $rowIsi1, $itemArea['MONTH'][$key])->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                }

                $vs_tgt = (($itemArea['TOTAL_RT'] / 75) * 100);
                $tot_vs_tgt += $vs_tgt;
                $ObjSheet->setCellValue('P' . $rowIsi1, $itemArea['TOTAL_RT'])->getStyle('P' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('Q' . $rowIsi1, number_format($vs_tgt, 2, '.', '') . '%')->getStyle('Q' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'))->getAlignment()->setHorizontal('center');

                $rowIsi1++;
            }

            // FOOTER
            $ObjSheet->setCellValue('B' . $rowIsi1, $keyMain)->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('left')->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowIsi1, '=SUM(F5:F' . ($rowIsi1 - 1) . ')')->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . $rowIsi1, '=SUM(' . $detItem . '5:' . $detItem . ($rowIsi1 - 1) . ')')->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            }
            $ObjSheet->setCellValue('P' . $rowIsi1, '=SUM(S5:S' . ($rowIsi1 - 1) . ')')->getStyle('P' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('Q' . $rowIsi1, number_format($tot_vs_tgt, 2, '.', '') . '%')->getStyle('Q' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
        }

        $spreadsheet->removeSheetByIndex(0);
        $fileName = 'Performance NON URD APO ' . $year;

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function gen_performance_ust($rOs, $year)
    {
        $spreadsheet = new Spreadsheet();
        $dataRange = range('F', 'Q');
        $months = array(
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
        );
        foreach ($rOs as $keyMain => $item) {
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $keyMain));

            $ObjSheet->getColumnDimension('A')->setWidth('5');
            $ObjSheet->getColumnDimension('B')->setWidth('8');
            $ObjSheet->getColumnDimension('C')->setWidth('8');

            $ObjSheet->getColumnDimension('D')->setWidth('25');
            $ObjSheet->getColumnDimension('E')->setWidth('13');

            $ObjSheet->getColumnDimension('F')->setWidth('9');
            $ObjSheet->getColumnDimension('G')->setWidth('9');
            $ObjSheet->getColumnDimension('H')->setWidth('9');
            $ObjSheet->getColumnDimension('I')->setWidth('9');
            $ObjSheet->getColumnDimension('J')->setWidth('9');
            $ObjSheet->getColumnDimension('K')->setWidth('9');
            $ObjSheet->getColumnDimension('L')->setWidth('9');
            $ObjSheet->getColumnDimension('M')->setWidth('9');
            $ObjSheet->getColumnDimension('N')->setWidth('9');
            $ObjSheet->getColumnDimension('O')->setWidth('9');
            $ObjSheet->getColumnDimension('P')->setWidth('9');
            $ObjSheet->getColumnDimension('Q')->setWidth('9');

            $ObjSheet->getColumnDimension('R')->setWidth('15');
            $ObjSheet->getColumnDimension('S')->setWidth('15');

            $ObjSheet->getRowDimension('4')->setRowHeight('32');

            // HEADER
            $ObjSheet->mergeCells('B3:C3')->setCellValue('B3', 'UST')->getStyle('B3:C3')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('B4', '2022')->getStyle('B4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->setCellValue('C4', '2023')->getStyle('C4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->mergeCells('D3:D4')->setCellValue('D3', 'Area')->getStyle('D3:D4')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
            $ObjSheet->mergeCells('E3:E4')->setCellValue('E3', 'TARGET STANDAR')->getStyle('E3:E4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('F3:S3')->setCellValue('F3', 'REAL OMSET UST ' . $year)->getStyle('F3:S3')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . '4', $months[$key])->getStyle($detItem . '4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            }

            $ObjSheet->setCellValue('R4', 'RT ' . $year)->getStyle('R4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('S4', '% RT2 VS TGT')->getStyle('S4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            // ISI KONTEN
            $rowIsi1 = 5;
            $tot_vs_tgt = 0;
            foreach ($item as $itemArea) {
                // dd($itemArea);
                $ObjSheet->setCellValue('B' . $rowIsi1, $itemArea['TOT_1'])->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('C' . $rowIsi1, $itemArea['TOT_2'])->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('D' . $rowIsi1, $itemArea['AREA'])->getStyle('D' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue('E' . $rowIsi1, 24)->getStyle('E' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');

                foreach ($dataRange as $key => $detItem) {
                    $ObjSheet->setCellValue($detItem . $rowIsi1, $itemArea['MONTH'][$key])->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                }

                $vs_tgt = (($itemArea['TOTAL_RT'] / 24) * 100);
                $tot_vs_tgt += $vs_tgt;
                $ObjSheet->setCellValue('R' . $rowIsi1, $itemArea['TOTAL_RT'])->getStyle('R' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('S' . $rowIsi1, number_format($vs_tgt, 2, '.', '') . '%')->getStyle('S' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'))->getAlignment()->setHorizontal('center');

                $rowIsi1++;
            }

            // FOOTER
            $ObjSheet->setCellValue('B' . $rowIsi1, '=SUM(B5:B' . ($rowIsi1 - 1) . ')')->getStyle('B' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('C' . $rowIsi1, '=SUM(C5:C' . ($rowIsi1 - 1) . ')')->getStyle('C' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('D' . $rowIsi1, $keyMain)->getStyle('D' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('left')->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowIsi1, '=SUM(F5:F' . ($rowIsi1 - 1) . ')')->getStyle('E' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . $rowIsi1, '=SUM(' . $detItem . '5:' . $detItem . ($rowIsi1 - 1) . ')')->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            }
            $ObjSheet->setCellValue('R' . $rowIsi1, '=SUM(S5:S' . ($rowIsi1 - 1) . ')')->getStyle('R' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('S' . $rowIsi1, number_format($tot_vs_tgt, 2, '.', '') . '%')->getStyle('S' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
        }

        $spreadsheet->removeSheetByIndex(0);
        $fileName = 'Performance UST APO ' . $year;

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function gen_performance_rekap($rOs, $year)
    {
        $spreadsheet = new Spreadsheet();
        $dataRange = range('H', 'S');
        $dataRangeReal = range('B', 'D');
        $months = array(
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
        );
        foreach ($rOs as $keyMain => $item) {
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $keyMain));

            $ObjSheet->getColumnDimension('A')->setWidth('5');
            $ObjSheet->getColumnDimension('B')->setWidth('8');
            $ObjSheet->getColumnDimension('C')->setWidth('8');
            $ObjSheet->getColumnDimension('D')->setWidth('8');

            $ObjSheet->getColumnDimension('E')->setWidth('25');
            $ObjSheet->getColumnDimension('F')->setWidth('13');
            $ObjSheet->getColumnDimension('G')->setWidth('10');

            $ObjSheet->getColumnDimension('H')->setWidth('9');
            $ObjSheet->getColumnDimension('I')->setWidth('9');
            $ObjSheet->getColumnDimension('J')->setWidth('9');
            $ObjSheet->getColumnDimension('K')->setWidth('9');
            $ObjSheet->getColumnDimension('L')->setWidth('9');
            $ObjSheet->getColumnDimension('M')->setWidth('9');
            $ObjSheet->getColumnDimension('N')->setWidth('9');
            $ObjSheet->getColumnDimension('O')->setWidth('9');
            $ObjSheet->getColumnDimension('P')->setWidth('9');
            $ObjSheet->getColumnDimension('Q')->setWidth('9');
            $ObjSheet->getColumnDimension('R')->setWidth('9');
            $ObjSheet->getColumnDimension('S')->setWidth('9');

            $ObjSheet->getColumnDimension('T')->setWidth('13');
            $ObjSheet->getColumnDimension('U')->setWidth('13');

            $ObjSheet->getRowDimension('4')->setRowHeight('32');

            // HEADER
            $ObjSheet->mergeCells('B3:D3')->setCellValue('B3', 'REAL OMSET')->getStyle('B3:D3')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('B4', '2021')->getStyle('B4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->setCellValue('C4', '2022')->getStyle('C4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->setCellValue('D4', '2023')->getStyle('D4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            $ObjSheet->mergeCells('E3:E4')->setCellValue('E3', 'AREA')->getStyle('E3:E4')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
            $ObjSheet->mergeCells('F3:F4')->setCellValue('F3', 'KATEGORI')->getStyle('F3:F4')->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('G3:G4')->setCellValue('G3', 'TARGET STANDAR')->getStyle('G3:G4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            $ObjSheet->mergeCells('H3:U3')->setCellValue('H3', 'REAL OMSET ' . $year)->getStyle('G3:T3')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

            foreach ($dataRange as $key => $detItem) {
                $ObjSheet->setCellValue($detItem . '4', $months[$key])->getStyle($detItem . '4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
            }

            $ObjSheet->setCellValue('T4', 'RT ' . $year)->getStyle('T4')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
            $ObjSheet->setCellValue('U4', '% RT2 VS TGT')->getStyle('U4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            // ISI KONTEN
            $rowIsi1 = 5;
            $gap = 4;
            foreach ($item as $itemArea) {
                $ObjSheet->mergeCells('E' . $rowIsi1 . ':E' . ($rowIsi1 + ($gap - 1)))->setCellValue('E' . $rowIsi1, $itemArea['AREA'])->getStyle('E' . $rowIsi1 . ':E' . ($rowIsi1 + ($gap - 1)))->applyFromArray($this->styling_default_template('00FFFFFF', '000000'))->getAlignment()->setWrapText(true);

                foreach ($itemArea['NON_UST'] as $key => $detData) {
                    $ObjSheet->setCellValue('F' . $rowIsi1, 'NON UST')->getStyle('F' . $rowIsi1)->applyFromArray($this->styling_title_template('00FFFF00', 'FF000000'))->getAlignment()->setHorizontal('center');
                    $ObjSheet->setCellValue('G' . $rowIsi1, 300)->getStyle('G' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                    if ($key == 'TOT_REAL') {
                        foreach ($detData as $key => $detReal) {
                            $ObjSheet->setCellValue($dataRangeReal[$key] . $rowIsi1, $detReal)->getStyle($dataRangeReal[$key] . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    } else {
                        foreach ($dataRange as $key => $detItem) {
                            $ObjSheet->setCellValue($detItem . $rowIsi1, $detData[$key])->getStyle($detItem . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    }
                    $rt_total = $ObjSheet->setCellValue('T' . $rowIsi1, '=AVERAGE(H' . $rowIsi1 . ':S' . $rowIsi1 . ')')->getStyle('T' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_total->getAlignment()->setHorizontal('center');
                    $rt_total->getNumberFormat()->setFormatCode('0.00');

                    $rt_totalvs = $ObjSheet->setCellValue('U' . $rowIsi1, '=T' . $rowIsi1 . '/G' . $rowIsi1)->getStyle('U' . $rowIsi1)->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_totalvs->getAlignment()->setHorizontal('center');
                    $rt_totalvs->getNumberFormat()->setFormatCode('0.00%');
                }

                foreach ($itemArea['UGP'] as $key => $detData) {
                    $ObjSheet->setCellValue('F' . ($rowIsi1 + 1), 'UGP')->getStyle('F' . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('00833C0C', 'FFFFFFFF'))->getAlignment()->setHorizontal('center');
                    $ObjSheet->setCellValue('G' . ($rowIsi1 + 1), 750)->getStyle('G' . ($rowIsi1 + 1))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                    if ($key == 'TOT_REAL') {
                        foreach ($detData as $key => $detReal) {
                            $ObjSheet->setCellValue($dataRangeReal[$key] . ($rowIsi1 + 1), $detReal)->getStyle($dataRangeReal[$key] . ($rowIsi1 + 1))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    } else {
                        foreach ($dataRange as $key => $detItem) {
                            $ObjSheet->setCellValue($detItem . ($rowIsi1 + 1), $detData[$key])->getStyle($detItem . ($rowIsi1 + 1))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    }
                    $rt_total = $ObjSheet->setCellValue('T' . ($rowIsi1 + 1), '=AVERAGE(H' . ($rowIsi1 + 1) . ':S' . ($rowIsi1 + 1) . ')')->getStyle('T' . ($rowIsi1 + 1))->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_total->getAlignment()->setHorizontal('center');
                    $rt_total->getNumberFormat()->setFormatCode('0.00');

                    $rt_totalvs = $ObjSheet->setCellValue('U' . ($rowIsi1 + 1), '=T' . ($rowIsi1 + 1) . '/G' . ($rowIsi1 + 1))->getStyle('U' . ($rowIsi1 + 1))->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_totalvs->getAlignment()->setHorizontal('center');
                    $rt_totalvs->getNumberFormat()->setFormatCode('0.00%');
                }

                foreach ($itemArea['URD'] as $key => $detData) {
                    $ObjSheet->setCellValue('F' . ($rowIsi1 + 2), 'URD')->getStyle('F' . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('00FF00FF', 'FFFFFFFF'))->getAlignment()->setHorizontal('center');
                    $ObjSheet->setCellValue('G' . ($rowIsi1 + 2), 750)->getStyle('G' . ($rowIsi1 + 2))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                    if ($key == 'TOT_REAL') {
                        foreach ($detData as $key => $detReal) {
                            $ObjSheet->setCellValue($dataRangeReal[$key] . ($rowIsi1 + 2), $detReal)->getStyle($dataRangeReal[$key] . ($rowIsi1 + 2))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    } else {
                        foreach ($dataRange as $key => $detItem) {
                            $ObjSheet->setCellValue($detItem . ($rowIsi1 + 2), $detData[$key])->getStyle($detItem . ($rowIsi1 + 2))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    }
                    $rt_total = $ObjSheet->setCellValue('T' . ($rowIsi1 + 2), '=AVERAGE(H' . ($rowIsi1 + 2) . ':S' . ($rowIsi1 + 2) . ')')->getStyle('T' . ($rowIsi1 + 2))->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_total->getAlignment()->setHorizontal('center');
                    $rt_total->getNumberFormat()->setFormatCode('0.00');

                    $rt_totalvs = $ObjSheet->setCellValue('U' . ($rowIsi1 + 2), '=T' . ($rowIsi1 + 2) . '/G' . ($rowIsi1 + 2))->getStyle('U' . ($rowIsi1 + 2))->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_totalvs->getAlignment()->setHorizontal('center');
                    $rt_totalvs->getNumberFormat()->setFormatCode('0.00%');
                }

                foreach ($itemArea['UST'] as $key => $detData) {
                    $ObjSheet->setCellValue('F' . ($rowIsi1 + 3), 'UST')->getStyle('F' . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setHorizontal('center');
                    $ObjSheet->setCellValue('G' . ($rowIsi1 + 3), 240)->getStyle('G' . ($rowIsi1 + 3))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                    if ($key == 'TOT_REAL') {
                        foreach ($detData as $key => $detReal) {
                            $ObjSheet->setCellValue($dataRangeReal[$key] . ($rowIsi1 + 3), $detReal)->getStyle($dataRangeReal[$key] . ($rowIsi1 + 3))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    } else {
                        foreach ($dataRange as $key => $detItem) {
                            $ObjSheet->setCellValue($detItem . ($rowIsi1 + 3), $detData[$key])->getStyle($detItem . ($rowIsi1 + 3))->applyFromArray($this->styling_default_template('00000000', 'FF000000'))->getAlignment()->setHorizontal('center');
                        }
                    }
                    $rt_total = $ObjSheet->setCellValue('T' . ($rowIsi1 + 3), '=AVERAGE(H' . ($rowIsi1 + 3) . ':S' . ($rowIsi1 + 3) . ')')->getStyle('T' . ($rowIsi1 + 3))->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_total->getAlignment()->setHorizontal('center');
                    $rt_total->getNumberFormat()->setFormatCode('0.00');

                    $rt_totalvs = $ObjSheet->setCellValue('U' . ($rowIsi1 + 3), '=T' . ($rowIsi1 + 3) . '/G' . ($rowIsi1 + 3))->getStyle('U' . ($rowIsi1 + 3))->applyFromArray($this->styling_default_template('00000000', 'FF000000'));
                    $rt_totalvs->getAlignment()->setHorizontal('center');
                    $rt_totalvs->getNumberFormat()->setFormatCode('0.00%');
                }

                $rowIsi1 += $gap;
            }

            // FOOTER
            $rowIsi2 = $rowIsi1;
            $gap = 4;
            $nonust = [];
            $ugp = [];
            $urd = [];
            $ust = [];
            foreach ($item as $key => $itemArea) {
                $nonust[$key] = $rowIsi2 - $gap;
                $ugp[$key] = $rowIsi2 - ($gap - 1);
                $urd[$key] = $rowIsi2 - ($gap - 2);
                $ust[$key] = $rowIsi2 - ($gap - 3);
                $gap += 4;
            }
            
            foreach($dataRangeReal as $columnID){
                $ObjSheet->setCellValue($columnID . $rowIsi1, '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $nonust)) . ')')->getStyle($columnID . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue($columnID . ($rowIsi1 + 1), '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $ugp)) . ')')->getStyle($columnID . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue($columnID . ($rowIsi1 + 2), '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $urd)) . ')')->getStyle($columnID . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue($columnID . ($rowIsi1 + 3), '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $ust)) . ')')->getStyle($columnID . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            }

            $ObjSheet->mergeCells('E' . $rowIsi1 . ':E' . ($rowIsi1 + 3))->setCellValue('E' . $rowIsi1, $keyMain)->getStyle('E' . $rowIsi1 . ':E' . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('left')->setWrapText(true);

            $ObjSheet->setCellValue('F' . $rowIsi1, 'NON UST')->getStyle('F' . $rowIsi1)->applyFromArray($this->styling_title_template('00FFFF00', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('F' . ($rowIsi1 + 1), 'UGP')->getStyle('F' . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('00833C0C', 'FFFFFFFF'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('F' . ($rowIsi1 + 2), 'URD')->getStyle('F' . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('00FF00FF', 'FFFFFFFF'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('F' . ($rowIsi1 + 3), 'UST')->getStyle('F' . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setHorizontal('center');

            $ObjSheet->setCellValue('G' . $rowIsi1, '=SUM(' . implode(',', array_map(function ($item) {return 'G' . $item;}, $nonust)) . ')')->getStyle('G' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('G' . ($rowIsi1 + 1), '=SUM(' . implode(',', array_map(function ($item) {return 'G' . $item;}, $ugp)) . ')')->getStyle('G' . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('G' . ($rowIsi1 + 2), '=SUM(' . implode(',', array_map(function ($item) {return 'G' . $item;}, $urd)) . ')')->getStyle('G' . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            $ObjSheet->setCellValue('G' . ($rowIsi1 + 3), '=SUM(' . implode(',', array_map(function ($item) {return 'G' . $item;}, $ust)) . ')')->getStyle('G' . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            
            foreach($dataRange as $columnID){
                $ObjSheet->setCellValue($columnID . $rowIsi1, '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $nonust)) . ')')->getStyle($columnID . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue($columnID . ($rowIsi1 + 1), '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $ugp)) . ')')->getStyle($columnID . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue($columnID . ($rowIsi1 + 2), '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $urd)) . ')')->getStyle($columnID . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue($columnID . ($rowIsi1 + 3), '=SUM(' . implode(',', array_map(function ($item) use ($columnID) {return $columnID . $item;}, $ust)) . ')')->getStyle($columnID . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
            }
            
            $rt_total_nonust = $ObjSheet->setCellValue('T' . $rowIsi1, '=SUM(' . implode(',', array_map(function ($item) {return 'T' . $item;}, $nonust)) . ')')->getStyle('T' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_nonust->getAlignment()->setHorizontal('center');
            $rt_total_nonust->getNumberFormat()->setFormatCode('0.00');
            $rt_total_ugp = $ObjSheet->setCellValue('T' . ($rowIsi1 + 1), '=SUM(' . implode(',', array_map(function ($item) {return 'T' . $item;}, $ugp)) . ')')->getStyle('T' . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_ugp->getAlignment()->setHorizontal('center');
            $rt_total_ugp->getNumberFormat()->setFormatCode('0.00');
            $rt_total_urd = $ObjSheet->setCellValue('T' . ($rowIsi1 + 2), '=SUM(' . implode(',', array_map(function ($item) {return 'T' . $item;}, $urd)) . ')')->getStyle('T' . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_urd->getAlignment()->setHorizontal('center');
            $rt_total_urd->getNumberFormat()->setFormatCode('0.00');
            $rt_total_ust = $ObjSheet->setCellValue('T' . ($rowIsi1 + 3), '=SUM(' . implode(',', array_map(function ($item) {return 'T' . $item;}, $ust)) . ')')->getStyle('T' . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_ust->getAlignment()->setHorizontal('center');
            $rt_total_ust->getNumberFormat()->setFormatCode('0.00');

            $rt_total_nonust = $ObjSheet->setCellValue('U' . $rowIsi1, '=SUM(' . implode(',', array_map(function ($item) {return 'U' . $item;}, $nonust)) . ')')->getStyle('U' . $rowIsi1)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_nonust->getAlignment()->setHorizontal('center');
            $rt_total_nonust->getNumberFormat()->setFormatCode('0.00%');            
            $rt_total_ugp = $ObjSheet->setCellValue('U' . ($rowIsi1 + 1), '=SUM(' . implode(',', array_map(function ($item) {return 'U' . $item;}, $ugp)) . ')')->getStyle('U' . ($rowIsi1 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_ugp->getAlignment()->setHorizontal('center');
            $rt_total_ugp->getNumberFormat()->setFormatCode('0.00%');
            $rt_total_urd = $ObjSheet->setCellValue('U' . ($rowIsi1 + 2), '=SUM(' . implode(',', array_map(function ($item) {return 'U' . $item;}, $urd)) . ')')->getStyle('U' . ($rowIsi1 + 2))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_urd->getAlignment()->setHorizontal('center');
            $rt_total_urd->getNumberFormat()->setFormatCode('0.00%');
            $rt_total_ust = $ObjSheet->setCellValue('U' . ($rowIsi1 + 3), '=SUM(' . implode(',', array_map(function ($item) {return 'U' . $item;}, $ust)) . ')')->getStyle('U' . ($rowIsi1 + 3))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $rt_total_ust->getAlignment()->setHorizontal('center');
            $rt_total_ust->getNumberFormat()->setFormatCode('0.00%');
        }

        $spreadsheet->removeSheetByIndex(0);
        $fileName = 'Performance REKAP APO ' . $year;

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

    public function CustomRange($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->CustomRange($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }
}
