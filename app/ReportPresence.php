<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportPresence
{
    public function generateMonthly($presences, $totDate, $sundays){
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        foreach ($presences as $presence) {
            # code...
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($countSheet++);
            $ObjSheet = $spreadsheet->getActiveSheet()->setTitle($presence['NAME_REGIONAL']);
    
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
            $ObjSheet->mergeCells('A3:AP3')->setCellValue('A3', 'BULAN '.strtoupper(date('F')).' '.date('Y'))->getStyle('A3:AP3')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            
            // HEADER
            $ObjSheet->mergeCells('A8:A10')->setCellValue('A8', 'NO')->getStyle('A8:A10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B8:B10')->setCellValue('B8', 'NAMA')->getStyle('B8:B10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C8:C10')->setCellValue('C8', 'JABATAN')->getStyle('C8:C10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D8:D10')->setCellValue('D8', 'AREA')->getStyle('D8:D10')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('E8:AI9')->setCellValue('E8', 'BULAN '.strtoupper(date('F')).' TAHUN '.date('Y'))->getStyle('E8:AI9')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            
            $abjad = "E";
            foreach (range(1, (int)$totDate) as $i) { 
                $fillColor = "ffffff";
                $txtColor  = "000000";
                if(!empty($sundays[$i])){
                    $fillColor = "c12807";
                    $txtColor  = "ffffff";
                }

                $ObjSheet->setCellValue($abjad.'10', $i)->getStyle($abjad.'10')->applyFromArray($this->styling_title_template($fillColor, $txtColor));
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
            $no = 1;
            foreach ($presence['PRESENCES'] as $data){
                $temp = (array)$data;
                $ObjSheet->setCellValue('A'.$row, $no++)->getStyle('A'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B'.$row, $data->NAME_USER)->getStyle('B'.$row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C'.$row, $data->NAME_ROLE)->getStyle('C'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D'.$row, $data->NAME_AREA)->getStyle('D'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $abjad = "E";
                $absent = 0;
                
                for ($i = 1; $i <= (int)$totDate; $i++) {
                    $fillColor = "ffffff";
                    $txtColor  = "000000";
                    if(!empty($sundays[$i])){
                        $fillColor = "c12807";
                        $txtColor  = "ffffff";
                    }
    
                    if(!empty($temp["TGL".$i])){
                        if($temp["TGL".$i] == "M"){
                            $ObjSheet->setCellValue($abjad.''.$row, 'V')->getStyle($abjad.''.$row)->applyFromArray($this->styling_default_template('11', $txtColor, $fillColor));
                            $absent++;
                        }else{
                            $ObjSheet->setCellValue($abjad.''.$row, '-')->getStyle($abjad.''.$row)->applyFromArray($this->styling_default_template('11', $txtColor, $fillColor));
                        }
                    }else{
                        $ObjSheet->setCellValue($abjad.''.$row, '')->getStyle($abjad.''.$row)->applyFromArray($this->styling_default_template('11', $txtColor, $fillColor));
                    }
    
                    $abjad++;
                }
                $ObjSheet->setCellValue('AJ'.$row, '25')->getStyle('AJ'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AK'.$row, $absent)->getStyle('AK'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AL'.$row, '')->getStyle('AL'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AM'.$row, '')->getStyle('AM'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AN'.$row, '')->getStyle('AN'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AO'.$row, '')->getStyle('AO'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AP'.$row, '')->getStyle('AP'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                
                $row++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'MONITORING PRESENSI BULAN '.strtoupper(date('F'))." ".date('Y')." ".date('d-m-Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
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
