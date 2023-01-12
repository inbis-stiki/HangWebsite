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

        $fileName = 'MONITORING PRESENSI BULAN '.strtoupper(date('F'))." ".date('Y')."_".date('d-m-Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function generateDaily($presences, $totDate, $sundays){
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        foreach ($presences as $presence) {
            # code...
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($countSheet++);
            $ObjSheet = $spreadsheet->getActiveSheet()->setTitle($presence['NAME_REGIONAL']);
    
            // < 07.01
            $ObjSheet->getColumnDimension('A')->setWidth(10);
            $ObjSheet->getColumnDimension('B')->setWidth(25);
            $ObjSheet->getColumnDimension('C')->setWidth(10);
            $ObjSheet->getColumnDimension('D')->setWidth(25);
            $ObjSheet->getColumnDimension('E')->setWidth(15);
            
            // HEADER
            $ObjSheet->mergeCells('A2:E2')->setCellValue('A2', 'PRESENSI < 07.01')->getStyle('A2:E2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('A3:A5')->setCellValue('A3', 'NO')->getStyle('A3:A5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('B3:B5')->setCellValue('B3', 'NAMA')->getStyle('B3:B5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('C3:C5')->setCellValue('C3', 'JABATAN')->getStyle('C3:C5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('D3:D5')->setCellValue('D3', 'AREA')->getStyle('D3:D5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            $ObjSheet->mergeCells('E3:E5')->setCellValue('E3', 'WAKTU')->getStyle('E3:E5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            // ISI KONTEN
            $row = 6;
            $no = 1;
            foreach ($presence['PRESENCES1'] as $data){
                $temp = (array)$data;
                $ObjSheet->setCellValue('A'.$row, $no++)->getStyle('A'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('B'.$row, $data->NAME_USER)->getStyle('B'.$row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('C'.$row, $data->NAME_ROLE)->getStyle('C'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('D'.$row, $data->NAME_AREA)->getStyle('D'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('E'.$row, $data->TIME)->getStyle('E'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                
                $row++;
            }
            
            // 07.01 - 07.15
            $ObjSheet->getColumnDimension('G')->setWidth(10);
            $ObjSheet->getColumnDimension('H')->setWidth(25);
            $ObjSheet->getColumnDimension('I')->setWidth(10);
            $ObjSheet->getColumnDimension('J')->setWidth(25);
            $ObjSheet->getColumnDimension('K')->setWidth(15);
            
            // HEADER
            $ObjSheet->mergeCells('G2:K2')->setCellValue('G2', 'PRESENSI 07.01 - 07.15')->getStyle('G2:K2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('G3:G5')->setCellValue('G3', 'NO')->getStyle('G3:G5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('H3:H5')->setCellValue('H3', 'NAMA')->getStyle('H3:H5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('I3:I5')->setCellValue('I3', 'JABATAN')->getStyle('I3:I5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('J3:J5')->setCellValue('J3', 'AREA')->getStyle('J3:J5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            $ObjSheet->mergeCells('K3:K5')->setCellValue('K3', 'WAKTU')->getStyle('K3:K5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            // ISI KONTEN
            $row = 6;
            $no = 1;
            foreach ($presence['PRESENCES2'] as $data){
                $temp = (array)$data;
                $ObjSheet->setCellValue('G'.$row, $no++)->getStyle('G'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('H'.$row, $data->NAME_USER)->getStyle('H'.$row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('I'.$row, $data->NAME_ROLE)->getStyle('I'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('J'.$row, $data->NAME_AREA)->getStyle('J'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('K'.$row, $data->TIME)->getStyle('K'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                
                $row++;
            }
            
            // 07.16 - 07.30
            $ObjSheet->getColumnDimension('M')->setWidth(10);
            $ObjSheet->getColumnDimension('N')->setWidth(25);
            $ObjSheet->getColumnDimension('O')->setWidth(10);
            $ObjSheet->getColumnDimension('P')->setWidth(25);
            $ObjSheet->getColumnDimension('Q')->setWidth(15);
            
            // HEADER
            $ObjSheet->mergeCells('M2:Q2')->setCellValue('M2', 'PRESENSI 07.16 - 07.30')->getStyle('M2:Q2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('M3:M5')->setCellValue('M3', 'NO')->getStyle('M3:M5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('N3:N5')->setCellValue('N3', 'NAMA')->getStyle('N3:N5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('O3:O5')->setCellValue('O3', 'JABATAN')->getStyle('O3:O5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('P3:P5')->setCellValue('P3', 'AREA')->getStyle('P3:P5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            $ObjSheet->mergeCells('Q3:Q5')->setCellValue('Q3', 'WAKTU')->getStyle('Q3:Q5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            // ISI KONTEN
            $row = 6;
            $no = 1;
            foreach ($presence['PRESENCES3'] as $data){
                $temp = (array)$data;
                $ObjSheet->setCellValue('M'.$row, $no++)->getStyle('M'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('N'.$row, $data->NAME_USER)->getStyle('N'.$row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('O'.$row, $data->NAME_ROLE)->getStyle('O'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('P'.$row, $data->NAME_AREA)->getStyle('P'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('Q'.$row, $data->TIME)->getStyle('Q'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                
                $row++;
            }
            
            // > 07.31
            $ObjSheet->getColumnDimension('S')->setWidth(10);
            $ObjSheet->getColumnDimension('T')->setWidth(25);
            $ObjSheet->getColumnDimension('U')->setWidth(10);
            $ObjSheet->getColumnDimension('V')->setWidth(25);
            $ObjSheet->getColumnDimension('W')->setWidth(15);
            
            // HEADER
            $ObjSheet->mergeCells('S2:W2')->setCellValue('S2', 'PRESENSI > 07.31')->getStyle('S2:W2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('S3:S5')->setCellValue('S3', 'NO')->getStyle('S3:S5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('T3:T5')->setCellValue('T3', 'NAMA')->getStyle('T3:T5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('U3:U5')->setCellValue('U3', 'JABATAN')->getStyle('U3:U5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('V3:V5')->setCellValue('V3', 'AREA')->getStyle('V3:V5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            $ObjSheet->mergeCells('W3:V5')->setCellValue('W3', 'WAKTU')->getStyle('W3:V5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            // ISI KONTEN
            $row = 6;
            $no = 1;
            foreach ($presence['PRESENCES4'] as $data){
                $temp = (array)$data;
                $ObjSheet->setCellValue('S'.$row, $no++)->getStyle('S'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('T'.$row, $data->NAME_USER)->getStyle('T'.$row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('U'.$row, $data->NAME_ROLE)->getStyle('U'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('V'.$row, $data->NAME_AREA)->getStyle('V'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('W'.$row, $data->TIME)->getStyle('W'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                
                $row++;
            }
            
            // IDAK PRESENSI
            $ObjSheet->getColumnDimension('Y')->setWidth(10);
            $ObjSheet->getColumnDimension('Z')->setWidth(25);
            $ObjSheet->getColumnDimension('AA')->setWidth(10);
            $ObjSheet->getColumnDimension('AB')->setWidth(25);
            
            // HEADER
            $ObjSheet->mergeCells('Y2:AB2')->setCellValue('Y2', 'TIDAK PRESENSI')->getStyle('Y2:AB2')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('Y3:Y5')->setCellValue('Y3', 'NO')->getStyle('Y3:Y5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('Z3:Z5')->setCellValue('Z3', 'NAMA')->getStyle('Z3:Z5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('AA3:AA5')->setCellValue('AA3', 'JABATAN')->getStyle('AA3:AA5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));
            $ObjSheet->mergeCells('AB3:AB5')->setCellValue('AB3', 'AREA')->getStyle('AB3:AB5')->applyFromArray($this->styling_title_template('fcd5b5', '000000'));            
            // ISI KONTEN
            $row = 6;
            $no = 1;
            foreach ($presence['PRESENCES5'] as $data){
                $temp = (array)$data;
                $ObjSheet->setCellValue('Y'.$row, $no++)->getStyle('Y'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('Z'.$row, $data->NAME_USER)->getStyle('Z'.$row)->applyFromArray($this->styling_default_template_left('11', '000000'));
                $ObjSheet->setCellValue('AA'.$row, $data->NAME_ROLE)->getStyle('AA'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                $ObjSheet->setCellValue('AB'.$row, $data->NAME_AREA)->getStyle('AB'.$row)->applyFromArray($this->styling_default_template('11', '000000', 'ffffff'));
                
                $row++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'MONITORING PRESENSI TANGGAL '.date('d-m-Y');
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
