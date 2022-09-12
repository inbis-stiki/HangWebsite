<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportTrend
{ 
    protected $dataTrendASMEN = array(
        [
            "NAME_AREA" => "MALANG 1",
            "TARGET" => "24",
            "TYPE" => "NONUST",
            "OMSET" => ["25", "20", "23", "27", "29", "17", "20", "25", "0", "21", "19", "17"]
        ],
        [
            "NAME_AREA" => "MALANG 2",
            "TARGET" => "24",
            "TYPE" => "UST",
            "OMSET" => ["25", "20", "23", "27", "29", "17", "20", "25", "0", "21", "19", "17"]
        ]
    );

    protected $dataTrendRPO = array(
        [
            "NAME_AREA" => "SURABAYA 1",
            "TARGET" => "24",
            "TYPE" => "NONUST",
            "OMSET" => ["25", "20", "23", "27", "29", "17", "20", "25", "0", "21", "19", "17"]
        ],
        [
            "NAME_AREA" => "SURABAYA 2",
            "TARGET" => "24",
            "TYPE" => "UST",
            "OMSET" => ["25", "20", "23", "27", "29", "17", "20", "25", "0", "21", "19", "17"]
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

    public function generate_trend_asmen()
    {
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("Trend ASMEN");

        $ObjSheet->getColumnDimension('B')->setWidth('5');
        $ObjSheet->getColumnDimension('C')->setWidth('25');
        $ObjSheet->getColumnDimension('D')->setWidth('18');

        $ObjSheet->getColumnDimension('E')->setWidth('10');
        $ObjSheet->getColumnDimension('F')->setWidth('10');
        $ObjSheet->getColumnDimension('G')->setWidth('10');
        $ObjSheet->getColumnDimension('H')->setWidth('10');
        $ObjSheet->getColumnDimension('I')->setWidth('10');
        $ObjSheet->getColumnDimension('J')->setWidth('10');
        $ObjSheet->getColumnDimension('K')->setWidth('10');
        $ObjSheet->getColumnDimension('L')->setWidth('10');
        $ObjSheet->getColumnDimension('M')->setWidth('10');
        $ObjSheet->getColumnDimension('N')->setWidth('10');
        $ObjSheet->getColumnDimension('O')->setWidth('10');
        $ObjSheet->getColumnDimension('P')->setWidth('10');

        $DataUST = array();
        $DataNONUST = array();
        $DataSELERAKU = array();
        for ($i = 0; $i < count($this->dataTrendASMEN); $i++) {
            if ($this->dataTrendASMEN[$i]['TYPE'] == "UST") {
                array_push($DataUST, $this->dataTrendASMEN[$i]);
            } else if ($this->dataTrendASMEN[$i]['TYPE'] == "NON UST") {
                array_push($DataNONUST, $this->dataTrendASMEN[$i]);
            } else {
                array_push($DataSELERAKU, $this->dataTrendASMEN[$i]);
            }
        }

        // UST
        $ObjSheet->mergeCells('B2:P2')->setCellValue('B2', "UST")->getStyle('B2:P2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('B3:B5')->setCellValue('B3', "NO")->getStyle('B3:B5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C3:C5')->setCellValue('C3', "AREA")->getStyle('C3:C5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('D3:D5')->setCellValue('D3', "TGT")->getStyle('D3:D5')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('E3:P4')->setCellValue('E3', "REALISASI OMSET")->getStyle('E3:P4')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->setCellValue('E5', 'JAN')->getStyle('E5')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('F5', 'FEB')->getStyle('F5')->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('G5', 'MAR')->getStyle('G5')->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('H5', 'APR')->getStyle('H5')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('I5', 'MEI')->getStyle('I5')->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('J5', 'JUN')->getStyle('J5')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('K5', 'JUL')->getStyle('K5')->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('L5', 'AUG')->getStyle('L5')->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('M5', 'SEP')->getStyle('M5')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('N5', 'OKT')->getStyle('N5')->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('O5', 'NOV')->getStyle('O5')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('P5', 'DES')->getStyle('P5')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $rowStart = 6;
        for ($i = 0; $i < count($DataUST); $i++) {
            $ObjSheet->setCellValue('B' . $rowStart, ($i + 1))->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart, $DataUST[$i]['NAME_AREA'])->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart, $DataUST[$i]['TARGET'])->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart, $DataUST[$i]['OMSET'][0])->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart, $DataUST[$i]['OMSET'][1])->getStyle('F' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart, $DataUST[$i]['OMSET'][2])->getStyle('G' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart, $DataUST[$i]['OMSET'][3])->getStyle('H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart, $DataUST[$i]['OMSET'][4])->getStyle('I' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart, $DataUST[$i]['OMSET'][5])->getStyle('J' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart, $DataUST[$i]['OMSET'][6])->getStyle('K' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart, $DataUST[$i]['OMSET'][7])->getStyle('L' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart, $DataUST[$i]['OMSET'][8])->getStyle('M' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart, $DataUST[$i]['OMSET'][9])->getStyle('N' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart, $DataUST[$i]['OMSET'][10])->getStyle('O' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart, $DataUST[$i]['OMSET'][11])->getStyle('P' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart++;
        }

        // NON UST
        $rowStart2 = $rowStart + 2;
        $ObjSheet->mergeCells('B' . $rowStart2 . ':P' . $rowStart2)->setCellValue('B' . $rowStart2, "NON UST")->getStyle('B' . $rowStart2 . ':P' . $rowStart2)->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->setCellValue('B' . ($rowStart2 + 1), "NO")->getStyle('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->setCellValue('C' . ($rowStart2 + 1), "AREA")->getStyle('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart2 + 1) . ':D' . ($rowStart2 + 3))->setCellValue('D' . ($rowStart2 + 1), "TGT")->getStyle('D' . ($rowStart2 + 1) . ':D' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('E' . ($rowStart2 + 1) . ':P' . ($rowStart2 + 2))->setCellValue('E' . ($rowStart2 + 1), "REALISASI OMSET")->getStyle('E' . ($rowStart2 + 1) . ':P' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->setCellValue('E' . ($rowStart2 + 3), 'JAN')->getStyle('E' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('F' . ($rowStart2 + 3), 'FEB')->getStyle('F' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('G' . ($rowStart2 + 3), 'MAR')->getStyle('G' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart2 + 3), 'APR')->getStyle('H' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart2 + 3), 'MEI')->getStyle('I' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('J' . ($rowStart2 + 3), 'JUN')->getStyle('J' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart2 + 3), 'JUL')->getStyle('K' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart2 + 3), 'AUG')->getStyle('L' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('M' . ($rowStart2 + 3), 'SEP')->getStyle('M' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('N' . ($rowStart2 + 3), 'OKT')->getStyle('N' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('O' . ($rowStart2 + 3), 'NOV')->getStyle('O' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('P' . ($rowStart2 + 3), 'DES')->getStyle('P' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $rowStart3 = ($rowStart2 + 4);
        for ($i = 0; $i < count($DataNONUST); $i++) {
            $ObjSheet->setCellValue('B' . $rowStart3, ($i + 1))->getStyle('B' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart3, $DataNONUST[$i]['NAME_AREA'])->getStyle('C' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart3, $DataNONUST[$i]['TARGET'])->getStyle('D' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart3, $DataNONUST[$i]['OMSET'][0])->getStyle('E' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart3, $DataNONUST[$i]['OMSET'][1])->getStyle('F' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart3, $DataNONUST[$i]['OMSET'][2])->getStyle('G' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart3, $DataNONUST[$i]['OMSET'][3])->getStyle('H' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart3, $DataNONUST[$i]['OMSET'][4])->getStyle('I' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart3, $DataNONUST[$i]['OMSET'][5])->getStyle('J' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart3, $DataNONUST[$i]['OMSET'][6])->getStyle('K' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart3, $DataNONUST[$i]['OMSET'][7])->getStyle('L' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart3, $DataNONUST[$i]['OMSET'][8])->getStyle('M' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart3, $DataNONUST[$i]['OMSET'][9])->getStyle('N' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart3, $DataNONUST[$i]['OMSET'][10])->getStyle('O' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart3, $DataNONUST[$i]['OMSET'][11])->getStyle('P' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart3++;
        }

        // SELERAKU
        $rowStart4 = $rowStart3 + 2;
        $ObjSheet->mergeCells('B' . $rowStart4 . ':P' . $rowStart4)->setCellValue('B' . $rowStart4, "SELERAKU")->getStyle('B' . $rowStart4 . ':P' . $rowStart4)->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));
        $ObjSheet->mergeCells('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->setCellValue('B' . ($rowStart4 + 1), "NO")->getStyle('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->setCellValue('C' . ($rowStart4 + 1), "AREA")->getStyle('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart4 + 1) . ':D' . ($rowStart4 + 3))->setCellValue('D' . ($rowStart4 + 1), "TGT")->getStyle('D' . ($rowStart4 + 1) . ':D' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('E' . ($rowStart4 + 1) . ':P' . ($rowStart4 + 2))->setCellValue('E' . ($rowStart4 + 1), "REALISASI OMSET")->getStyle('E' . ($rowStart4 + 1) . ':P' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->setCellValue('E' . ($rowStart4 + 3), 'JAN')->getStyle('E' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('F' . ($rowStart4 + 3), 'FEB')->getStyle('F' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('G' . ($rowStart4 + 3), 'MAR')->getStyle('G' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart4 + 3), 'APR')->getStyle('H' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart4 + 3), 'MEI')->getStyle('I' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('J' . ($rowStart4 + 3), 'JUN')->getStyle('J' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart4 + 3), 'JUL')->getStyle('K' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart4 + 3), 'AUG')->getStyle('L' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('M' . ($rowStart4 + 3), 'SEP')->getStyle('M' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('N' . ($rowStart4 + 3), 'OKT')->getStyle('N' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('O' . ($rowStart4 + 3), 'NOV')->getStyle('O' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('P' . ($rowStart4 + 3), 'DES')->getStyle('P' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $rowStart5 = ($rowStart4 + 4);
        for ($i = 0; $i < count($DataSELERAKU); $i++) {
            $ObjSheet->setCellValue('B' . $rowStart5, ($i + 1))->getStyle('B' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart5, $DataSELERAKU[$i]['NAME_AREA'])->getStyle('C' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart5, $DataSELERAKU[$i]['TARGET'])->getStyle('D' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart5, $DataSELERAKU[$i]['OMSET'][0])->getStyle('E' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart5, $DataSELERAKU[$i]['OMSET'][1])->getStyle('F' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart5, $DataSELERAKU[$i]['OMSET'][2])->getStyle('G' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart5, $DataSELERAKU[$i]['OMSET'][3])->getStyle('H' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart5, $DataSELERAKU[$i]['OMSET'][4])->getStyle('I' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart5, $DataSELERAKU[$i]['OMSET'][5])->getStyle('J' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart5, $DataSELERAKU[$i]['OMSET'][6])->getStyle('K' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart5, $DataSELERAKU[$i]['OMSET'][7])->getStyle('L' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart5, $DataSELERAKU[$i]['OMSET'][8])->getStyle('M' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart5, $DataSELERAKU[$i]['OMSET'][9])->getStyle('N' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart5, $DataSELERAKU[$i]['OMSET'][10])->getStyle('O' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart5, $DataSELERAKU[$i]['OMSET'][11])->getStyle('P' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart5++;
        }

        $fileName = 'TREND - ASMEN - ' . date_format(date_create(date("Y-m")), 'F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
    public function generate_trend_rpo()
    {
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("Trend RPO");

        $ObjSheet->getColumnDimension('B')->setWidth('5');
        $ObjSheet->getColumnDimension('C')->setWidth('25');
        $ObjSheet->getColumnDimension('D')->setWidth('18');

        $ObjSheet->getColumnDimension('E')->setWidth('10');
        $ObjSheet->getColumnDimension('F')->setWidth('10');
        $ObjSheet->getColumnDimension('G')->setWidth('10');
        $ObjSheet->getColumnDimension('H')->setWidth('10');
        $ObjSheet->getColumnDimension('I')->setWidth('10');
        $ObjSheet->getColumnDimension('J')->setWidth('10');
        $ObjSheet->getColumnDimension('K')->setWidth('10');
        $ObjSheet->getColumnDimension('L')->setWidth('10');
        $ObjSheet->getColumnDimension('M')->setWidth('10');
        $ObjSheet->getColumnDimension('N')->setWidth('10');
        $ObjSheet->getColumnDimension('O')->setWidth('10');
        $ObjSheet->getColumnDimension('P')->setWidth('10');

        $DataUST = array();
        $DataNONUST = array();
        $DataSELERAKU = array();
        for ($i = 0; $i < count($this->dataTrendRPO); $i++) {
            if ($this->dataTrendRPO[$i]['TYPE'] == "UST") {
                array_push($DataUST, $this->dataTrendRPO[$i]);
            } else if ($this->dataTrendRPO[$i]['TYPE'] == "NON UST") {
                array_push($DataNONUST, $this->dataTrendRPO[$i]);
            } else {
                array_push($DataSELERAKU, $this->dataTrendRPO[$i]);
            }
        }

        // UST
        $ObjSheet->mergeCells('B2:P2')->setCellValue('B2', "UST")->getStyle('B2:P2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('B3:B5')->setCellValue('B3', "NO")->getStyle('B3:B5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C3:C5')->setCellValue('C3', "AREA")->getStyle('C3:C5')->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('D3:D5')->setCellValue('D3', "TGT")->getStyle('D3:D5')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('E3:P4')->setCellValue('E3', "REALISASI OMSET")->getStyle('E3:P4')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->setCellValue('E5', 'JAN')->getStyle('E5')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('F5', 'FEB')->getStyle('F5')->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('G5', 'MAR')->getStyle('G5')->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('H5', 'APR')->getStyle('H5')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('I5', 'MEI')->getStyle('I5')->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('J5', 'JUN')->getStyle('J5')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('K5', 'JUL')->getStyle('K5')->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('L5', 'AUG')->getStyle('L5')->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('M5', 'SEP')->getStyle('M5')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('N5', 'OKT')->getStyle('N5')->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('O5', 'NOV')->getStyle('O5')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('P5', 'DES')->getStyle('P5')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $rowStart = 6;
        for ($i = 0; $i < count($DataUST); $i++) {
            $ObjSheet->setCellValue('B' . $rowStart, ($i + 1))->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart, $DataUST[$i]['NAME_AREA'])->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart, $DataUST[$i]['TARGET'])->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart, $DataUST[$i]['OMSET'][0])->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart, $DataUST[$i]['OMSET'][1])->getStyle('F' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart, $DataUST[$i]['OMSET'][2])->getStyle('G' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart, $DataUST[$i]['OMSET'][3])->getStyle('H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart, $DataUST[$i]['OMSET'][4])->getStyle('I' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart, $DataUST[$i]['OMSET'][5])->getStyle('J' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart, $DataUST[$i]['OMSET'][6])->getStyle('K' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart, $DataUST[$i]['OMSET'][7])->getStyle('L' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart, $DataUST[$i]['OMSET'][8])->getStyle('M' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart, $DataUST[$i]['OMSET'][9])->getStyle('N' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart, $DataUST[$i]['OMSET'][10])->getStyle('O' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart, $DataUST[$i]['OMSET'][11])->getStyle('P' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart++;
        }

        // NON UST
        $rowStart2 = $rowStart + 2;
        $ObjSheet->mergeCells('B' . $rowStart2 . ':P' . $rowStart2)->setCellValue('B' . $rowStart2, "NON UST")->getStyle('B' . $rowStart2 . ':P' . $rowStart2)->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->setCellValue('B' . ($rowStart2 + 1), "NO")->getStyle('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->setCellValue('C' . ($rowStart2 + 1), "AREA")->getStyle('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart2 + 1) . ':D' . ($rowStart2 + 3))->setCellValue('D' . ($rowStart2 + 1), "TGT")->getStyle('D' . ($rowStart2 + 1) . ':D' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('E' . ($rowStart2 + 1) . ':P' . ($rowStart2 + 2))->setCellValue('E' . ($rowStart2 + 1), "REALISASI OMSET")->getStyle('E' . ($rowStart2 + 1) . ':P' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->setCellValue('E' . ($rowStart2 + 3), 'JAN')->getStyle('E' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('F' . ($rowStart2 + 3), 'FEB')->getStyle('F' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('G' . ($rowStart2 + 3), 'MAR')->getStyle('G' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart2 + 3), 'APR')->getStyle('H' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart2 + 3), 'MEI')->getStyle('I' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('J' . ($rowStart2 + 3), 'JUN')->getStyle('J' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart2 + 3), 'JUL')->getStyle('K' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart2 + 3), 'AUG')->getStyle('L' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('M' . ($rowStart2 + 3), 'SEP')->getStyle('M' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('N' . ($rowStart2 + 3), 'OKT')->getStyle('N' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('O' . ($rowStart2 + 3), 'NOV')->getStyle('O' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('P' . ($rowStart2 + 3), 'DES')->getStyle('P' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $rowStart3 = ($rowStart2 + 4);
        for ($i = 0; $i < count($DataNONUST); $i++) {
            $ObjSheet->setCellValue('B' . $rowStart3, ($i + 1))->getStyle('B' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart3, $DataNONUST[$i]['NAME_AREA'])->getStyle('C' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart3, $DataNONUST[$i]['TARGET'])->getStyle('D' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart3, $DataNONUST[$i]['OMSET'][0])->getStyle('E' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart3, $DataNONUST[$i]['OMSET'][1])->getStyle('F' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart3, $DataNONUST[$i]['OMSET'][2])->getStyle('G' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart3, $DataNONUST[$i]['OMSET'][3])->getStyle('H' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart3, $DataNONUST[$i]['OMSET'][4])->getStyle('I' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart3, $DataNONUST[$i]['OMSET'][5])->getStyle('J' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart3, $DataNONUST[$i]['OMSET'][6])->getStyle('K' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart3, $DataNONUST[$i]['OMSET'][7])->getStyle('L' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart3, $DataNONUST[$i]['OMSET'][8])->getStyle('M' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart3, $DataNONUST[$i]['OMSET'][9])->getStyle('N' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart3, $DataNONUST[$i]['OMSET'][10])->getStyle('O' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart3, $DataNONUST[$i]['OMSET'][11])->getStyle('P' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart3++;
        }

        // SELERAKU
        $rowStart4 = $rowStart3 + 2;
        $ObjSheet->mergeCells('B' . $rowStart4 . ':P' . $rowStart4)->setCellValue('B' . $rowStart4, "SELERAKU")->getStyle('B' . $rowStart4 . ':P' . $rowStart4)->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));
        $ObjSheet->mergeCells('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->setCellValue('B' . ($rowStart4 + 1), "NO")->getStyle('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->setCellValue('C' . ($rowStart4 + 1), "AREA")->getStyle('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF66FF33', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart4 + 1) . ':D' . ($rowStart4 + 3))->setCellValue('D' . ($rowStart4 + 1), "TGT")->getStyle('D' . ($rowStart4 + 1) . ':D' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('E' . ($rowStart4 + 1) . ':P' . ($rowStart4 + 2))->setCellValue('E' . ($rowStart4 + 1), "REALISASI OMSET")->getStyle('E' . ($rowStart4 + 1) . ':P' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->setCellValue('E' . ($rowStart4 + 3), 'JAN')->getStyle('E' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('F' . ($rowStart4 + 3), 'FEB')->getStyle('F' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('G' . ($rowStart4 + 3), 'MAR')->getStyle('G' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart4 + 3), 'APR')->getStyle('H' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart4 + 3), 'MEI')->getStyle('I' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('J' . ($rowStart4 + 3), 'JUN')->getStyle('J' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart4 + 3), 'JUL')->getStyle('K' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart4 + 3), 'AUG')->getStyle('L' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('M' . ($rowStart4 + 3), 'SEP')->getStyle('M' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('N' . ($rowStart4 + 3), 'OKT')->getStyle('N' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('O' . ($rowStart4 + 3), 'NOV')->getStyle('O' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('P' . ($rowStart4 + 3), 'DES')->getStyle('P' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $rowStart5 = ($rowStart4 + 4);
        for ($i = 0; $i < count($DataSELERAKU); $i++) {
            $ObjSheet->setCellValue('B' . $rowStart5, ($i + 1))->getStyle('B' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart5, $DataSELERAKU[$i]['NAME_AREA'])->getStyle('C' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart5, $DataSELERAKU[$i]['TARGET'])->getStyle('D' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart5, $DataSELERAKU[$i]['OMSET'][0])->getStyle('E' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart5, $DataSELERAKU[$i]['OMSET'][1])->getStyle('F' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart5, $DataSELERAKU[$i]['OMSET'][2])->getStyle('G' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart5, $DataSELERAKU[$i]['OMSET'][3])->getStyle('H' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart5, $DataSELERAKU[$i]['OMSET'][4])->getStyle('I' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart5, $DataSELERAKU[$i]['OMSET'][5])->getStyle('J' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart5, $DataSELERAKU[$i]['OMSET'][6])->getStyle('K' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart5, $DataSELERAKU[$i]['OMSET'][7])->getStyle('L' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart5, $DataSELERAKU[$i]['OMSET'][8])->getStyle('M' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart5, $DataSELERAKU[$i]['OMSET'][9])->getStyle('N' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart5, $DataSELERAKU[$i]['OMSET'][10])->getStyle('O' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart5, $DataSELERAKU[$i]['OMSET'][11])->getStyle('P' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart5++;
        }

        $fileName = 'TREND - RPO - ' . date_format(date_create(date("Y-m")), 'F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
