<?php

namespace App;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportPresence
{
    public function generateMonthly($presences, $totDate, $sundays, $year, $month)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        foreach ($presences as $presence) {
            # code...
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($countSheet++);
            $ObjSheet = $spreadsheet->getActiveSheet()->setTitle($presence['NAME_REGIONAL']);

            $ObjSheet->getColumnDimension('A')->setWidth(10);
            $ObjSheet->getColumnDimension('B')->setWidth(25);
            $ObjSheet->getColumnDimension('C')->setWidth(10);
            $ObjSheet->getColumnDimension('D')->setWidth(25);
            $ObjSheet->getColumnDimension('AJ')->setWidth(25);
            $ObjSheet->getColumnDimension('AL')->setWidth(15);
            $ObjSheet->getColumnDimension('AM')->setWidth(18);
            $ObjSheet->getColumnDimension('AN')->setWidth(15);
            $ObjSheet->getColumnDimension('AO')->setWidth(15);
            $ObjSheet->getColumnDimension('AK')->setWidth(15);
            $ObjSheet->getColumnDimension('AP')->setWidth(25);

            // TITLE MONITORING
            $ObjSheet->mergeCells('A2:AP2')->setCellValue('A2', 'REKAP ABSENSI')->getStyle('A2:AP2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A3:AP3')->setCellValue('A3', 'BULAN ' . strtoupper($month) . ' ' . $year)->getStyle('A3:AP3')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));

            // HEADER
            $ObjSheet->mergeCells('A4:A6')->setCellValue('A4', 'NO')->getStyle('A4:A6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B4:B6')->setCellValue('B4', 'NAMA')->getStyle('B4:B6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C4:C6')->setCellValue('C4', 'JABATAN')->getStyle('C4:C6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D4:D6')->setCellValue('D4', 'AREA')->getStyle('D4:D6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('E4:AI5')->setCellValue('E4', 'BULAN ' . strtoupper($month) . ' TAHUN ' . $year)->getStyle('E4:AI5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));

            $abjad = "E";
            foreach (range(1, 31) as $i) {
                $fillColor = "fcd5b5";
                $txtColor  = "000000";
                if (!empty($sundays[$i])) {
                    $fillColor = "c12807";
                    $txtColor  = "ffffff";
                } else if ($i > (int)$totDate) {
                    $fillColor = "535c68";
                    $txtColor  = "000000";
                }

                $ObjSheet->setCellValue($abjad . '6', $i)->getStyle($abjad . '6')->applyFromArray($this->styling_title_template($fillColor, $txtColor));
                $abjad++;
            }

            $ObjSheet->mergeCells('AJ4:AJ6')->setCellValue('AJ4', 'HARI KERJA EFEKTIF')->getStyle('AJ4:AJ6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('AK4:AK6')->setCellValue('AK4', 'ABSENSI')->getStyle('AK4:AK6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('AL4:AO4')->setCellValue('AL4', 'TUNJANGAN TERKAIT JABATAN / POSISI KERJA')->getStyle('AL4:AO4')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('AL5:AO5')->setCellValue('AL5', 'Perhitungan Bulanan')->getStyle('AL5:AO5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->setCellValue('AL6', 'Gaji Pokok')->getStyle('AL6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->setCellValue('AM6', 'Tunj. Kesehatan')->getStyle('AM6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->setCellValue('AN6', 'Tunj. Pulsa')->getStyle('AN6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->setCellValue('AO6', 'Gaji Diterima')->getStyle('AO6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('AP4:AP6')->setCellValue('AP4', 'KETERANGAN TAMBAHAN')->getStyle('AP4:AP6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));

            // ISI KONTEN
            $row = 7;
            $no = 1;
            foreach ($presence['PRESENCES'] as $data) {
                $temp = (array)$data;
                $ObjSheet->setCellValue('A' . $row, $no++)->getStyle('A' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B' . $row, $data->NAME_USER)->getStyle('B' . $row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C' . $row, $data->NAME_ROLE)->getStyle('C' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D' . $row, $data->NAME_AREA)->getStyle('D' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $abjad = "E";
                $absent = 0;

                for ($i = 1; $i <= 31; $i++) {
                    $fillColor = "ffffff";
                    $txtColor  = "000000";
                    if (!empty($sundays[$i])) {
                        $fillColor = "c12807";
                        $txtColor  = "ffffff";
                    } else if ($i > (int)$totDate) {
                        $fillColor = "535c68";
                        $txtColor  = "000000";
                    }

                    if (!empty($temp["TGL" . $i])) {
                        if ($temp["TGL" . $i] == "M") {
                            $ObjSheet->setCellValue($abjad . '' . $row, 'V')->getStyle($abjad . '' . $row)->applyFromArray($this->styling_default_template('11', $txtColor, $fillColor));
                            $absent++;
                        } else {
                            $ObjSheet->setCellValue($abjad . '' . $row, '-')->getStyle($abjad . '' . $row)->applyFromArray($this->styling_default_template('11', $txtColor, $fillColor));
                        }
                    } else {
                        $ObjSheet->setCellValue($abjad . '' . $row, '')->getStyle($abjad . '' . $row)->applyFromArray($this->styling_default_template('11', $txtColor, $fillColor));
                    }

                    $abjad++;
                }
                $ObjSheet->setCellValue('AJ' . $row, '25')->getStyle('AJ' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AK' . $row, $absent)->getStyle('AK' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AL' . $row, '')->getStyle('AL' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AM' . $row, '')->getStyle('AM' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AN' . $row, '')->getStyle('AN' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AO' . $row, '')->getStyle('AO' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AP' . $row, '')->getStyle('AP' . $row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));

                $row++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'MONITORING PRESENSI BULAN ' . strtoupper($month) . " " . date('Y') . "_" . date('d-m-Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function generateDaily($presences, $totDate, $sundays)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        foreach ($presences as $presence) {
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($countSheet++);
            $ObjSheet = $spreadsheet->getActiveSheet()->setTitle($presence['NAME_REGIONAL']);

            // < 07.01
            $ObjSheet->getRowDimension('1')->setRowHeight(60);

            $ObjSheet->getColumnDimension('A')->setWidth(10);
            $ObjSheet->getColumnDimension('B')->setWidth(25);
            $ObjSheet->getColumnDimension('C')->setWidth(10);
            $ObjSheet->getColumnDimension('D')->setWidth(25);
            $ObjSheet->getColumnDimension('E')->setWidth(15);
            
            $ObjSheet->getColumnDimension('G')->setWidth(10);
            $ObjSheet->getColumnDimension('H')->setWidth(25);
            $ObjSheet->getColumnDimension('I')->setWidth(10);
            $ObjSheet->getColumnDimension('J')->setWidth(25);
            $ObjSheet->getColumnDimension('K')->setWidth(15);

            // HEADER
            $ObjSheet->mergeCells('A1:E1')->setCellValue('A1', $presence['NAME_REGIONAL'])->getStyle('A1:E1')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));

            $ObjSheet->mergeCells('A3:E3')->setCellValue('A3', 'PRESENSI < 07.01')->getStyle('A3:E3')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A4:A6')->setCellValue('A4', 'NO')->getStyle('A4:A6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B4:B6')->setCellValue('B4', 'NAMA')->getStyle('B4:B6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C4:C6')->setCellValue('C4', 'JABATAN')->getStyle('C4:C6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D4:D6')->setCellValue('D4', 'AREA')->getStyle('D4:D6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('E4:E6')->setCellValue('E4', 'WAKTU')->getStyle('E4:E6')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            // ISI KONTEN
            $row1 = 7;
            $no1 = 1;
            foreach ($presence['PRESENCES1'] as $data) {
                $temp = (array)$data;
                $ObjSheet->setCellValue('A' . $row1, $no1++)->getStyle('A' . $row1)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B' . $row1, $data->NAME_USER)->getStyle('B' . $row1)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C' . $row1, $data->NAME_ROLE)->getStyle('C' . $row1)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D' . $row1, $data->NAME_AREA)->getStyle('D' . $row1)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('E' . $row1, $data->TIME)->getStyle('E' . $row1)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));

                $row1++;
            }

            // 07.01 - 07.15
            $lastRow0 = ($row1 + 2);

            // HEADER
            $ObjSheet->mergeCells('A' . $lastRow0 . ':E' . $lastRow0)->setCellValue('A' . $lastRow0, 'PRESENSI 07.01 - 07.15')->getStyle('A' . $lastRow0 . ':E' . $lastRow0)->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A' . ($lastRow0 + 1) . ':A' . ($lastRow0 + 2))->setCellValue('A' . ($lastRow0 + 1), 'NO')->getStyle('A' . ($lastRow0 + 1) . ':A' . ($lastRow0 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B' . ($lastRow0 + 1) . ':B' . ($lastRow0 + 2))->setCellValue('B' . ($lastRow0 + 1), 'NAMA')->getStyle('B' . ($lastRow0 + 1) . ':B' . ($lastRow0 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C' . ($lastRow0 + 1) . ':C' . ($lastRow0 + 2))->setCellValue('C' . ($lastRow0 + 1), 'JABATAN')->getStyle('C' . ($lastRow0 + 1) . ':C' . ($lastRow0 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D' . ($lastRow0 + 1) . ':D' . ($lastRow0 + 2))->setCellValue('D' . ($lastRow0 + 1), 'AREA')->getStyle('D' . ($lastRow0 + 1) . ':D' . ($lastRow0 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('E' . ($lastRow0 + 1) . ':E' . ($lastRow0 + 2))->setCellValue('E' . ($lastRow0 + 1), 'WAKTU')->getStyle('E' . ($lastRow0 + 1) . ':E' . ($lastRow0 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            // ISI KONTEN
            $row2 = ($lastRow0 + 3);
            $no2 = 1;
            foreach ($presence['PRESENCES2'] as $data) {
                $temp = (array)$data;
                $ObjSheet->setCellValue('A' . $row2, $no2++)->getStyle('A' . $row2)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B' . $row2, $data->NAME_USER)->getStyle('B' . $row2)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C' . $row2, $data->NAME_ROLE)->getStyle('C' . $row2)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D' . $row2, $data->NAME_AREA)->getStyle('D' . $row2)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('E' . $row2, $data->TIME)->getStyle('E' . $row2)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));

                $row2++;
            }

            // 07.16 - 07.30
            $lastRow1 = ($row2 + 2);

            // HEADER
            $ObjSheet->mergeCells('A' . $lastRow1 . ':E' . $lastRow1)->setCellValue('A' . $lastRow1, 'PRESENSI 07.16 - 07.30')->getStyle('A' . $lastRow1 . ':E' . $lastRow1)->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A' . ($lastRow1 + 1) . ':A' . ($lastRow1 + 2))->setCellValue('A' . ($lastRow1 + 1) . '', 'NO')->getStyle('A' . ($lastRow1 + 1) . ':A' . ($lastRow1 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B' . ($lastRow1 + 1) . ':B' . ($lastRow1 + 2))->setCellValue('B' . ($lastRow1 + 1) . '', 'NAMA')->getStyle('B' . ($lastRow1 + 1) . ':B' . ($lastRow1 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C' . ($lastRow1 + 1) . ':C' . ($lastRow1 + 2))->setCellValue('C' . ($lastRow1 + 1) . '', 'JABATAN')->getStyle('C' . ($lastRow1 + 1) . ':C' . ($lastRow1 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D' . ($lastRow1 + 1) . ':D' . ($lastRow1 + 2))->setCellValue('D' . ($lastRow1 + 1) . '', 'AREA')->getStyle('D' . ($lastRow1 + 1) . ':D' . ($lastRow1 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('E' . ($lastRow1 + 1) . ':E' . ($lastRow1 + 2))->setCellValue('E' . ($lastRow1 + 1) . '', 'WAKTU')->getStyle('E' . ($lastRow1 + 1) . ':E' . ($lastRow1 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            // ISI KONTEN
            $row3 = ($lastRow1 + 3);
            $no3 = 1;
            foreach ($presence['PRESENCES3'] as $data) {
                $ObjSheet->setCellValue('A' . $row3, $no3++)->getStyle('A' . $row3)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B' . $row3, $data->NAME_USER)->getStyle('B' . $row3)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C' . $row3, $data->NAME_ROLE)->getStyle('C' . $row3)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D' . $row3, $data->NAME_AREA)->getStyle('D' . $row3)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('E' . $row3, $data->TIME)->getStyle('E' . $row3)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));

                $row3++;
            }

            // > 07.31
            $lastRow2 = ($row3 + 2);

            // HEADER
            $ObjSheet->mergeCells('A' . $lastRow2 . ':E' . $lastRow2)->setCellValue('A' . $lastRow2, 'PRESENSI > 07.31')->getStyle('A' . $lastRow2 . ':E' . $lastRow2)->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A' . ($lastRow2 + 1) . ':A' . ($lastRow2 + 2))->setCellValue('A' . ($lastRow2 + 1) . '', 'NO')->getStyle('A' . ($lastRow2 + 1) . ':A' . ($lastRow2 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B' . ($lastRow2 + 1) . ':B' . ($lastRow2 + 2))->setCellValue('B' . ($lastRow2 + 1) . '', 'NAMA')->getStyle('B' . ($lastRow2 + 1) . ':B' . ($lastRow2 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C' . ($lastRow2 + 1) . ':C' . ($lastRow2 + 2))->setCellValue('C' . ($lastRow2 + 1) . '', 'JABATAN')->getStyle('C' . ($lastRow2 + 1) . ':C' . ($lastRow2 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D' . ($lastRow2 + 1) . ':D' . ($lastRow2 + 2))->setCellValue('D' . ($lastRow2 + 1) . '', 'AREA')->getStyle('D' . ($lastRow2 + 1) . ':D' . ($lastRow2 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('E' . ($lastRow2 + 1) . ':E' . ($lastRow2 + 2))->setCellValue('E' . ($lastRow2 + 1) . '', 'WAKTU')->getStyle('E' . ($lastRow2 + 1) . ':E' . ($lastRow2 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            // ISI KONTEN
            $row4 = ($lastRow2 + 3);
            $no4 = 1;
            foreach ($presence['PRESENCES4'] as $data) {
                $temp = (array)$data;
                $ObjSheet->setCellValue('A' . $row4, $no4++)->getStyle('A' . $row4)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B' . $row4, $data->NAME_USER)->getStyle('B' . $row4)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C' . $row4, $data->NAME_ROLE)->getStyle('C' . $row4)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D' . $row4, $data->NAME_AREA)->getStyle('D' . $row4)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('E' . $row4, $data->TIME)->getStyle('E' . $row4)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));

                $row4++;
            }

            // IDAK PRESENSI
            $lastRow4 = ($row4 + 2);

            // HEADER
            $ObjSheet->mergeCells('A' . $lastRow4 . ':D' . $lastRow4)->setCellValue('A' . $lastRow4, 'TIDAK PRESENSI')->getStyle('A' . $lastRow4 . ':D' . $lastRow4)->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A' . ($lastRow4 + 1) . ':A' . ($lastRow4 + 2))->setCellValue('A' . ($lastRow4 + 1) . '', 'NO')->getStyle('A' . ($lastRow4 + 1) . ':A' . ($lastRow4 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B' . ($lastRow4 + 1) . ':B' . ($lastRow4 + 2))->setCellValue('B' . ($lastRow4 + 1) . '', 'NAMA')->getStyle('B' . ($lastRow4 + 1) . ':B' . ($lastRow4 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C' . ($lastRow4 + 1) . ':C' . ($lastRow4 + 2))->setCellValue('C' . ($lastRow4 + 1) . '', 'JABATAN')->getStyle('C' . ($lastRow4 + 1) . ':C' . ($lastRow4 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D' . ($lastRow4 + 1) . ':D' . ($lastRow4 + 2))->setCellValue('D' . ($lastRow4 + 1) . '', 'AREA')->getStyle('D' . ($lastRow4 + 1) . ':D' . ($lastRow4 + 2))->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            // ISI KONTEN
            $row5 = ($lastRow4 + 3);
            $no5 = 1;
            foreach ($presence['PRESENCES5'] as $data) {
                $temp = (array)$data;
                $ObjSheet->setCellValue('A' . $row5, $no5++)->getStyle('A' . $row5)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B' . $row5, $data->NAME_USER)->getStyle('B' . $row5)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C' . $row5, $data->NAME_ROLE)->getStyle('C' . $row5)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D' . $row5, $data->NAME_AREA)->getStyle('D' . $row5)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));

                $row5++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'MONITORING PRESENSI TANGGAL ' . date('d-m-Y');

        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');

        $writer = IOFactory::createWriter($spreadsheet,'Mpdf');
        $writer->writeAllSheets();
        header('Content-Disposition: attachment;filename="' . $fileName . '.pdf"');

        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function styling_default_template($FontSize, $ColorText, $fill)
    {
        $styleDefault['fill']['fillType']                     = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
        $styleDefault['fill']['color']['argb']                = $fill;
        $styleDefault['font']['bold']                         = true;
        $styleDefault['font']['size']                         = $FontSize;
        $styleDefault['font']['color']['argb']                = $ColorText;
        $styleDefault['borders']['outline']['borderStyle']    = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $styleDefault['alignment']['vertical']                = \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER;
        $styleDefault['alignment']['horizontal']              = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;

        return $styleDefault;
    }
    public function styling_default_template_left($FontSize, $ColorText)
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
