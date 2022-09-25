<?php

namespace App;

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

    public function generate_transaksi_harian()
    {
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("Transaksi Harian");

        $ObjSheet->getColumnDimension('A')->setWidth('18');
        $ObjSheet->getColumnDimension('B')->setWidth('30');
        $ObjSheet->getColumnDimension('C')->setWidth('15');
        $ObjSheet->getColumnDimension('D')->setWidth('18');
        $ObjSheet->getColumnDimension('E')->setWidth('18');

        $ObjSheet->getColumnDimension('F')->setWidth('10');
        $ObjSheet->getColumnDimension('G')->setWidth('10');
        $ObjSheet->getColumnDimension('H')->setWidth('10');

        $ObjSheet->getColumnDimension('I')->setWidth('15');
        $ObjSheet->getColumnDimension('J')->setWidth('15');
        $ObjSheet->getColumnDimension('K')->setWidth('15');
        $ObjSheet->getColumnDimension('L')->setWidth('15');
        $ObjSheet->getColumnDimension('M')->setWidth('15');
        $ObjSheet->getColumnDimension('N')->setWidth('15');
        $ObjSheet->getColumnDimension('O')->setWidth('15');
        $ObjSheet->getColumnDimension('P')->setWidth('15');
        $ObjSheet->getColumnDimension('Q')->setWidth('15');
        $ObjSheet->getColumnDimension('R')->setWidth('15');

        $ObjSheet->getColumnDimension('S')->setWidth('20');
        $ObjSheet->getColumnDimension('T')->setWidth('20');
        $ObjSheet->getColumnDimension('U')->setWidth('20');
        $ObjSheet->getColumnDimension('V')->setWidth('35');

        $ObjSheet->setAutoFilter('A7:V' . (count($this->dataTransaksiHarian) + 7));

        $ObjSheet->mergeCells('A1:U2')->setCellValue('A1', "REKAP HARIAN REGIONAL PROMOTION OFFICER")->getStyle('A1:U2')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('A3:U4')->setCellValue('A3', "*Uleg dalam satuan Inner dan Pars dalam satuan paket")->getStyle('A3:U4')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FFFF0000'));

        $ObjSheet->mergeCells('A5:A7')->setCellValue('A5', 'TANGGAL')->getStyle('A5:A7')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));
        $ObjSheet->mergeCells('B5:B7')->setCellValue('B5', 'NAMA')->getStyle('B5:B7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->mergeCells('C5:C7')->setCellValue('C5', 'JABATAN')->getStyle('C5:C7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->mergeCells('D5:D7')->setCellValue('D5', 'AREA')->getStyle('D5:D7')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->mergeCells('E5:E7')->setCellValue('E5', 'AKTIFITAS')->getStyle('E5:E7')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('F5:H7')->setCellValue('F5', 'LOKASI')->getStyle('F5:H7')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));

        $ObjSheet->mergeCells('I5:R5')->setCellValue('I5', 'ITEM TERJUAL')->getStyle('I5:R5')->applyFromArray($this->styling_title_template('FFFFC000', 'FFFF0000'));
        $ObjSheet->setCellValue('I6', 'UST')->getStyle('I6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('J6', 'USU')->getStyle('J6')->applyFromArray($this->styling_title_template('FFFFC000', 'FF000000'));
        $ObjSheet->setCellValue('K6', 'USP')->getStyle('K6')->applyFromArray($this->styling_title_template('FFE26B0A', 'FF000000'));
        $ObjSheet->setCellValue('L6', 'USI')->getStyle('L6')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));
        $ObjSheet->setCellValue('M6', 'USTR')->getStyle('M6')->applyFromArray($this->styling_title_template('FF948A54', 'FF000000'));
        $ObjSheet->setCellValue('N6', 'USB')->getStyle('N6')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->setCellValue('O6', 'USK')->getStyle('O6')->applyFromArray($this->styling_title_template('FFC0504D', 'FF000000'));
        $ObjSheet->setCellValue('P6', 'USR')->getStyle('P6')->applyFromArray($this->styling_title_template('FFFFC000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('Q6', 'FSU')->getStyle('Q6')->applyFromArray($this->styling_title_template('FFF79646', 'FF000000'));
        $ObjSheet->setCellValue('R6', 'FSB')->getStyle('R6')->applyFromArray($this->styling_title_template('FF00B050', 'FF000000'));

        $ObjSheet->getStyle('I7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('J7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('K7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('L7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('M7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('N7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('O7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('P7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('Q7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('R7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->getStyle('S7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));

        $ObjSheet->mergeCells('S5:S6')->setCellValue('S5', 'TOTAL DISPLAY')->getStyle('S5:S6')->applyFromArray($this->styling_title_template('FF92D050', 'FF000000'));
        $ObjSheet->getStyle('T7')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('T5:T7')->setCellValue('T5', 'TOTAL OMSET')->getStyle('T5:T7')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->mergeCells('U5:U7')->setCellValue('U5', 'KETERANGAN')->getStyle('U5:U7')->applyFromArray($this->styling_title_template('FFBFBFBF', 'FF000000'));

        $rowStart = 8;
        for ($i = 0; $i < count($this->dataTransaksiHarian); $i++) {
            $ObjSheet->setCellValue('A' . $rowStart, $this->dataTransaksiHarian[$i]['DATE_TRANS'])->getStyle('A' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('B' . $rowStart, $this->dataTransaksiHarian[$i]['NAME_USER'])->getStyle('B' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('C' . $rowStart, $this->dataTransaksiHarian[$i]['ROLE_USER'])->getStyle('C' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('D' . $rowStart, $this->dataTransaksiHarian[$i]['AREA_TRANS'])->getStyle('D' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('E' . $rowStart, $this->dataTransaksiHarian[$i]['TYPE'])->getStyle('E' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('F' . $rowStart, $this->dataTransaksiHarian[$i]['LOKASI'])->mergeCells('F' . $rowStart . ':H' . $rowStart)->getStyle('F' . $rowStart . ':H' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('I' . $rowStart, $this->dataTransaksiHarian[$i]['UST'])->getStyle('I' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('J' . $rowStart, $this->dataTransaksiHarian[$i]['USU'])->getStyle('J' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('K' . $rowStart, $this->dataTransaksiHarian[$i]['USP'])->getStyle('K' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('L' . $rowStart, $this->dataTransaksiHarian[$i]['USI'])->getStyle('L' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('M' . $rowStart, $this->dataTransaksiHarian[$i]['USTR'])->getStyle('M' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('N' . $rowStart, $this->dataTransaksiHarian[$i]['USB'])->getStyle('N' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('O' . $rowStart, $this->dataTransaksiHarian[$i]['USK'])->getStyle('O' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('P' . $rowStart, $this->dataTransaksiHarian[$i]['USR'])->getStyle('P' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('Q' . $rowStart, $this->dataTransaksiHarian[$i]['FSU'])->getStyle('Q' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('R' . $rowStart, $this->dataTransaksiHarian[$i]['FSB'])->getStyle('R' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('S' . $rowStart, $this->dataTransaksiHarian[$i]['TOTAL_DISPLAY'])->getStyle('S' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue('T' . $rowStart, $this->dataTransaksiHarian[$i]['TOTAL_OMSET'])->getStyle('T' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->getStyle('U' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $rowStart++;
        }

        $fileName = 'TRANSAKSI HARIAN - APO atau SALES - ' . date_format(date_create(date("Y-m-d")), 'j F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
