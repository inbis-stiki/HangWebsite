<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonitoringExcel extends Controller
{
    public function index(Request $req)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $ObjSheet = $spreadsheet->getActiveSheet();
        $ObjSheet->setTitle("NAME REGIONAL");

        $ObjSheet->getColumnDimension('A')->setWidth(10);
        $ObjSheet->getColumnDimension('B')->setWidth(10);
        $ObjSheet->getColumnDimension('C')->setWidth(10);
        $ObjSheet->getColumnDimension('D')->setWidth(12);
        $ObjSheet->getColumnDimension('AJ')->setWidth(25);
        $ObjSheet->getColumnDimension('AL')->setWidth(15);
        $ObjSheet->getColumnDimension('AM')->setWidth(18);
        $ObjSheet->getColumnDimension('AN')->setWidth(15);
        $ObjSheet->getColumnDimension('AO')->setWidth(15);
        $ObjSheet->getColumnDimension('AK')->setWidth(15);
        $ObjSheet->getColumnDimension('AP')->setWidth(25);

        // TITLE MONITORING
        $ObjSheet->mergeCells('A2:AP2')->setCellValue('A2', 'REKAP ABSENSI')->getStyle('A2:AP2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('A3:AP3')->setCellValue('A3', 'BULAN JANUARI 2023')->getStyle('A3:AP3')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        
        // HEADER
        $ObjSheet->setCellValue('A7', '< 07:01')->getStyle('A7')->applyFromArray($this->styling_default_template_noborder('11', '000000'));
        $ObjSheet->mergeCells('A8:A10')->setCellValue('A8', 'NO')->getStyle('A8:A10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('B8:B10')->setCellValue('B8', 'JABATAN')->getStyle('B8:B10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('C8:C10')->setCellValue('C8', 'AREA')->getStyle('C8:C10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('D8:D10')->setCellValue('D8', 'NAMA SPG')->getStyle('D8:D10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('E8:AI9')->setCellValue('E8', 'BULAN JANUARI TAHUN 2023')->getStyle('E8:AI9')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        
        $abjad = "E";
        foreach (range(1, 31) as $i) { 
            $ObjSheet->setCellValue($abjad.'10', $i)->getStyle($abjad.'10')->applyFromArray($this->styling_title_template('ffffff', '000000'));
            $abjad++;
        }

        $ObjSheet->mergeCells('AJ8:AJ10')->setCellValue('AJ8', 'HARI KERJA EFEKTIF')->getStyle('AJ8:AJ10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('AK8:AK10')->setCellValue('AK8', 'ABSENSI')->getStyle('AK8:AK10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('AL8:AO8')->setCellValue('AL8', 'TUNJANGAN TERKAIT JABATAN / POSISI KERJA')->getStyle('AL8:AO8')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('AL9:AO9')->setCellValue('AL9', 'Perhitungan Bulanan')->getStyle('AL9:AO9')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->setCellValue('AL10', 'Gaji Pokok')->getStyle('AL10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->setCellValue('AM10', 'Tunj. Kesehatan')->getStyle('AM10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->setCellValue('AN10', 'Tunj. Pulsa')->getStyle('AN10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->setCellValue('AO10', 'Gaji Diterima')->getStyle('AO10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        $ObjSheet->mergeCells('AP8:AP10')->setCellValue('AP8', 'KETERANGAN TAMBAHAN')->getStyle('AP8:AP10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
        
        // ISI KONTEN
        $row = 11;
        foreach (range(1, 5) as $data){
            $ObjSheet->setCellValue('A'.$row, $data)->getStyle('A'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('B'.$row, 'Manager')->getStyle('B'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('C'.$row, 'Jatim')->getStyle('C'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('D'.$row, 'Dwi Prasetyo')->getStyle('D'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $abjad = "E";
            foreach (range(1, 31) as $i) { 
                $ObjSheet->setCellValue($abjad.''.$row, 'V')->getStyle($abjad.''.$row)->applyFromArray($this->styling_default_template('ffffff', '000000'));
                $abjad++;
            }
            $ObjSheet->setCellValue('AJ'.$row, '31')->getStyle('AJ'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('AK'.$row, '31')->getStyle('AK'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('AL'.$row, 'Rp. 174.881')->getStyle('AL'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('AM'.$row, 'Rp. 100.000')->getStyle('AM'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('AN'.$row, 'Rp. 50.000')->getStyle('AN'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('AO'.$row, 'Rp. 4.522.025')->getStyle('AO'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            $ObjSheet->setCellValue('AP'.$row, 'Tidak libur & ijin')->getStyle('AP'.$row)->applyFromArray($this->styling_default_template('11', '000000'));
            
            $row++;
        }

        $fileName = 'Monitoring Presensi';
        $new_name = time().md5($fileName);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $new_name . '.xlsx"');
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
