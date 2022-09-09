<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportExcell
{
    protected $dataPencapaian = array(
        [
            "NAME_USER" => "Zidan",
            "NAME_AREA" => "MALANG 2",
            "TARGET_UST" => 9,
            "REAL_UST" => 8,
            "VSTARGET_UST" => 88.889,
            "TARGET_NONUST" => 13,
            "REAL_NONUST" => 53,
            "VSTARGET_NONUST" => 407.692,
            "TARGET_SELERAKU" => 6,
            "REAL_SELERAKU" => 12,
            "VSTARGET_SELERAKU" => 200,
            "AVERAGE" => 1.77885,
            "ID_USER_RANKSALE" => 1
        ]
    );

    protected $dataPencapaianAsmen = array(
        [
            "NAME_USER" => "Zidan",
            "NAME_AREA" => "MALANG 2",
            "TARGET_UST" => 9,
            "REAL_UST" => 8,
            "VSTARGET_UST" => 88.889,
            "TARGET_NONUST" => 13,
            "REAL_NONUST" => 53,
            "VSTARGET_NONUST" => 407.692,
            "TARGET_SELERAKU" => 6,
            "REAL_SELERAKU" => 12,
            "VSTARGET_SELERAKU" => 200,
            "AVERAGE" => 1.77885,
            "ID_USER_RANKSALE" => 1
        ]
    );

    protected $dataPencapaianApoSPG = array(
        [
            "NAME_USER" => "Imanda",
            "NAME_AREA" => "MALANG 2",
            "TARGET_UST" => 9,
            "REAL_UST" => 8,
            "VSTARGET_UST" => 88.889,
            "TARGET_NONUST" => 13,
            "REAL_NONUST" => 53,
            "VSTARGET_NONUST" => 407.692,
            "TARGET_SELERAKU" => 6,
            "REAL_SELERAKU" => 12,
            "VSTARGET_SELERAKU" => 200,
            "AVERAGE" => 1.77885,
            "ID_USER_RANKSALE" => 1
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

    public function generate_ranking_rpo()
    {

        $jmlRPODapul = count($this->dataPencapaian);
        $jmlRPOLapul = count($this->dataPencapaian);

        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->createSheet(0);
        $ObjSheet->setTitle("RANGKING RPO");

        $ObjSheet->getColumnDimension('B')->setWidth('25');
        $ObjSheet->getColumnDimension('C')->setWidth('25');

        $ObjSheet->getColumnDimension('D')->setWidth('8');
        $ObjSheet->getColumnDimension('E')->setWidth('8');
        $ObjSheet->getColumnDimension('F')->setWidth('8');
        $ObjSheet->getColumnDimension('G')->setWidth('8');
        $ObjSheet->getColumnDimension('H')->setWidth('8');
        $ObjSheet->getColumnDimension('I')->setWidth('8');
        $ObjSheet->getColumnDimension('J')->setWidth('8');
        $ObjSheet->getColumnDimension('K')->setWidth('8');
        $ObjSheet->getColumnDimension('L')->setWidth('8');

        $ObjSheet->getColumnDimension('M')->setWidth('12');
        $ObjSheet->getColumnDimension('N')->setWidth('12');
        $ObjSheet->getColumnDimension('O')->setWidth('2');
        $ObjSheet->getColumnDimension('P')->setWidth('10');
        $ObjSheet->getColumnDimension('Q')->setWidth('10');

        $ObjSheet->getRowDimension('4')->setRowHeight('20');
        $ObjSheet->getRowDimension('5')->setRowHeight('20');
        $ObjSheet->getRowDimension('6')->setRowHeight('30');

        // Activity RPO LAPUL
        $ObjSheet->mergeCells('B2:Q2')->setCellValue('B2', strtoupper(date_format(date_create(date("Y-m")), 'F Y')))->getStyle('B2:Q2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B3', 'AKTIVITY RPO DAPUL')->getStyle('B3')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F3', 'Bobot 50%')->getStyle('F3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I3', 'Bobot 25%')->getStyle('I3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L3', 'Bobot 25%')->getStyle('L3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N3:Q3')->setCellValue('N3', 'DATA PER ' . strtoupper(date_format(date_create(date("Y-m-d")), 'j F Y')))->getStyle('N3:Q3')->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B4:B6')->setCellValue('B4', 'NAMA')->getStyle('B4:B6')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C4:C6')->setCellValue('C4', 'AREA')->getStyle('C4:C6')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D4:L4')->setCellValue('D4', 'KATEGORI')->getStyle('D4:L4')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D5:F5')->setCellValue('D5', 'AKTIVITAS UB')->getStyle('D5:F5')->applyFromArray($this->styling_title_template('FFFF00FF', 'FF000000'));
        $ObjSheet->mergeCells('G5:I5')->setCellValue('G5', 'PEDAGANG SAYUR')->getStyle('G5:I5')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
        $ObjSheet->mergeCells('J5:L5')->setCellValue('J5', 'RETAIL')->getStyle('J5:L5')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));

        $ObjSheet->setCellValue('D6', 'TGT')->getStyle('D6')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E6', 'REAL')->getStyle('E6')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F6', '% VS TGT')->getStyle('F6')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G6', 'TGT')->getStyle('G6')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H6', 'REAL')->getStyle('H6')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I6', '% VS TGT')->getStyle('I6')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J6', 'TGT')->getStyle('J6')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K6', 'REAL')->getStyle('K6')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L6', '% VS TGT')->getStyle('L6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M4:M6')->setCellValue('M4', '% AVG')->getStyle('M4:M6')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N4:N6')->setCellValue('N4', 'RANK')->getStyle('N4:N6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $rowStart = 7;
        for ($i = 0; $i < $jmlRPODapul; $i++) {
            $ObjSheet->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart++;
        }

        $ObjSheet->getRowDimension($rowStart)->setRowHeight('10');

        $ObjSheet->setCellValue('B' . ($rowStart + 1), 'AVERAGE')->getStyle('B' . ($rowStart + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('B' . ($rowStart + 2), 'AVERAGE')->getStyle('B' . ($rowStart + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->setCellValue('C' . ($rowStart + 1), 'DAPUL')->getStyle('C' . ($rowStart + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('C' . ($rowStart + 2), 'NASIONAL')->getStyle('C' . ($rowStart + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));

        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $ColorFill = 'FF00FFFF';
                $ColorText = 'FF000000';
            } else {
                $ColorFill = 'FF0000FF';
                $ColorText = 'FFFFFFFF';
            }
            $ObjSheet->getStyle('D' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . ($rowStart + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . ($rowStart + $i))->applyFromArray($this->styling_title_template('FF000000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        }

        // Pencapaian RPO LAPUL
        $rowStart2 = $rowStart + 4;
        $ObjSheet->setCellValue('B' . $rowStart2, 'PENCAPAIAN RPO')->getStyle('B' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart2, 'Bobot 75%')->getStyle('F' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart2, 'Bobot 0%')->getStyle('I' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart2, 'Bobot 25%')->getStyle('L' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N' . $rowStart2 . ':Q' . $rowStart2)->setCellValue('N' . $rowStart2, 'DATA PER ' . strtoupper(date_format(date_create(date("Y-m-d")), 'j F Y')))->getStyle('N' . $rowStart2 . ':Q' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->setCellValue('B' . ($rowStart2 + 1), 'NAMA')->getStyle('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->setCellValue('C' . ($rowStart2 + 1), 'AREA')->getStyle('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart2 + 1) . ':L' . ($rowStart2 + 1))->setCellValue('D' . ($rowStart2 + 1), 'KATEGORI')->getStyle('D' . ($rowStart2 + 1) . ':L' . ($rowStart2 + 1))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart2 + 2) . ':F' . ($rowStart2 + 2))->setCellValue('D' . ($rowStart2 + 2), 'NON UST')->getStyle('D' . ($rowStart2 + 2) . ':F' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart2 + 2) . ':I' . ($rowStart2 + 2))->setCellValue('G' . ($rowStart2 + 2), 'UST')->getStyle('G' . ($rowStart2 + 2) . ':I' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart2 + 2) . ':L' . ($rowStart2 + 2))->setCellValue('J' . ($rowStart2 + 2), 'SELERAKU')->getStyle('J' . ($rowStart2 + 2) . ':L' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart2 + 3), 'TGT')->getStyle('D' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart2 + 3), 'REAL')->getStyle('E' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart2 + 3), '% VS TGT')->getStyle('F' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart2 + 3), 'TGT')->getStyle('G' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart2 + 3), 'REAL')->getStyle('H' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart2 + 3), '% VS TGT')->getStyle('I' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart2 + 3), 'TGT')->getStyle('J' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart2 + 3), 'REAL')->getStyle('K' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart2 + 3), '% VS TGT')->getStyle('L' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart2 + 1) . ':M' . ($rowStart2 + 3))->setCellValue('M' . ($rowStart2 + 1), '% AVG')->getStyle('M' . ($rowStart2 + 1) . ':M' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N' . ($rowStart2 + 1) . ':N' . ($rowStart2 + 3))->setCellValue('N' . ($rowStart2 + 1), 'RANK')->getStyle('N' . ($rowStart2 + 1) . ':N' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P' . ($rowStart2 + 2), 'UBNG')->getStyle('P' . ($rowStart2 + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P' . ($rowStart2 + 3), 'REAL')->getStyle('P' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart3 = ($rowStart2 + 4);
        for ($i = 0; $i < $jmlRPODapul; $i++) {

            $ObjSheet->setCellValue('B' . $rowStart3, $this->dataPencapaian[$i]['NAME_USER'])->getStyle('B' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart3, $this->dataPencapaian[$i]['NAME_AREA'])->getStyle('C' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart3, $this->dataPencapaian[$i]['TARGET_NONUST'])->getStyle('D' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart3, $this->dataPencapaian[$i]['REAL_NONUST'])->getStyle('E' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart3, $this->dataPencapaian[$i]['VSTARGET_NONUST'])->getStyle('F' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart3, $this->dataPencapaian[$i]['TARGET_UST'])->getStyle('G' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart3, $this->dataPencapaian[$i]['REAL_UST'])->getStyle('H' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart3, $this->dataPencapaian[$i]['VSTARGET_UST'])->getStyle('I' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart3, $this->dataPencapaian[$i]['TARGET_SELERAKU'])->getStyle('J' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart3, $this->dataPencapaian[$i]['REAL_SELERAKU'])->getStyle('K' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart3, $this->dataPencapaian[$i]['VSTARGET_SELERAKU'])->getStyle('L' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart3, $this->dataPencapaian[$i]['AVERAGE'])->getStyle('M' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart3, $this->dataPencapaian[$i]['ID_USER_RANKSALE'])->getStyle('N' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('P' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart3++;
        }

        $ObjSheet->getRowDimension($rowStart)->setRowHeight('10');

        $ObjSheet->setCellValue('B' . ($rowStart3 + 1), 'AVERAGE')->getStyle('B' . ($rowStart3 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('B' . ($rowStart3 + 2), 'AVERAGE')->getStyle('B' . ($rowStart3 + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->setCellValue('C' . ($rowStart3 + 1), 'DAPUL')->getStyle('C' . ($rowStart3 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('C' . ($rowStart3 + 2), 'NASIONAL')->getStyle('C' . ($rowStart3 + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));

        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $ColorFill = 'FF00FFFF';
                $ColorText = 'FF000000';
            } else {
                $ColorFill = 'FF0000FF';
                $ColorText = 'FFFFFFFF';
            }
            $ObjSheet->getStyle('D' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . ($rowStart3 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . ($rowStart3 + $i))->applyFromArray($this->styling_title_template('FF000000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        }

        // Activity RPO LAPUL
        $rowStart4 = $rowStart3 + 5;
        $ObjSheet->mergeCells('B' . ($rowStart4 - 1) . ':Q' . ($rowStart4 - 1))->setCellValue('B' . ($rowStart4 - 1), strtoupper(date_format(date_create(date("Y-m")), 'F Y')))->getStyle('B' . ($rowStart4 - 1) . ':Q' . ($rowStart4 - 1))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B' . $rowStart4, 'AKTIVITY RPO LAPUL')->getStyle('B' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart4, 'Bobot 50%')->getStyle('F' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart4, 'Bobot 25%')->getStyle('I' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart4, 'Bobot 25%')->getStyle('L' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N' . $rowStart4 . ':Q' . $rowStart4)->setCellValue('N' . $rowStart4, 'DATA PER ' . date_format(date_create(date("Y-m-d")), 'j F Y'))->getStyle('N' . $rowStart4 . ':Q' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->setCellValue('B' . ($rowStart4 + 1), 'NAMA')->getStyle('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->setCellValue('C' . ($rowStart4 + 1), 'AREA')->getStyle('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart4 + 1) . ':L' . ($rowStart4 + 1))->setCellValue('D' . ($rowStart4 + 1), 'KATEGORI')->getStyle('D' . ($rowStart4 + 1) . ':L' . ($rowStart4 + 1))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart4 + 2) . ':F' . ($rowStart4 + 2))->setCellValue('D' . ($rowStart4 + 2), 'NON UST')->getStyle('D' . ($rowStart4 + 2) . ':F' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart4 + 2) . ':I' . ($rowStart4 + 2))->setCellValue('G' . ($rowStart4 + 2), 'UST')->getStyle('G' . ($rowStart4 + 2) . ':I' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart4 + 2) . ':L' . ($rowStart4 + 2))->setCellValue('J' . ($rowStart4 + 2), 'SELERAKU')->getStyle('J' . ($rowStart4 + 2) . ':L' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart4 + 3), 'TGT')->getStyle('D' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart4 + 3), 'REAL')->getStyle('E' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart4 + 3), '% VS TGT')->getStyle('F' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart4 + 3), 'TGT')->getStyle('G' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart4 + 3), 'REAL')->getStyle('H' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart4 + 3), '% VS TGT')->getStyle('I' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart4 + 3), 'TGT')->getStyle('J' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart4 + 3), 'REAL')->getStyle('K' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart4 + 3), '% VS TGT')->getStyle('L' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart4 + 1) . ':M' . ($rowStart4 + 3))->setCellValue('M' . ($rowStart4 + 1), '% AVG')->getStyle('M' . ($rowStart4 + 1) . ':M' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N' . ($rowStart4 + 1) . ':N' . ($rowStart4 + 3))->setCellValue('N' . ($rowStart4 + 1), 'RANK')->getStyle('N' . ($rowStart4 + 1) . ':N' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $rowStart5 = ($rowStart4 + 4);
        for ($i = 0; $i < $jmlRPOLapul; $i++) {

            $ObjSheet->getStyle('B' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart5++;
        }

        $ObjSheet->getRowDimension($rowStart)->setRowHeight('10');

        $ObjSheet->setCellValue('B' . ($rowStart5 + 1), 'AVERAGE')->getStyle('B' . ($rowStart5 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('B' . ($rowStart5 + 2), 'AVERAGE')->getStyle('B' . ($rowStart5 + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->setCellValue('C' . ($rowStart5 + 1), 'DAPUL')->getStyle('C' . ($rowStart5 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('C' . ($rowStart5 + 2), 'NASIONAL')->getStyle('C' . ($rowStart5 + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));

        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $ColorFill = 'FF00FFFF';
                $ColorText = 'FF000000';
            } else {
                $ColorFill = 'FF0000FF';
                $ColorText = 'FFFFFFFF';
            }
            $ObjSheet->getStyle('D' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . ($rowStart5 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . ($rowStart5 + $i))->applyFromArray($this->styling_title_template('FF000000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        }

        // Pencapaian RPO LAPUL
        $rowStart6 = $rowStart5 + 4;
        $ObjSheet->setCellValue('B' . $rowStart6, 'PENCAPAIAN RPO')->getStyle('B' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart6, 'Bobot 75%')->getStyle('F' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart6, 'Bobot 0%')->getStyle('I' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart6, 'Bobot 25%')->getStyle('L' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N' . $rowStart6 . ':Q' . $rowStart6)->setCellValue('N' . $rowStart6, 'DATA PER ' . strtoupper(date_format(date_create(date("Y-m-d")), 'j F Y')))->getStyle('N' . $rowStart6 . ':Q' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B' . ($rowStart6 + 1) . ':B' . ($rowStart6 + 3))->setCellValue('B' . ($rowStart6 + 1), 'NAMA')->getStyle('B' . ($rowStart6 + 1) . ':B' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart6 + 1) . ':C' . ($rowStart6 + 3))->setCellValue('C' . ($rowStart6 + 1), 'AREA')->getStyle('C' . ($rowStart6 + 1) . ':C' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart6 + 1) . ':L' . ($rowStart6 + 1))->setCellValue('D' . ($rowStart6 + 1), 'KATEGORI')->getStyle('D' . ($rowStart6 + 1) . ':L' . ($rowStart6 + 1))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart6 + 2) . ':F' . ($rowStart6 + 2))->setCellValue('D' . ($rowStart6 + 2), 'NON UST')->getStyle('D' . ($rowStart6 + 2) . ':F' . ($rowStart6 + 2))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart6 + 2) . ':I' . ($rowStart6 + 2))->setCellValue('G' . ($rowStart6 + 2), 'UST')->getStyle('G' . ($rowStart6 + 2) . ':I' . ($rowStart6 + 2))->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart6 + 2) . ':L' . ($rowStart6 + 2))->setCellValue('J' . ($rowStart6 + 2), 'SELERAKU')->getStyle('J' . ($rowStart6 + 2) . ':L' . ($rowStart6 + 2))->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart6 + 3), 'TGT')->getStyle('D' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart6 + 3), 'REAL')->getStyle('E' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart6 + 3), '% VS TGT')->getStyle('F' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart6 + 3), 'TGT')->getStyle('G' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart6 + 3), 'REAL')->getStyle('H' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart6 + 3), '% VS TGT')->getStyle('I' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart6 + 3), 'TGT')->getStyle('J' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart6 + 3), 'REAL')->getStyle('K' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart6 + 3), '% VS TGT')->getStyle('L' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart6 + 1) . ':M' . ($rowStart6 + 3))->setCellValue('M' . ($rowStart6 + 1), '% AVG')->getStyle('M' . ($rowStart6 + 1) . ':M' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N' . ($rowStart6 + 1) . ':N' . ($rowStart6 + 3))->setCellValue('N' . ($rowStart6 + 1), 'RANK')->getStyle('N' . ($rowStart6 + 1) . ':N' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P' . ($rowStart6 + 2), 'UBNG')->getStyle('P' . ($rowStart6 + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P' . ($rowStart6 + 3), 'REAL')->getStyle('P' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart7 = ($rowStart6 + 4);
        for ($i = 0; $i < $jmlRPOLapul; $i++) {

            $ObjSheet->getStyle('B' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('P' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart7++;
        }

        $ObjSheet->getRowDimension($rowStart)->setRowHeight('10');

        $ObjSheet->setCellValue('B' . ($rowStart7 + 1), 'AVERAGE')->getStyle('B' . ($rowStart7 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('B' . ($rowStart7 + 2), 'AVERAGE')->getStyle('B' . ($rowStart7 + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->setCellValue('C' . ($rowStart7 + 1), 'DAPUL')->getStyle('C' . ($rowStart7 + 1))->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        $ObjSheet->setCellValue('C' . ($rowStart7 + 2), 'NASIONAL')->getStyle('C' . ($rowStart7 + 2))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));

        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $ColorFill = 'FF00FFFF';
                $ColorText = 'FF000000';
            } else {
                $ColorFill = 'FF0000FF';
                $ColorText = 'FFFFFFFF';
            }
            $ObjSheet->getStyle('D' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . ($rowStart7 + $i))->applyFromArray($this->styling_content_template($ColorFill, $ColorText))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . ($rowStart7 + $i))->applyFromArray($this->styling_title_template('FF000000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        }

        $fileName = 'RANKING - RPO - (REGIONAL) - ' . date_format(date_create(date("Y-m")), 'F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function generate_ranking_asmen()
    {
        $jmlAsmen = count($this->dataPencapaianAsmen);

        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->createSheet(0);
        $ObjSheet->setTitle("RANGKING ASMEN");

        $ObjSheet->getColumnDimension('B')->setWidth('25');
        $ObjSheet->getColumnDimension('C')->setWidth('25');

        $ObjSheet->getColumnDimension('D')->setWidth('8');
        $ObjSheet->getColumnDimension('E')->setWidth('8');
        $ObjSheet->getColumnDimension('F')->setWidth('8');
        $ObjSheet->getColumnDimension('G')->setWidth('8');
        $ObjSheet->getColumnDimension('H')->setWidth('8');
        $ObjSheet->getColumnDimension('I')->setWidth('8');
        $ObjSheet->getColumnDimension('J')->setWidth('8');
        $ObjSheet->getColumnDimension('K')->setWidth('8');
        $ObjSheet->getColumnDimension('L')->setWidth('8');

        $ObjSheet->getColumnDimension('M')->setWidth('12');
        $ObjSheet->getColumnDimension('N')->setWidth('12');
        $ObjSheet->getColumnDimension('O')->setWidth('2');
        $ObjSheet->getColumnDimension('P')->setWidth('10');
        $ObjSheet->getColumnDimension('Q')->setWidth('10');

        $ObjSheet->getRowDimension('4')->setRowHeight('20');
        $ObjSheet->getRowDimension('5')->setRowHeight('20');
        $ObjSheet->getRowDimension('6')->setRowHeight('30');

        // Activity Asmen
        $ObjSheet->mergeCells('B2:Q2')->setCellValue('B2', strtoupper(date_format(date_create(date("Y-m")), 'F Y')))->getStyle('B2:Q2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B3', 'AKTIVITY ASMEN')->getStyle('B3')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F3', 'Bobot 50%')->getStyle('F3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I3', 'Bobot 25%')->getStyle('I3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L3', 'Bobot 25%')->getStyle('L3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N3:Q3')->setCellValue('N3', 'DATA PER ' . strtoupper(date_format(date_create(date("Y-m-d")), 'j F Y')))->getStyle('N3:Q3')->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B4:B6')->setCellValue('B4', 'NAMA')->getStyle('B4:B6')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C4:C6')->setCellValue('C4', 'AREA')->getStyle('C4:C6')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D4:L4')->setCellValue('D4', 'KATEGORI')->getStyle('D4:L4')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D5:F5')->setCellValue('D5', 'AKTIVITAS UB')->getStyle('D5:F5')->applyFromArray($this->styling_title_template('FFFF00FF', 'FF000000'));
        $ObjSheet->mergeCells('G5:I5')->setCellValue('G5', 'PEDAGANG SAYUR')->getStyle('G5:I5')->applyFromArray($this->styling_title_template('FF66FFFF', 'FF000000'));
        $ObjSheet->mergeCells('J5:L5')->setCellValue('J5', 'RETAIL')->getStyle('J5:L5')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));

        $ObjSheet->setCellValue('D6', 'TGT')->getStyle('D6')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E6', 'REAL')->getStyle('E6')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F6', '% VS TGT')->getStyle('F6')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G6', 'TGT')->getStyle('G6')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H6', 'REAL')->getStyle('H6')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I6', '% VS TGT')->getStyle('I6')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J6', 'TGT')->getStyle('J6')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K6', 'REAL')->getStyle('K6')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L6', '% VS TGT')->getStyle('L6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M4:M6')->setCellValue('M4', '% AVG')->getStyle('M4:M6')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N4:N6')->setCellValue('N4', 'RANK')->getStyle('N4:N6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $rowStart = 7;
        for ($i = 1; $i <= $jmlAsmen; $i++) {

            $ObjSheet->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart++;
        }

        $ObjSheet->getRowDimension($rowStart)->setRowHeight('10');

        $ObjSheet->setCellValue('B' . ($rowStart + 1), 'AVERAGE')->getStyle('B' . ($rowStart + 1))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->setCellValue('C' . ($rowStart + 1), 'NASIONAL')->getStyle('C' . ($rowStart + 1))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));

        $ObjSheet->getStyle('D' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('E' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('F' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('G' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('H' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('I' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('J' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('K' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('L' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('M' . ($rowStart + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('N' . ($rowStart + 1))->applyFromArray($this->styling_title_template('FF000000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        // Pencapaian Asmen
        $rowStart2 = $rowStart + 4;
        $ObjSheet->setCellValue('B' . $rowStart2, 'PENCAPAIAN RPO')->getStyle('B' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart2, 'Bobot 75%')->getStyle('F' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart2, 'Bobot 0%')->getStyle('I' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart2, 'Bobot 25%')->getStyle('L' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N' . $rowStart2 . ':Q' . $rowStart2)->setCellValue('N' . $rowStart2, 'DATA PER ' . strtoupper(date_format(date_create(date("Y-m-d")), 'j F Y')))->getStyle('N' . $rowStart2 . ':Q' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->setCellValue('B' . ($rowStart2 + 1), 'NAMA')->getStyle('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->setCellValue('C' . ($rowStart2 + 1), 'AREA')->getStyle('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart2 + 1) . ':L' . ($rowStart2 + 1))->setCellValue('D' . ($rowStart2 + 1), 'KATEGORI')->getStyle('D' . ($rowStart2 + 1) . ':L' . ($rowStart2 + 1))->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart2 + 2) . ':F' . ($rowStart2 + 2))->setCellValue('D' . ($rowStart2 + 2), 'NON UST')->getStyle('D' . ($rowStart2 + 2) . ':F' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart2 + 2) . ':I' . ($rowStart2 + 2))->setCellValue('G' . ($rowStart2 + 2), 'UST')->getStyle('G' . ($rowStart2 + 2) . ':I' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart2 + 2) . ':L' . ($rowStart2 + 2))->setCellValue('J' . ($rowStart2 + 2), 'SELERAKU')->getStyle('J' . ($rowStart2 + 2) . ':L' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart2 + 3), 'TGT')->getStyle('D' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart2 + 3), 'REAL')->getStyle('E' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart2 + 3), '% VS TGT')->getStyle('F' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart2 + 3), 'TGT')->getStyle('G' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart2 + 3), 'REAL')->getStyle('H' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart2 + 3), '% VS TGT')->getStyle('I' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart2 + 3), 'TGT')->getStyle('J' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart2 + 3), 'REAL')->getStyle('K' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart2 + 3), '% VS TGT')->getStyle('L' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart2 + 1) . ':M' . ($rowStart2 + 3))->setCellValue('M' . ($rowStart2 + 1), '% AVG')->getStyle('M' . ($rowStart2 + 1) . ':M' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N' . ($rowStart2 + 1) . ':N' . ($rowStart2 + 3))->setCellValue('N' . ($rowStart2 + 1), 'RANK')->getStyle('N' . ($rowStart2 + 1) . ':N' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P' . ($rowStart2 + 2), 'UBNG')->getStyle('P' . ($rowStart2 + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P' . ($rowStart2 + 3), 'REAL')->getStyle('P' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart3 = ($rowStart2 + 4);
        for ($i = 0; $i < $jmlAsmen; $i++) {

            $ObjSheet->setCellValue('B' . $rowStart3, $this->dataPencapaianAsmen[$i]['NAME_USER'])->getStyle('B' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart3, $this->dataPencapaianAsmen[$i]['NAME_AREA'])->getStyle('C' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart3, $this->dataPencapaianAsmen[$i]['TARGET_NONUST'])->getStyle('D' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart3, $this->dataPencapaianAsmen[$i]['REAL_NONUST'])->getStyle('E' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart3, $this->dataPencapaianAsmen[$i]['VSTARGET_NONUST'])->getStyle('F' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart3, $this->dataPencapaianAsmen[$i]['TARGET_UST'])->getStyle('G' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart3, $this->dataPencapaianAsmen[$i]['REAL_UST'])->getStyle('H' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart3, $this->dataPencapaianAsmen[$i]['VSTARGET_UST'])->getStyle('I' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart3, $this->dataPencapaianAsmen[$i]['TARGET_SELERAKU'])->getStyle('J' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart3, $this->dataPencapaianAsmen[$i]['REAL_SELERAKU'])->getStyle('K' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart3, $this->dataPencapaianAsmen[$i]['VSTARGET_SELERAKU'])->getStyle('L' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart3, $this->dataPencapaianAsmen[$i]['AVERAGE'])->getStyle('M' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart3, $this->dataPencapaianAsmen[$i]['ID_USER_RANKSALE'])->getStyle('N' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('P' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart3++;
        }

        $ObjSheet->getRowDimension($rowStart)->setRowHeight('10');

        $ObjSheet->setCellValue('B' . ($rowStart3 + 1), 'AVERAGE')->getStyle('B' . ($rowStart3 + 1))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->setCellValue('C' . ($rowStart3 + 1), 'NASIONAL')->getStyle('C' . ($rowStart3 + 1))->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));

        $ObjSheet->getStyle('D' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('E' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('F' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('G' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('H' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('I' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('J' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('K' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('L' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('M' . ($rowStart3 + 1))->applyFromArray($this->styling_content_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('N' . ($rowStart3 + 1))->applyFromArray($this->styling_title_template('FF000000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('S17:S19')->setCellValue('S17', 'AREA')->getStyle('S17:S19')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('T17:AB17')->setCellValue('T17', 'KATEGORI')->getStyle('T17:AB17')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('T18:V18')->getStyle('T18:V18')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('W18:Y18')->getStyle('W18:Y18')->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('Z18:AB18')->getStyle('Z18:AB18')->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('T19', 'TGT')->getStyle('T19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('U19', 'REAL')->getStyle('U19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('V19', '% VS TGT')->getStyle('V19')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('W19', 'TGT')->getStyle('W19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('X19', 'REAL')->getStyle('X19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('Y19', '% VS TGT')->getStyle('Y19')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('Z19', 'TGT')->getStyle('Z19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('AA19', 'REAL')->getStyle('AA19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('AB19', '% VS TGT')->getStyle('AB19')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('S20:S21')->setCellValue('S20', 'NASIONAL')->getStyle('S20:S21')->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->getStyle('T20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('T21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        
        $ObjSheet->getStyle('U20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('U21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('V20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('V21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('W20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('W21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('X20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('X21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('Y20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('Y21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('Z20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('Z21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('AA20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('AA21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('AB20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('AB21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('AD18', 'UBNG')->getStyle('AD18')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->setCellValue('AD19', 'REAL')->getStyle('AD19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->getStyle('AD20')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->getStyle('AD21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);

        

        $fileName = 'RANKING - ASMEN - (REGIONAL) - ' . date_format(date_create(date("Y-m")), 'F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function generate_ranking_apo_spg()
    {
        $jmlAPO = count($this->dataPencapaianApoSPG);
        $jmlSPG = count($this->dataPencapaianApoSPG);

        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->createSheet(0);
        $ObjSheet->setTitle("RANGKING APO & SPG");

        $ObjSheet->getColumnDimension('B')->setWidth('25');
        $ObjSheet->getColumnDimension('C')->setWidth('25');

        $ObjSheet->getColumnDimension('D')->setWidth('8');
        $ObjSheet->getColumnDimension('E')->setWidth('8');
        $ObjSheet->getColumnDimension('F')->setWidth('8');
        $ObjSheet->getColumnDimension('G')->setWidth('8');
        $ObjSheet->getColumnDimension('H')->setWidth('8');
        $ObjSheet->getColumnDimension('I')->setWidth('8');
        $ObjSheet->getColumnDimension('J')->setWidth('8');
        $ObjSheet->getColumnDimension('K')->setWidth('8');
        $ObjSheet->getColumnDimension('L')->setWidth('8');

        $ObjSheet->getColumnDimension('M')->setWidth('12');
        $ObjSheet->getColumnDimension('N')->setWidth('12');
        $ObjSheet->getColumnDimension('O')->setWidth('2');
        $ObjSheet->getColumnDimension('P')->setWidth('10');
        $ObjSheet->getColumnDimension('Q')->setWidth('10');

        $ObjSheet->getRowDimension('5')->setRowHeight('20');
        $ObjSheet->getRowDimension('6')->setRowHeight('20');
        $ObjSheet->getRowDimension('7')->setRowHeight('30');

        // Activity APO
        $ObjSheet->mergeCells('B2:N2')->setCellValue('B2', strtoupper(date_format(date_create(date("Y-m")), 'F Y')))->getStyle('B2:N2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B4', 'AKTIVITY APO')->getStyle('B4')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F4', 'Bobot 50%')->getStyle('F4')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I4', 'Bobot 25%')->getStyle('I4')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L4', 'Bobot 25%')->getStyle('L4')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('K3:M3')->setCellValue('K3', ' RANGKING ' . strtoupper(date_format(date_create(date("Y-m")), 'F Y')))->getStyle('K3:M3')->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B5:B7')->setCellValue('B5', 'NAMA')->getStyle('B5:B7')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C5:C7')->setCellValue('C5', 'AREA')->getStyle('C5:C7')->applyFromArray($this->styling_title_template('FFC000', 'FF000000'));
        $ObjSheet->mergeCells('D5:L5')->setCellValue('D5', 'KATEGORI')->getStyle('D5:L5')->applyFromArray($this->styling_title_template('00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D6:F6')->setCellValue('D6', 'AKTIVITAS UB')->getStyle('D6:F6')->applyFromArray($this->styling_title_template('FF81FF', 'FF000000'));
        $ObjSheet->mergeCells('G6:I6')->setCellValue('G6', 'PEDAGANG SAYUR')->getStyle('G6:I6')->applyFromArray($this->styling_title_template('D8E4BC', 'FF000000'));
        $ObjSheet->mergeCells('J6:L6')->setCellValue('J6', 'RETAIL')->getStyle('J6:L6')->applyFromArray($this->styling_title_template('FFFF00', 'FF000000'));

        $ObjSheet->setCellValue('D7', 'TGT')->getStyle('D7')->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('E7', 'REAL')->getStyle('E7')->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('F7', '% VS TGT')->getStyle('F7')->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G7', 'TGT')->getStyle('G7')->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('H7', 'REAL')->getStyle('H7')->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('I7', '% VS TGT')->getStyle('I7')->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J7', 'TGT')->getStyle('J7')->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('K7', 'REAL')->getStyle('K7')->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('L7', '% VS TGT')->getStyle('L7')->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M5:M7')->setCellValue('M5', '% AVG')->getStyle('M5:M7')->applyFromArray($this->styling_title_template('92CDDC', 'FF000000'));
        $ObjSheet->mergeCells('N5:N7')->setCellValue('N5', 'RANGKING')->getStyle('N5:N7')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $rowStart = 8;
        for ($i = 0; $i < $jmlAPO; $i++) {
            $ObjSheet->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart++;
        }

        // Pencapaian APO
        $rowStart2 = $rowStart + 2;
        $ObjSheet->setCellValue('B' . $rowStart2, 'PENCAPAIAN APO')->getStyle('B' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart2, 'Bobot 75%')->getStyle('F' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart2, 'Bobot 0%')->getStyle('I' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart2, 'Bobot 25%')->getStyle('L' . $rowStart2)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));        

        $ObjSheet->mergeCells('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->setCellValue('B' . ($rowStart2 + 1), 'NAMA')->getStyle('B' . ($rowStart2 + 1) . ':B' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->setCellValue('C' . ($rowStart2 + 1), 'AREA')->getStyle('C' . ($rowStart2 + 1) . ':C' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFC000', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart2 + 1) . ':L' . ($rowStart2 + 1))->setCellValue('D' . ($rowStart2 + 1), 'KATEGORI')->getStyle('D' . ($rowStart2 + 1) . ':L' . ($rowStart2 + 1))->applyFromArray($this->styling_title_template('00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart2 + 2) . ':F' . ($rowStart2 + 2))->setCellValue('D' . ($rowStart2 + 2), 'NON UST')->getStyle('D' . ($rowStart2 + 2) . ':F' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart2 + 2) . ':I' . ($rowStart2 + 2))->setCellValue('G' . ($rowStart2 + 2), 'UST')->getStyle('G' . ($rowStart2 + 2) . ':I' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart2 + 2) . ':L' . ($rowStart2 + 2))->setCellValue('J' . ($rowStart2 + 2), 'SELERAKU')->getStyle('J' . ($rowStart2 + 2) . ':L' . ($rowStart2 + 2))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart2 + 3), 'TGT')->getStyle('D' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart2 + 3), 'REAL')->getStyle('E' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart2 + 3), '% VS TGT')->getStyle('F' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart2 + 3), 'TGT')->getStyle('G' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart2 + 3), 'REAL')->getStyle('H' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart2 + 3), '% VS TGT')->getStyle('I' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart2 + 3), 'TGT')->getStyle('J' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart2 + 3), 'REAL')->getStyle('K' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart2 + 3), '% VS TGT')->getStyle('L' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart2 + 1) . ':M' . ($rowStart2 + 3))->setCellValue('M' . ($rowStart2 + 1), '% AVG')->getStyle('M' . ($rowStart2 + 1) . ':M' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('92CDDC', 'FF000000'));
        $ObjSheet->mergeCells('N' . ($rowStart2 + 1) . ':N' . ($rowStart2 + 3))->setCellValue('N' . ($rowStart2 + 1), 'RANGKING')->getStyle('N' . ($rowStart2 + 1) . ':N' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P' . ($rowStart2 + 2), 'UBNG')->getStyle('P' . ($rowStart2 + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P' . ($rowStart2 + 3), 'TGT')->getStyle('P' . ($rowStart2 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart3 = ($rowStart2 + 4);
        for ($i = 0; $i < $jmlAPO; $i++) {

            $ObjSheet->setCellValue('B' . $rowStart3, $this->dataPencapaianApoSPG[$i]['NAME_USER'])->getStyle('B' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart3, $this->dataPencapaianApoSPG[$i]['NAME_AREA'])->getStyle('C' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart3, $this->dataPencapaianApoSPG[$i]['TARGET_NONUST'])->getStyle('D' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart3, $this->dataPencapaianApoSPG[$i]['REAL_NONUST'])->getStyle('E' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart3, $this->dataPencapaianApoSPG[$i]['VSTARGET_NONUST'])->getStyle('F' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('G' . $rowStart3, $this->dataPencapaianApoSPG[$i]['TARGET_UST'])->getStyle('G' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('H' . $rowStart3, $this->dataPencapaianApoSPG[$i]['REAL_UST'])->getStyle('H' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart3, $this->dataPencapaianApoSPG[$i]['VSTARGET_UST'])->getStyle('I' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart3, $this->dataPencapaianApoSPG[$i]['TARGET_SELERAKU'])->getStyle('J' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart3, $this->dataPencapaianApoSPG[$i]['REAL_SELERAKU'])->getStyle('K' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart3, $this->dataPencapaianApoSPG[$i]['VSTARGET_SELERAKU'])->getStyle('L' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart3, $this->dataPencapaianApoSPG[$i]['AVERAGE'])->getStyle('M' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart3, $this->dataPencapaianApoSPG[$i]['ID_USER_RANKSALE'])->getStyle('N' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('P' . $rowStart3)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart3++;
        }

        // Activity SPG
        $rowStart4 = $rowStart3 + 3;
        $ObjSheet->mergeCells('B' . ($rowStart4 - 1) . ':N' . ($rowStart4 - 1))->setCellValue('B' . ($rowStart4 - 1), strtoupper(date_format(date_create(date("Y-m")), 'F Y')))->getStyle('B' . ($rowStart4 - 1) . ':N' . ($rowStart4 - 1))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B' . $rowStart4, 'AKTIVITY SPG')->getStyle('B' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart4, 'Bobot 50%')->getStyle('F' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart4, 'Bobot 25%')->getStyle('I' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart4, 'Bobot 25%')->getStyle('L' . $rowStart4)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));

        $ObjSheet->mergeCells('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->setCellValue('B' . ($rowStart4 + 1), 'NAMA')->getStyle('B' . ($rowStart4 + 1) . ':B' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->setCellValue('C' . ($rowStart4 + 1), 'AREA')->getStyle('C' . ($rowStart4 + 1) . ':C' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFC000', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart4 + 1) . ':L' . ($rowStart4 + 1))->setCellValue('D' . ($rowStart4 + 1), 'KATEGORI')->getStyle('D' . ($rowStart4 + 1) . ':L' . ($rowStart4 + 1))->applyFromArray($this->styling_title_template('00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart4 + 2) . ':F' . ($rowStart4 + 2))->setCellValue('D' . ($rowStart4 + 2), 'AKTIVITAS UB')->getStyle('D' . ($rowStart4 + 2) . ':F' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FF81FF', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart4 + 2) . ':I' . ($rowStart4 + 2))->setCellValue('G' . ($rowStart4 + 2), 'PEDAGANG SAYUR')->getStyle('G' . ($rowStart4 + 2) . ':I' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('D8E4BC', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart4 + 2) . ':L' . ($rowStart4 + 2))->setCellValue('J' . ($rowStart4 + 2), 'RETAIL')->getStyle('J' . ($rowStart4 + 2) . ':L' . ($rowStart4 + 2))->applyFromArray($this->styling_title_template('FFFF00', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart4 + 3), 'TGT')->getStyle('D' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart4 + 3), 'REAL')->getStyle('E' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart4 + 3), '% VS TGT')->getStyle('F' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart4 + 3), 'TGT')->getStyle('G' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart4 + 3), 'REAL')->getStyle('H' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart4 + 3), '% VS TGT')->getStyle('I' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart4 + 3), 'TGT')->getStyle('J' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart4 + 3), 'REAL')->getStyle('K' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart4 + 3), '% VS TGT')->getStyle('L' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart4 + 1) . ':M' . ($rowStart4 + 3))->setCellValue('M' . ($rowStart4 + 1), '% AVG')->getStyle('M' . ($rowStart4 + 1) . ':M' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('92CDDC', 'FF000000'));
        $ObjSheet->mergeCells('N' . ($rowStart4 + 1) . ':N' . ($rowStart4 + 3))->setCellValue('N' . ($rowStart4 + 1), 'RANGKING')->getStyle('N' . ($rowStart4 + 1) . ':N' . ($rowStart4 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $rowStart5 = ($rowStart4 + 4);
        for ($i = 0; $i < $jmlSPG; $i++) {

            $ObjSheet->getStyle('B' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart5)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart5++;
        }

        
        // Pencapaian SPG
        $rowStart6 = $rowStart5 + 2;
        $ObjSheet->setCellValue('B' . $rowStart6, 'PENCAPAIAN SPG')->getStyle('B' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F' . $rowStart6, 'Bobot 75%')->getStyle('F' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I' . $rowStart6, 'Bobot 0%')->getStyle('I' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L' . $rowStart6, 'Bobot 25%')->getStyle('L' . $rowStart6)->applyFromArray($this->styling_default_template(10, 'FFFF0000'));        

        $ObjSheet->mergeCells('B' . ($rowStart6 + 1) . ':B' . ($rowStart6 + 3))->setCellValue('B' . ($rowStart6 + 1), 'NAMA')->getStyle('B' . ($rowStart6 + 1) . ':B' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C' . ($rowStart6 + 1) . ':C' . ($rowStart6 + 3))->setCellValue('C' . ($rowStart6 + 1), 'AREA')->getStyle('C' . ($rowStart6 + 1) . ':C' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFC000', 'FF000000'));
        $ObjSheet->mergeCells('D' . ($rowStart6 + 1) . ':L' . ($rowStart6 + 1))->setCellValue('D' . ($rowStart6 + 1), 'KATEGORI')->getStyle('D' . ($rowStart6 + 1) . ':L' . ($rowStart6 + 1))->applyFromArray($this->styling_title_template('00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D' . ($rowStart6 + 2) . ':F' . ($rowStart6 + 2))->setCellValue('D' . ($rowStart6 + 2), 'NON UST')->getStyle('D' . ($rowStart6 + 2) . ':F' . ($rowStart6 + 2))->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G' . ($rowStart6 + 2) . ':I' . ($rowStart6 + 2))->setCellValue('G' . ($rowStart6 + 2), 'UST')->getStyle('G' . ($rowStart6 + 2) . ':I' . ($rowStart6 + 2))->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J' . ($rowStart6 + 2) . ':L' . ($rowStart6 + 2))->setCellValue('J' . ($rowStart6 + 2), 'SELERAKU')->getStyle('J' . ($rowStart6 + 2) . ':L' . ($rowStart6 + 2))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));

        $ObjSheet->setCellValue('D' . ($rowStart6 + 3), 'TGT')->getStyle('D' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('E' . ($rowStart6 + 3), 'REAL')->getStyle('E' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('F' . ($rowStart6 + 3), '% VS TGT')->getStyle('F' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G' . ($rowStart6 + 3), 'TGT')->getStyle('G' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('H' . ($rowStart6 + 3), 'REAL')->getStyle('H' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('I' . ($rowStart6 + 3), '% VS TGT')->getStyle('I' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J' . ($rowStart6 + 3), 'TGT')->getStyle('J' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('8DB4E2', 'FF000000'));
        $ObjSheet->setCellValue('K' . ($rowStart6 + 3), 'REAL')->getStyle('K' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('92D050', 'FF000000'));
        $ObjSheet->setCellValue('L' . ($rowStart6 + 3), '% VS TGT')->getStyle('L' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FDE9D9', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M' . ($rowStart6 + 1) . ':M' . ($rowStart6 + 3))->setCellValue('M' . ($rowStart6 + 1), '% AVG')->getStyle('M' . ($rowStart6 + 1) . ':M' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('92CDDC', 'FF000000'));
        $ObjSheet->mergeCells('N' . ($rowStart6 + 1) . ':N' . ($rowStart6 + 3))->setCellValue('N' . ($rowStart6 + 1), 'RANGKING')->getStyle('N' . ($rowStart6 + 1) . ':N' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P' . ($rowStart6 + 2), 'UBNG')->getStyle('P' . ($rowStart6 + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P' . ($rowStart6 + 3), 'TGT')->getStyle('P' . ($rowStart6 + 3))->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart7 = ($rowStart6 + 4);
        for ($i = 0; $i < $jmlSPG; $i++) {

            $ObjSheet->getStyle('B' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('C' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('D' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('E' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('F' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('G' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('H' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('I' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('J' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('K' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('L' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('M' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('N' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('P' . $rowStart7)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart7++;
        }

        $fileName = 'RANKING - APO-SPG - ' . date_format(date_create(date("Y-m")), 'F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
