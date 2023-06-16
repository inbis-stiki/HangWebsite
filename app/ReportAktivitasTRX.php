<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAktivitasTRX extends Model
{
    public function gen_akt_trx_apo($rOs)
    {
        $spreadsheet = new Spreadsheet();
        $dataRange = array_slice($this->CustomRange('AA'), count(range('A', 'C')));
        $groups = array();
        for ($i = 0; $i < count($dataRange) - 1; $i += 2) {
            $group = $dataRange[$i] . ';' . $dataRange[$i + 1];
            $groups[] = $group;
        }
        $months = array(
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
        );
        foreach ($rOs as $keyMain => $item) {
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle(preg_replace("/[^a-zA-Z0-9 ]/", "", $keyMain));

            $ObjSheet->getColumnDimension('B')->setWidth('35');
            $ObjSheet->getColumnDimension('C')->setWidth('9');
            $ObjSheet->getColumnDimension('D')->setWidth('9');
            $ObjSheet->getColumnDimension('E')->setWidth('9');
            $ObjSheet->getColumnDimension('F')->setWidth('9');
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
            $ObjSheet->getColumnDimension('T')->setWidth('9');
            $ObjSheet->getColumnDimension('U')->setWidth('9');
            $ObjSheet->getColumnDimension('V')->setWidth('9');
            $ObjSheet->getColumnDimension('W')->setWidth('9');
            $ObjSheet->getColumnDimension('X')->setWidth('9');
            $ObjSheet->getColumnDimension('Y')->setWidth('9');
            $ObjSheet->getColumnDimension('Z')->setWidth('9');
            $ObjSheet->getColumnDimension('AA')->setWidth('9');

            $ObjSheet->getColumnDimension('AB')->setWidth('2');
            $ObjSheet->getColumnDimension('AC')->setWidth('9');
            $ObjSheet->getColumnDimension('AD')->setWidth('9');

            // HEADER
            $ObjSheet->mergeCells('B3:B6')->setCellValue('B3', 'AREA')->getStyle('B3:B6')->applyFromArray($this->styling_title_template('66ffff', 'FF000000'));
            $ObjSheet->mergeCells('C3:C6')->setCellValue('C3', 'TGT TRX / BLN')->getStyle('C3:C6')->applyFromArray($this->styling_title_template('ff0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true)->setHorizontal('center');

            foreach ($groups as $key => $detItem) {
                $ObjSheet->mergeCells('D3:AA3')->setCellValue('D3', 'AKTIVITAS TRX APPS 2023')->getStyle('D3:AA3')->applyFromArray($this->styling_title_template('ffff00', 'FF000000'));
                $ObjSheet->mergeCells(explode(';', $detItem)[0] . '4:' . explode(';', $detItem)[1] . '4')->setCellValue(explode(';', $detItem)[0] . '4', $months[$key])->getStyle(explode(';', $detItem)[0] . '4:' . explode(';', $detItem)[1] . '4')->applyFromArray($this->styling_title_template('66ffff', 'FF000000'));
                $ObjSheet->mergeCells(explode(';', $detItem)[0] . '5:' . explode(';', $detItem)[0] . '6')->setCellValue(explode(';', $detItem)[0] . '5', 'TRX')->getStyle(explode(';', $detItem)[0] . '5:' . explode(';', $detItem)[0] . '6')->applyFromArray($this->styling_title_template('0c13ff', 'FFFFFFFF'));
                $ObjSheet->mergeCells(explode(';', $detItem)[1] . '5:' . explode(';', $detItem)[1] . '6')->setCellValue(explode(';', $detItem)[1] . '5', '% TRX VS TGT')->getStyle(explode(';', $detItem)[1] . '5:' . explode(';', $detItem)[1] . '6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
            }

            $ObjSheet->mergeCells('AC3:AD4')->setCellValue('AC3', 'Rata - Rata ' . date('Y'))->getStyle('AC3:AD4')->applyFromArray($this->styling_title_template('66ffff', 'FF000000'));
            $ObjSheet->mergeCells('AC5:AC6')->setCellValue('AC5', 'TRX')->getStyle('AC5:AC6')->applyFromArray($this->styling_title_template('0c13ff', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
            $ObjSheet->mergeCells('AD5:AD6')->setCellValue('AD5', '% TRX VS TGT')->getStyle('AD5:AD6')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

            // ISI KONTEN
            $start = 6;
            $rowData = 7;

            $TotalAllCall = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $TotalAllRO = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $sumTgtTrxBln = 0;
            foreach ($item as $detItem) {
                $ObjSheet->setCellValue('B' . $rowData, $detItem['AREA'])->getStyle('B' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue('C' . $rowData, $detItem['TGT_TRX_BLN'])->getStyle('C' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'))->getAlignment()->setWrapText(true);
                foreach ($groups as $key => $groupsItem) {
                    $TotalAllCall[$key] += $detItem['TRX'][$key];
                    $TotalAllRO[$key] += $detItem['TGTVS'][$key];

                    $ObjSheet->setCellValue(explode(';', $groupsItem)[0] . $rowData, $detItem['TRX'][$key])->getStyle(explode(';', $groupsItem)[0] . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'))->getAlignment()->setHorizontal('center');
                    $ObjSheet->setCellValue(explode(';', $groupsItem)[1] . $rowData, $detItem['TGTVS'][$key] . '%')->getStyle(explode(';', $groupsItem)[1] . $rowData)->applyFromArray((($detItem['TGTVS'][$key] >= 100) ? $this->styling_title_template('FFFF0000', 'FFFFFF') : $this->styling_title_template('FF00FF00', '000000')))->getAlignment()->setHorizontal('right');
                }

                $totCall = array_sum($detItem['TRX']);
                $countCall = count($detItem['TRX']);
                $avgCall = $totCall / $countCall;
                $sumTgtTrxBln += intval($detItem['TGT_TRX_BLN']);

                $totRo = array_sum($detItem['TGTVS']);
                $countRo = count($detItem['TGTVS']);
                $avgRo = ($totCall / 1500) * 100;

                $ObjSheet->setCellValue('AC' . $rowData, $totCall)->getStyle('AC' . $rowData)->applyFromArray($this->styling_default_template('00FFFFFF', '000000'))->getAlignment()->setHorizontal('center');
                $ObjSheet->setCellValue('AD' . $rowData, (($avgRo != 0) ? number_format($avgRo, 2, ',', '.') : 0) . '%')->getStyle('AD' . $rowData)->applyFromArray((($avgRo >= 100) ? $this->styling_title_template('FFFF0000', 'FFFFFF') : $this->styling_title_template('FF00FF00', '000000')))->getAlignment()->setHorizontal('right')->setWrapText(true);

                $rowData++;
            }

            // FOOTER
            foreach ($groups as $key => $groupsItem) {
                // dd($groupsItem);die;
                $ObjSheet->setCellValue(explode(';', $groupsItem)[0] . $rowData, $TotalAllCall[$key])->getStyle(explode(';', $groupsItem)[0] . $rowData)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setHorizontal('center');
                $rt_trxvs = $ObjSheet->setCellValue(explode(';', $groupsItem)[1] . $rowData, '=IF(OR(' . explode(';', $groupsItem)[0] . $rowData . '=0), 0, ROUNDUP(IFERROR(' . explode(';', $groupsItem)[0] . $rowData . '/C' . $rowData . ', 0), 3))')->getStyle(explode(';', $groupsItem)[1] . $rowData)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
                $rt_trxvs->getAlignment()->setHorizontal('right');
                $rt_trxvs->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
            }

            $totAllCall = array_sum($TotalAllCall);
            $countAllCall = count($TotalAllCall);
            $avgAllCall = $totAllCall / $countAllCall;

            $totAllRo = array_sum($TotalAllCall);
            $countAllRo = count($TotalAllCall);
            $avgAllRo = $totAllRo / $countAllRo;

            $ObjSheet->setCellValue('AC' . $rowData, $totAllCall)->getStyle('AC' . $rowData)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'))->getAlignment()->setWrapText(true);
            $avg_trxvs = $ObjSheet->setCellValue('AD' . $rowData, '=IF(OR(AC'. $rowData . '=0), 0, ROUNDUP(IFERROR(AC'. $rowData . '/C' . $rowData . ', 0), 3))')->getStyle('AD' . $rowData)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $avg_trxvs->getAlignment()->setHorizontal('right');
            $avg_trxvs->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $ObjSheet->setCellValue('B' . $rowData, $keyMain)->getStyle('B' . $rowData)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
            $ObjSheet->setCellValue('C' . $rowData, $sumTgtTrxBln)->getStyle('C' . $rowData)->applyFromArray($this->styling_title_template('FF00FFFF', 'FF000000'));
        }

        $spreadsheet->removeSheetByIndex(0);

        $fileName = 'Aktivitas TRX APPS APO';

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
