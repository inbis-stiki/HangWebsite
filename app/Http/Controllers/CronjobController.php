<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Recomendation;
use AWS\CRT\HTTP\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CronjobController extends Controller
{

    public function cronjob_store_rekomendasi()
    {
        try {
            DB::table("recomendation")->delete();

            $DataUser = DB::table("user")
                ->select('user.*')
                ->get();

            foreach ($DataUser as $ItemUser) {
                $id_user = $ItemUser->ID_USER;

                $Trans = DB::table("transaction")
                    ->select('transaction.*')
                    ->selectRaw('DATEDIFF("' . Carbon::now() . '", transaction.DATE_TRANS) AS DateDiff')
                    ->where('ID_USER', '=', $id_user)
                    ->where('ID_SHOP', '<>', NULL)
                    ->orderBy('DateDiff', 'ASC')
                    ->get();

                $dataRecom = array();
                foreach ($Trans as $dataTrans) {
                    $shopData = DB::table("md_shop")
                        ->select("md_shop.*")
                        ->where('md_shop.ID_SHOP', '=', $dataTrans->ID_SHOP)
                        ->first();

                    if ($dataTrans->DateDiff >= 14) {
                        $dataRespon = array(
                            "ID_USER" => $id_user,
                            "ID_SHOP" => $shopData->ID_SHOP,
                            "PHOTO_SHOP" => $shopData->PHOTO_SHOP,
                            "NAME_SHOP" => $shopData->NAME_SHOP,
                            "OWNER_SHOP" => $shopData->OWNER_SHOP,
                            "DETLOC_SHOP" => $shopData->DETLOC_SHOP,
                            "LONG_SHOP" => $shopData->LONG_SHOP,
                            "LAT_SHOP" => $shopData->LAT_SHOP,
                            "ID_DISTRICT" => $shopData->ID_DISTRICT,
                            "LAST_VISITED" => $dataTrans->DateDiff
                        );

                        array_push($dataRecom, $dataRespon);
                    }
                }

                foreach ($dataRecom as $RecomShop) {
                    $CheckRecom = DB::table("recomendation")
                        ->select("recomendation.*")
                        ->where('recomendation.ID_USER', '=', $RecomShop['ID_USER'])
                        ->where('recomendation.ID_SHOP', '=', $RecomShop['ID_SHOP'])
                        ->first();

                    if ($CheckRecom == null) {
                        $recomendation = new Recomendation();
                        $recomendation->ID_USER      = $RecomShop['ID_USER'];
                        $recomendation->ID_SHOP      = $RecomShop['ID_SHOP'];
                        $recomendation->NAME_SHOP    = $RecomShop['NAME_SHOP'];
                        $recomendation->DETLOC_SHOP  = $RecomShop['DETLOC_SHOP'];
                        $recomendation->PHOTO_SHOP   = $RecomShop['PHOTO_SHOP'];
                        $recomendation->IDLE_RECOM   = $RecomShop['LAST_VISITED'];
                        $recomendation->LAT_SHOP     = $RecomShop['LAT_SHOP'];
                        $recomendation->LONG_SHOP    = $RecomShop['LONG_SHOP'];
                        $recomendation->ID_DISTRICT  = $RecomShop['ID_DISTRICT'];
                        $recomendation->DATE_RECOM   = date('Y-m-d');
                        $recomendation->save();
                    }
                }
            }

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!'
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function cronjob_template_rangking()
    {
        $spreadsheet = new Spreadsheet();

        $ObjSheet0 = $spreadsheet->createSheet(0);
        $ObjSheet0->setTitle("RANGKING RPO");

        $ObjSheet0->getColumnDimension('B')->setWidth('25');
        $ObjSheet0->getColumnDimension('C')->setWidth('25');

        $ObjSheet0->getColumnDimension('D')->setWidth('8');
        $ObjSheet0->getColumnDimension('E')->setWidth('8');
        $ObjSheet0->getColumnDimension('F')->setWidth('8');
        $ObjSheet0->getColumnDimension('G')->setWidth('8');
        $ObjSheet0->getColumnDimension('H')->setWidth('8');
        $ObjSheet0->getColumnDimension('I')->setWidth('8');
        $ObjSheet0->getColumnDimension('J')->setWidth('8');
        $ObjSheet0->getColumnDimension('K')->setWidth('8');
        $ObjSheet0->getColumnDimension('L')->setWidth('8');

        $ObjSheet0->getColumnDimension('M')->setWidth('12');
        $ObjSheet0->getColumnDimension('N')->setWidth('12');
        $ObjSheet0->getColumnDimension('O')->setWidth('2');
        $ObjSheet0->getColumnDimension('P')->setWidth('10');
        $ObjSheet0->getColumnDimension('Q')->setWidth('10');

        $ObjSheet0->getRowDimension('4')->setRowHeight('20');
        $ObjSheet0->getRowDimension('5')->setRowHeight('20');
        $ObjSheet0->getRowDimension('6')->setRowHeight('30');

        $this->table_activity_rpo($ObjSheet0);
        $this->table_pencapaian_rpo($ObjSheet0);

        $ObjSheet1 = $spreadsheet->createSheet(1);
        $ObjSheet1->setTitle("RANGKING ASMEN");

        $ObjSheet1->getColumnDimension('B')->setWidth('25');
        $ObjSheet1->getColumnDimension('C')->setWidth('25');

        $ObjSheet1->getColumnDimension('D')->setWidth('8');
        $ObjSheet1->getColumnDimension('E')->setWidth('8');
        $ObjSheet1->getColumnDimension('F')->setWidth('8');
        $ObjSheet1->getColumnDimension('G')->setWidth('8');
        $ObjSheet1->getColumnDimension('H')->setWidth('8');
        $ObjSheet1->getColumnDimension('I')->setWidth('8');
        $ObjSheet1->getColumnDimension('J')->setWidth('8');
        $ObjSheet1->getColumnDimension('K')->setWidth('8');
        $ObjSheet1->getColumnDimension('L')->setWidth('8');

        $ObjSheet1->getColumnDimension('M')->setWidth('12');
        $ObjSheet1->getColumnDimension('N')->setWidth('12');
        $ObjSheet1->getColumnDimension('O')->setWidth('2');
        $ObjSheet1->getColumnDimension('P')->setWidth('10');
        $ObjSheet1->getColumnDimension('Q')->setWidth('10');

        $ObjSheet1->getRowDimension('4')->setRowHeight('20');
        $ObjSheet1->getRowDimension('5')->setRowHeight('20');
        $ObjSheet1->getRowDimension('6')->setRowHeight('30');

        $this->table_activity_asmen($ObjSheet1);
        $this->table_pencapaian_asmen($ObjSheet1);

        $fileName = 'LAPORAN_RANGKING_BULAN_' . date_format(date_create(date("Y-m")), 'F Y');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        // $path = Excel::store(new Xlsx($spreadsheet), "' . $fileName . '.xlsx", 's3', 'xlsx');
        // echo Storage::disk('s3')->url($path);
    }

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

    public function table_activity_rpo($ObjSheet)
    {
        $ObjSheet->mergeCells('B2:Q2')->setCellValue('B2', 'AGUSTUS 2022')->getStyle('B2:Q2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B3', 'AKTIVITY RPO DAPUL')->getStyle('B3')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F3', 'Bobot 50%')->getStyle('F3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I3', 'Bobot 25%')->getStyle('I3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L3', 'Bobot 25%')->getStyle('L3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N3:Q3')->setCellValue('N3', 'DATA PER 31 AGUSTUS 2022')->getStyle('N3:Q3')->applyFromArray($this->styling_default_template(10, 'FF000000'));

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
        for ($i = 1; $i <= 5; $i++) {

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
    }

    public function table_pencapaian_rpo($ObjSheet)
    {
        $ObjSheet->setCellValue('B16', 'PENCAPAIAN RPO')->getStyle('B16')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F16', 'Bobot 75%')->getStyle('F16')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I16', 'Bobot 0%')->getStyle('I16')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L16', 'Bobot 25%')->getStyle('L16')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N16:Q16')->setCellValue('N16', 'DATA PER 31 AGUSTUS 2022')->getStyle('N16:Q16')->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B17:B19')->setCellValue('B17', 'NAMA')->getStyle('B17:B19')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C17:C19')->setCellValue('C17', 'AREA')->getStyle('C17:C19')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D17:L17')->setCellValue('D17', 'KATEGORI')->getStyle('D17:L17')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D18:F18')->setCellValue('D18', 'NON UST')->getStyle('D18:F18')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G18:I18')->setCellValue('G18', 'UST')->getStyle('G18:I18')->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J18:L18')->setCellValue('J18', 'SELERAKU')->getStyle('J18:L18')->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('D19', 'TGT')->getStyle('D19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E19', 'REAL')->getStyle('E19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F19', '% VS TGT')->getStyle('F19')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G19', 'TGT')->getStyle('G19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H19', 'REAL')->getStyle('H19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I19', '% VS TGT')->getStyle('I19')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J19', 'TGT')->getStyle('J19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K19', 'REAL')->getStyle('K19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L19', '% VS TGT')->getStyle('L19')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M17:M19')->setCellValue('M17', '% AVG')->getStyle('M17:M19')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N17:N19')->setCellValue('N17', 'RANK')->getStyle('N17:N19')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P18', 'UBNG')->getStyle('P18')->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P19', 'REAL')->getStyle('P19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart = 20;
        for ($i = 1; $i <= 5; $i++) {

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
            $ObjSheet->getStyle('P' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

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
    }

    public function table_activity_asmen($ObjSheet)
    {
        $ObjSheet->mergeCells('B2:Q2')->setCellValue('B2', 'AGUSTUS 2022')->getStyle('B2:Q2')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));
        $ObjSheet->setCellValue('B3', 'AKTIVITY ASMEN')->getStyle('B3')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F3', 'Bobot 50%')->getStyle('F3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I3', 'Bobot 25%')->getStyle('I3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L3', 'Bobot 25%')->getStyle('L3')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N3:Q3')->setCellValue('N3', 'DATA PER 31 AGUSTUS 2022')->getStyle('N3:Q3')->applyFromArray($this->styling_default_template(10, 'FF000000'));

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
        for ($i = 1; $i <= 5; $i++) {

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
    }

    public function table_pencapaian_asmen($ObjSheet)
    {
        $ObjSheet->setCellValue('B16', 'PENCAPAIAN ASMEN')->getStyle('B16')->applyFromArray($this->styling_default_template(10, 'FF000000'));
        $ObjSheet->setCellValue('F16', 'Bobot 75%')->getStyle('F16')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('I16', 'Bobot 0%')->getStyle('I16')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->setCellValue('L16', 'Bobot 25%')->getStyle('L16')->applyFromArray($this->styling_default_template(10, 'FFFF0000'));
        $ObjSheet->mergeCells('N16:Q16')->setCellValue('N16', 'DATA PER 31 AGUSTUS 2022')->getStyle('N16:Q16')->applyFromArray($this->styling_default_template(10, 'FF000000'));

        $ObjSheet->mergeCells('B17:B19')->setCellValue('B17', 'NAMA')->getStyle('B17:B19')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('C17:C19')->setCellValue('C17', 'AREA')->getStyle('C17:C19')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D17:L17')->setCellValue('D17', 'KATEGORI')->getStyle('D17:L17')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('D18:F18')->setCellValue('D18', 'NON UST')->getStyle('D18:F18')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('G18:I18')->setCellValue('G18', 'UST')->getStyle('G18:I18')->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('J18:L18')->setCellValue('J18', 'SELERAKU')->getStyle('J18:L18')->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

        $ObjSheet->setCellValue('D19', 'TGT')->getStyle('D19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('E19', 'REAL')->getStyle('E19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('F19', '% VS TGT')->getStyle('F19')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('G19', 'TGT')->getStyle('G19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('H19', 'REAL')->getStyle('H19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('I19', '% VS TGT')->getStyle('I19')->applyFromArray($this->styling_title_template('FF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->setCellValue('J19', 'TGT')->getStyle('J19')->applyFromArray($this->styling_title_template('FFF4B084', 'FF000000'));
        $ObjSheet->setCellValue('K19', 'REAL')->getStyle('K19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));
        $ObjSheet->setCellValue('L19', '% VS TGT')->getStyle('L19')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('M17:M19')->setCellValue('M17', '% AVG')->getStyle('M17:M19')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('N17:N19')->setCellValue('N17', 'RANK')->getStyle('N17:N19')->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'));

        $ObjSheet->setCellValue('P18', 'UBNG')->getStyle('P18')->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'));
        $ObjSheet->setCellValue('P19', 'REAL')->getStyle('P19')->applyFromArray($this->styling_title_template('FFBDD7EE', 'FF000000'));

        $rowStart = 20;
        for ($i = 1; $i <= 5; $i++) {

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
            $ObjSheet->getStyle('P' . $rowStart)->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

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

        $ObjSheet->mergeCells('S17:S19')->setCellValue('S17', 'AREA')->getStyle('S17:S19')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('T17:AB17')->setCellValue('T17', 'KATEGORI')->getStyle('T17:AB17')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('T18:V18')->setCellValue('D18', 'NON UST')->getStyle('T18:V18')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('W18:Y18')->setCellValue('G18', 'UST')->getStyle('W18:Y18')->applyFromArray($this->styling_title_template('FFFF0000', 'FF000000'));
        $ObjSheet->mergeCells('Z18:AB18')->setCellValue('J18', 'SELERAKU')->getStyle('Z18:AB18')->applyFromArray($this->styling_title_template('FF00FF00', 'FF000000'));

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
        $ObjSheet->mergeCells('T20:T21')->getStyle('T20:T21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('U20:U21')->getStyle('U20:U21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('V20:V21')->getStyle('V20:V21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('W20:W21')->getStyle('W20:W21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);

        $ObjSheet->mergeCells('X20:X21')->getStyle('X20:X21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('Y20:Y21')->getStyle('Y20:Y21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('Z20:Z21')->getStyle('Z20:Z21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('AA20:AA21')->getStyle('AA20:AA21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->mergeCells('AB20:AB21')->getStyle('AB20:AB21')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'))->getAlignment()->setWrapText(true);
    }
}
