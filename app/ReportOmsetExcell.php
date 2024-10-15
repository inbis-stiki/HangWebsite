<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportOmsetExcell
{
    public function gen_omset($rOs, $regional, $shopProduct, $typeshopProduct)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $indMonth = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $dataRange = array_slice($this->CustomRange('Z'), count(range('A', 'B')));
        $groupedRange = array_chunk($dataRange, 2);
        // dd($rOs);
        foreach ($rOs as $tipe => $item) {
            $rowIsi = 5;
            $ObjSheet = $spreadsheet->createSheet();
            $ObjSheet->setTitle($tipe);

            $ObjSheet->getColumnDimension('B')->setWidth('25');

            $ObjSheet->mergeCells('B2:Z2')->setCellValue('B2', strtoupper($tipe))->getStyle('B2:Z2')->applyFromArray($this->styling_title_template('FFDF6726', 'FFFFFFFF'));
            $ObjSheet->mergeCells('B3:B4')->setCellValue('B3', 'NAMA APO / SPG')->getStyle('B3:B4')->applyFromArray($this->styling_title_template('FFF2DCDB', '000000'));

            foreach ($indMonth as $keyMonth => $itemMonth) {
                $cellUsing = $groupedRange[$keyMonth];

                $ObjSheet->getColumnDimension($cellUsing[0])->setWidth('17');
                $ObjSheet->getColumnDimension($cellUsing[1])->setWidth('17');

                $realCell = $cellUsing[0] . '3:' . $cellUsing[1] . '3';
                $ObjSheet->mergeCells($realCell)->setCellValue($cellUsing[0] . '3', strtoupper($itemMonth))->getStyle($realCell)->applyFromArray($this->styling_title_template('FFF2DCDB', '000000'));

                $ObjSheet->setCellValue($cellUsing[0] . '4', 'TOTAL OMSET')->getStyle($cellUsing[0] . '4')->applyFromArray($this->styling_title_template('FFF2DCDB', '000000'));
                $ObjSheet->setCellValue($cellUsing[1] . '4', 'TOTAL OUTLET')->getStyle($cellUsing[1] . '4')->applyFromArray($this->styling_title_template('FFF2DCDB', '000000'));
            }

            // ISI KONTEN
            foreach ($item as $detItem) {
                $ObjSheet->setCellValue('B' . $rowIsi, $detItem['NAME_USER'])->getStyle('B' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'))->getAlignment()->setWrapText(true);

                foreach ($indMonth as $keyMonth => $itemMonth) {
                    $ObjSheet->setCellValue('B' . $rowIsi, $detItem['NAME_USER'])->getStyle('B' . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'))->getAlignment()->setWrapText(true);

                    $cellUsing = $groupedRange[$keyMonth];

                    $valueOmset = $detItem['MONTH' . ($keyMonth + 1) . '_TOTAL_OMSET'];
                    $valueOutlet = $detItem['MONTH' . ($keyMonth + 1) . '_TOTAL_OUTLET'];

                    $ObjSheet->setCellValue($cellUsing[0] . $rowIsi, $valueOmset)->getStyle($cellUsing[0] . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'))->getAlignment()->setWrapText(true);
                    $ObjSheet->setCellValue($cellUsing[1] . $rowIsi, $valueOutlet)->getStyle($cellUsing[1] . $rowIsi)->applyFromArray($this->styling_default_template('11', '000000'))->getAlignment()->setWrapText(true);
                }

                $rowIsi++;
            }
            // END ISI KONTEN
        }

        if ($typeshopProduct == 'SHOP_CATEGORY') {
            $title = 'By Tipe Toko ' . $shopProduct;
        } else if ($typeshopProduct == 'PRODUCT_CATEGORY') {
            $title = 'By Kategori Produk ' . $shopProduct;
        } else {
            $title = '';
        }

        $ObjSheet->setAutoFilter('B3:B' . $rowIsi);
        $spreadsheet->removeSheetByIndex(0);

        $fileName = 'Report Omset ' . $regional . ' ' . $title;
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
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
