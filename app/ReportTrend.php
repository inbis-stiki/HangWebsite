<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportTrend
{
    protected $dataTrendRPO = [
        array(
            "NAME_REGIONAL" => "JABODETABEK",
            "DATA" => [
                [
                    "NAME_AREA" => "SERANG KOTA",
                    "RATA" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "AVG" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "RATIO" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM_LAST_YEAR" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM" => [
                        "UST" => [
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ],
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ]
                        ],
                        "NONUST" => [
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ],
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ]
                        ],
                        "SELERAKU" => [
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ],
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ]
                        ]
                    ]
                ],
                [
                    "NAME_AREA" => "JAKARTA TIMUR",
                    "RATA" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "AVG" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "RATIO" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM_LAST_YEAR" => [
                        "UST" => 271,
                        "NONUST" => 100,
                        "SELERAKU" => 80
                    ],
                    "ITEM" => [
                        "UST" => [
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ],
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ]
                        ],
                        "NONUST" => [
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ],
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ]
                        ],
                        "SELERAKU" => [
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ],
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ]
                        ]
                    ]
                ]
            ]
        ),
        array(
            "NAME_REGIONAL" => "JATIM 1",
            "DATA" => [
                [
                    "NAME_AREA" => "SURABAYA 1",
                    "RATA" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "AVG" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "RATIO" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM_LAST_YEAR" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM" => [
                        "UST" => [
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ],
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ]
                        ],
                        "NONUST" => [
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ],
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ]
                        ],
                        "SELERAKU" => [
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ],
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ]
                        ]
                    ]
                ],
                [
                    "NAME_AREA" => "MALANG 2",
                    "RATA" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "AVG" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "RATIO" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM_LAST_YEAR" => [
                        "UST" => 155,
                        "NONUST" => 323,
                        "SELERAKU" => 90
                    ],
                    "ITEM" => [
                        "UST" => [
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ],
                            [
                                "TGT" => 288,
                                "REAL" => 157,
                                "VSTGT" => 55
                            ]
                        ],
                        "NONUST" => [
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ],
                            [
                                "TGT" => 3600,
                                "REAL" => 435,
                                "VSTGT" => 12
                            ]
                        ],
                        "SELERAKU" => [
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ],
                            [
                                "TGT" => 576,
                                "REAL" => 44,
                                "VSTGT" => 8
                            ]
                        ]
                    ]
                ]
            ]
        )
    ];

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

    public function generate_trend_rpo($reports, $year)
    {
        
        $spreadsheet = new Spreadsheet();
        $ObjSheet = $spreadsheet->getActiveSheet(0);
        $ObjSheet->setTitle("TREND RPO");

        $ObjSheet->getColumnDimension('A')->setWidth('2');
        $ObjSheet->getColumnDimension('B')->setWidth('2');
        $ObjSheet->getColumnDimension('C')->setWidth('20');
        $ObjSheet->getColumnDimension('D')->setWidth('10');

        $ObjSheet->getColumnDimension('E')->setWidth('8');
        $ObjSheet->getColumnDimension('F')->setWidth('8');
        $ObjSheet->getColumnDimension('G')->setWidth('8');
        $ObjSheet->getColumnDimension('H')->setWidth('8');
        $ObjSheet->getColumnDimension('I')->setWidth('8');
        $ObjSheet->getColumnDimension('J')->setWidth('8');
        $ObjSheet->getColumnDimension('K')->setWidth('8');
        $ObjSheet->getColumnDimension('L')->setWidth('8');
        $ObjSheet->getColumnDimension('M')->setWidth('8');
        $ObjSheet->getColumnDimension('N')->setWidth('8');
        $ObjSheet->getColumnDimension('O')->setWidth('8');
        $ObjSheet->getColumnDimension('P')->setWidth('8');
        $ObjSheet->getColumnDimension('Q')->setWidth('8');
        $ObjSheet->getColumnDimension('R')->setWidth('8');
        $ObjSheet->getColumnDimension('S')->setWidth('8');
        $ObjSheet->getColumnDimension('T')->setWidth('8');
        $ObjSheet->getColumnDimension('U')->setWidth('8');
        $ObjSheet->getColumnDimension('V')->setWidth('8');
        $ObjSheet->getColumnDimension('W')->setWidth('8');
        $ObjSheet->getColumnDimension('X')->setWidth('8');
        $ObjSheet->getColumnDimension('Y')->setWidth('8');
        $ObjSheet->getColumnDimension('Z')->setWidth('8');
        $ObjSheet->getColumnDimension('AA')->setWidth('8');
        $ObjSheet->getColumnDimension('AB')->setWidth('8');
        $ObjSheet->getColumnDimension('AC')->setWidth('8');
        $ObjSheet->getColumnDimension('AD')->setWidth('8');
        $ObjSheet->getColumnDimension('AE')->setWidth('8');
        $ObjSheet->getColumnDimension('AF')->setWidth('8');
        $ObjSheet->getColumnDimension('AG')->setWidth('8');
        $ObjSheet->getColumnDimension('AH')->setWidth('8');
        $ObjSheet->getColumnDimension('AI')->setWidth('8');
        $ObjSheet->getColumnDimension('AJ')->setWidth('8');
        $ObjSheet->getColumnDimension('AK')->setWidth('8');
        $ObjSheet->getColumnDimension('AL')->setWidth('8');
        $ObjSheet->getColumnDimension('AM')->setWidth('8');
        $ObjSheet->getColumnDimension('AN')->setWidth('8');
        
        $ObjSheet->getColumnDimension('AO')->setWidth('13');
        $ObjSheet->getColumnDimension('AP')->setWidth('13');
        $ObjSheet->getColumnDimension('AQ')->setWidth('13');

        $ObjSheet->getRowDimension('4')->setRowHeight('25');

        // $ObjSheet->mergeCells('B2:B4')->setCellValue('B2', date('Y'))->getStyle('B2:B4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('C2:C4')->setCellValue('C2', "AREA")->getStyle('C2:C4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D2:D4')->setCellValue('D2', "ITEM")->getStyle('D2:D4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('E2:AN2')->setCellValue('E2', "REALISASI")->getStyle('E2:AN2')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('E3:G3')->setCellValue('E3', "JAN")->getStyle('E3:G3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('H3:J3')->setCellValue('H3', "FEB")->getStyle('H3:J3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('K3:M3')->setCellValue('K3', "MAR")->getStyle('K3:M3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('N3:P3')->setCellValue('N3', "APR")->getStyle('N3:P3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('Q3:S3')->setCellValue('Q3', "MEI")->getStyle('Q3:S3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('T3:V3')->setCellValue('T3', "JUN")->getStyle('T3:V3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('W3:Y3')->setCellValue('W3', "JUL")->getStyle('W3:Y3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('Z3:AB3')->setCellValue('Z3', "AUG")->getStyle('Z3:AB3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AC3:AE3')->setCellValue('AC3', "SEP")->getStyle('AC3:AE3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AF3:AH3')->setCellValue('AF3', "OKT")->getStyle('AF3:AH3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AI3:AK3')->setCellValue('AI3', "NOV")->getStyle('AI3:AK3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AL3:AN3')->setCellValue('AL3', "DES")->getStyle('AL3:AN3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));

        $ObjSheet->setCellValue('E4', 'TGT')->getStyle('E4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('F4', 'REAL')->getStyle('F4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('G4', '%VSTGT')->getStyle('G4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('H4', 'TGT')->getStyle('H4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('I4', 'REAL')->getStyle('I4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('J4', '%VSTGT')->getStyle('J4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('K4', 'TGT')->getStyle('K4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('L4', 'REAL')->getStyle('L4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('M4', '%VSTGT')->getStyle('M4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('N4', 'TGT')->getStyle('N4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('O4', 'REAL')->getStyle('O4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('P4', '%VSTGT')->getStyle('P4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('Q4', 'TGT')->getStyle('Q4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('R4', 'REAL')->getStyle('R4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('S4', '%VSTGT')->getStyle('S4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('T4', 'TGT')->getStyle('T4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('U4', 'REAL')->getStyle('U4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('V4', '%VSTGT')->getStyle('V4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('W4', 'TGT')->getStyle('W4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('X4', 'REAL')->getStyle('X4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('Y4', '%VSTGT')->getStyle('Y4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('Z4', 'TGT')->getStyle('Z4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AA4', 'REAL')->getStyle('AA4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AB4', '%VSTGT')->getStyle('AB4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AC4', 'TGT')->getStyle('AC4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AD4', 'REAL')->getStyle('AD4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AE4', '%VSTGT')->getStyle('AE4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AF4', 'TGT')->getStyle('AF4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AG4', 'REAL')->getStyle('AG4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AH4', '%VSTGT')->getStyle('AH4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AI4', 'TGT')->getStyle('AI4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AJ4', 'REAL')->getStyle('AJ4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AK4', '%VSTGT')->getStyle('AK4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AL4', 'TGT')->getStyle('AL4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AM4', 'REAL')->getStyle('AM4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AN4', '%VSTGT')->getStyle('AN4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        
        $ObjSheet->mergeCells('AO2:AO4')->setCellValue('AO2', "RATA2")->getStyle('AO2:AO4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('AP2:AP4')->setCellValue('AP2', "% AVG")->getStyle('AP2:AP4')->applyFromArray($this->styling_title_template('FFE60000', 'FFFFFFFF'));
        // $ObjSheet->mergeCells('AQ2:AQ4')->setCellValue('AQ2', "% 2022 vs 2021")->getStyle('AQ2:AQ4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $rowStart = 5;
        $i = 0;
        foreach ($reports as $report) {
            $ObjSheet->mergeCells('C' . ($rowStart + $i) . ':C' . (($rowStart + $i) + 4))->setCellValue('C' . ($rowStart + $i), $report->REGIONAL_STL)->getStyle('C' . ($rowStart + $i) . ':C' . (($rowStart + $i) + 4))->applyFromArray($this->styling_title_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $reals['m1'] = explode(';', $report->M1);
            $reals['m2'] = explode(';', $report->M2);
            $reals['m3'] = explode(';', $report->M3);
            $reals['m4'] = explode(';', $report->M4);
            $reals['m5'] = explode(';', $report->M5);
            $reals['m6'] = explode(';', $report->M6);
            $reals['m7'] = explode(';', $report->M7);
            $reals['m8'] = explode(';', $report->M8);
            $reals['m9'] = explode(';', $report->M9);
            $reals['m10'] = explode(';', $report->M10);
            $reals['m11'] = explode(';', $report->M11);
            $reals['m12'] = explode(';', $report->M12);

            // == UST == 
            // $ObjSheet->setCellValue('B' . ($rowStart + $i), $DataUST['ITEM_LAST_YEAR']['UST'])->getStyle('B' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue("D" . ($rowStart + $i), "UST")->getStyle("D" . ($rowStart + $i))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $report->TGTUST)->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $reals['m'.$j][0])->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), ($reals['m'.$j][0] != 0 ? round(((int)$reals['m'.$j][0]/$report->TGTUST) * 100) : 0) . '%')->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }
            
            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.($rowStart + $i);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $avgRealCell)->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.($rowStart + $i);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $avgRealCell)->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // == NON UST == 
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 1), "NONUST")->getStyle("D" . (($rowStart + $i) + 1))->applyFromArray($this->styling_title_template('FFFFFF00', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 1), $report->TGTNONUST)->getStyle($abjad . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 1), $reals['m'.$j][1])->getStyle($abjad . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 1), ($reals['m'.$j][1] != 0 ? round(((int)$reals['m'.$j][1]/$report->TGTNONUST) * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+1);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+1), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+1);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+1), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // == SELERAKU ==
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 2), "SELERAKU")->getStyle("D" . (($rowStart + $i) + 2))->applyFromArray($this->styling_title_template('FF00FF00', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 2), $report->TGTSELERAKU)->getStyle($abjad . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 2), $reals['m'.$j][2])->getStyle($abjad . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 2), ($reals['m'.$j][2] != 0 ? round(((int)$reals['m'.$j][2]/$report->TGTSELERAKU) * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+2);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+2), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+2);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+2), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // == RENDANG ==
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 3), "RENDANG")->getStyle("D" . (($rowStart + $i) + 3))->applyFromArray($this->styling_title_template('FF933DE3', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 3), $report->TGTRENDANG)->getStyle($abjad . (($rowStart + $i) + 3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 3), $reals['m'.$j][3])->getStyle($abjad . (($rowStart + $i) + 3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 3), ($reals['m'.$j][3] != 0 ? round(((int)$reals['m'.$j][3]/$report->TGTRENDANG) * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+3);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+3), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+3);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+3), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // // == GEPREK ==
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 4), "GEPREK")->getStyle("D" . (($rowStart + $i) + 4))->applyFromArray($this->styling_title_template('FFC9931E', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 4), $report->TGTGEPREK)->getStyle($abjad . (($rowStart + $i) + 4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 4), $reals['m'.$j][4])->getStyle($abjad . (($rowStart + $i) + 4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 4), ($reals['m'.$j][4] != 0 ? round((int)$reals['m'.$j][4]/$report->TGTGEPREK * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+4);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+4), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+4);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+4), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AO' . ($rowStart + $i), $DataUST['RATA']['UST'])->getStyle('AO' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AO' . (($rowStart + $i) + 1), $DataUST['RATA']['NONUST'])->getStyle('AO' . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AO' . (($rowStart + $i) + 2), $DataUST['RATA']['SELERAKU'])->getStyle('AO' . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            // $ObjSheet->setCellValue('AP' . ($rowStart + $i), $DataUST['AVG']['UST'])->getStyle('AP' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AP' . (($rowStart + $i) + 1), $DataUST['AVG']['NONUST'])->getStyle('AP' . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AP' . (($rowStart + $i) + 2), $DataUST['AVG']['SELERAKU'])->getStyle('AP' . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            // $ObjSheet->setCellValue('AQ' . ($rowStart + $i), $DataUST['RATIO']['UST'])->getStyle('AQ' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AQ' . (($rowStart + $i) + 1), $DataUST['RATIO']['NONUST'])->getStyle('AQ' . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AQ' . (($rowStart + $i) + 2), $DataUST['RATIO']['SELERAKU'])->getStyle('AQ' . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $rowStart++;
            $i += 4;
        }

        $fileName = 'TREND - RPO - ' . $year;
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function generate_trend_asmen($reports, $year)
    {
        
        $spreadsheet = new Spreadsheet();
        $ObjSheet = $spreadsheet->getActiveSheet(0);
        $ObjSheet->setTitle("TREND RPC");

        $ObjSheet->getColumnDimension('A')->setWidth('2');
        $ObjSheet->getColumnDimension('B')->setWidth('2');
        $ObjSheet->getColumnDimension('C')->setWidth('20');
        $ObjSheet->getColumnDimension('D')->setWidth('10');

        $ObjSheet->getColumnDimension('E')->setWidth('8');
        $ObjSheet->getColumnDimension('F')->setWidth('8');
        $ObjSheet->getColumnDimension('G')->setWidth('8');
        $ObjSheet->getColumnDimension('H')->setWidth('8');
        $ObjSheet->getColumnDimension('I')->setWidth('8');
        $ObjSheet->getColumnDimension('J')->setWidth('8');
        $ObjSheet->getColumnDimension('K')->setWidth('8');
        $ObjSheet->getColumnDimension('L')->setWidth('8');
        $ObjSheet->getColumnDimension('M')->setWidth('8');
        $ObjSheet->getColumnDimension('N')->setWidth('8');
        $ObjSheet->getColumnDimension('O')->setWidth('8');
        $ObjSheet->getColumnDimension('P')->setWidth('8');
        $ObjSheet->getColumnDimension('Q')->setWidth('8');
        $ObjSheet->getColumnDimension('R')->setWidth('8');
        $ObjSheet->getColumnDimension('S')->setWidth('8');
        $ObjSheet->getColumnDimension('T')->setWidth('8');
        $ObjSheet->getColumnDimension('U')->setWidth('8');
        $ObjSheet->getColumnDimension('V')->setWidth('8');
        $ObjSheet->getColumnDimension('W')->setWidth('8');
        $ObjSheet->getColumnDimension('X')->setWidth('8');
        $ObjSheet->getColumnDimension('Y')->setWidth('8');
        $ObjSheet->getColumnDimension('Z')->setWidth('8');
        $ObjSheet->getColumnDimension('AA')->setWidth('8');
        $ObjSheet->getColumnDimension('AB')->setWidth('8');
        $ObjSheet->getColumnDimension('AC')->setWidth('8');
        $ObjSheet->getColumnDimension('AD')->setWidth('8');
        $ObjSheet->getColumnDimension('AE')->setWidth('8');
        $ObjSheet->getColumnDimension('AF')->setWidth('8');
        $ObjSheet->getColumnDimension('AG')->setWidth('8');
        $ObjSheet->getColumnDimension('AH')->setWidth('8');
        $ObjSheet->getColumnDimension('AI')->setWidth('8');
        $ObjSheet->getColumnDimension('AJ')->setWidth('8');
        $ObjSheet->getColumnDimension('AK')->setWidth('8');
        $ObjSheet->getColumnDimension('AL')->setWidth('8');
        $ObjSheet->getColumnDimension('AM')->setWidth('8');
        $ObjSheet->getColumnDimension('AN')->setWidth('8');
        
        $ObjSheet->getColumnDimension('AO')->setWidth('13');
        $ObjSheet->getColumnDimension('AP')->setWidth('13');
        $ObjSheet->getColumnDimension('AQ')->setWidth('13');

        $ObjSheet->getRowDimension('4')->setRowHeight('25');

        // $ObjSheet->mergeCells('B2:B4')->setCellValue('B2', date('Y'))->getStyle('B2:B4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $ObjSheet->mergeCells('C2:C4')->setCellValue('C2', "AREA")->getStyle('C2:C4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('D2:D4')->setCellValue('D2', "ITEM")->getStyle('D2:D4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('E2:AN2')->setCellValue('E2', "REALISASI")->getStyle('E2:AN2')->applyFromArray($this->styling_title_template('FF00B0F0', 'FF000000'));

        $ObjSheet->mergeCells('E3:G3')->setCellValue('E3', "JAN")->getStyle('E3:G3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('H3:J3')->setCellValue('H3', "FEB")->getStyle('H3:J3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('K3:M3')->setCellValue('K3', "MAR")->getStyle('K3:M3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('N3:P3')->setCellValue('N3', "APR")->getStyle('N3:P3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('Q3:S3')->setCellValue('Q3', "MEI")->getStyle('Q3:S3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('T3:V3')->setCellValue('T3', "JUN")->getStyle('T3:V3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('W3:Y3')->setCellValue('W3', "JUL")->getStyle('W3:Y3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('Z3:AB3')->setCellValue('Z3', "AUG")->getStyle('Z3:AB3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AC3:AE3')->setCellValue('AC3', "SEP")->getStyle('AC3:AE3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AF3:AH3')->setCellValue('AF3', "OKT")->getStyle('AF3:AH3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AI3:AK3')->setCellValue('AI3', "NOV")->getStyle('AI3:AK3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));
        $ObjSheet->mergeCells('AL3:AN3')->setCellValue('AL3', "DES")->getStyle('AL3:AN3')->applyFromArray($this->styling_title_template('FFFFFFFF', 'FF000000'));

        $ObjSheet->setCellValue('E4', 'TGT')->getStyle('E4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('F4', 'REAL')->getStyle('F4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('G4', '%VSTGT')->getStyle('G4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('H4', 'TGT')->getStyle('H4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('I4', 'REAL')->getStyle('I4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('J4', '%VSTGT')->getStyle('J4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('K4', 'TGT')->getStyle('K4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('L4', 'REAL')->getStyle('L4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('M4', '%VSTGT')->getStyle('M4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('N4', 'TGT')->getStyle('N4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('O4', 'REAL')->getStyle('O4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('P4', '%VSTGT')->getStyle('P4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('Q4', 'TGT')->getStyle('Q4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('R4', 'REAL')->getStyle('R4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('S4', '%VSTGT')->getStyle('S4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('T4', 'TGT')->getStyle('T4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('U4', 'REAL')->getStyle('U4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('V4', '%VSTGT')->getStyle('V4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('W4', 'TGT')->getStyle('W4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('X4', 'REAL')->getStyle('X4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('Y4', '%VSTGT')->getStyle('Y4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('Z4', 'TGT')->getStyle('Z4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AA4', 'REAL')->getStyle('AA4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AB4', '%VSTGT')->getStyle('AB4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AC4', 'TGT')->getStyle('AC4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AD4', 'REAL')->getStyle('AD4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AE4', '%VSTGT')->getStyle('AE4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AF4', 'TGT')->getStyle('AF4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AG4', 'REAL')->getStyle('AG4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AH4', '%VSTGT')->getStyle('AH4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AI4', 'TGT')->getStyle('AI4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AJ4', 'REAL')->getStyle('AJ4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AK4', '%VSTGT')->getStyle('AK4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AL4', 'TGT')->getStyle('AL4')->applyFromArray($this->styling_content_template('FFF4B084', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AM4', 'REAL')->getStyle('AM4')->applyFromArray($this->styling_content_template('FFBDD7EE', '00000000'))->getAlignment()->setWrapText(true);
        $ObjSheet->setCellValue('AN4', '%VSTGT')->getStyle('AN4')->applyFromArray($this->styling_content_template('FFE60000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
        
        $ObjSheet->mergeCells('AO2:AO4')->setCellValue('AO2', "RATA2")->getStyle('AO2:AO4')->applyFromArray($this->styling_title_template('FFFFFF00', 'FF000000'));
        $ObjSheet->mergeCells('AP2:AP4')->setCellValue('AP2', "% AVG")->getStyle('AP2:AP4')->applyFromArray($this->styling_title_template('FFE60000', 'FFFFFFFF'));
        // $ObjSheet->mergeCells('AQ2:AQ4')->setCellValue('AQ2', "% 2022 vs 2021")->getStyle('AQ2:AQ4')->applyFromArray($this->styling_title_template('FF0000FF', 'FFFFFFFF'));
        $rowStart = 5;
        $i = 0;
        foreach ($reports as $report) {
            $ObjSheet->mergeCells('C' . ($rowStart + $i) . ':C' . (($rowStart + $i) + 4))->setCellValue('C' . ($rowStart + $i), $report->LOCATION_STL)->getStyle('C' . ($rowStart + $i) . ':C' . (($rowStart + $i) + 4))->applyFromArray($this->styling_title_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $reals['m1'] = explode(';', $report->M1);
            $reals['m2'] = explode(';', $report->M2);
            $reals['m3'] = explode(';', $report->M3);
            $reals['m4'] = explode(';', $report->M4);
            $reals['m5'] = explode(';', $report->M5);
            $reals['m6'] = explode(';', $report->M6);
            $reals['m7'] = explode(';', $report->M7);
            $reals['m8'] = explode(';', $report->M8);
            $reals['m9'] = explode(';', $report->M9);
            $reals['m10'] = explode(';', $report->M10);
            $reals['m11'] = explode(';', $report->M11);
            $reals['m12'] = explode(';', $report->M12);

            // == UST == 
            // $ObjSheet->setCellValue('B' . ($rowStart + $i), $DataUST['ITEM_LAST_YEAR']['UST'])->getStyle('B' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            $ObjSheet->setCellValue("D" . ($rowStart + $i), "UST")->getStyle("D" . ($rowStart + $i))->applyFromArray($this->styling_title_template('FFFF0000', 'FFFFFFFF'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $report->TGTUST)->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $reals['m'.$j][0])->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), ($reals['m'.$j][0] != 0 ? round(((int)$reals['m'.$j][0]/$report->TGTUST) * 100) : 0) . '%')->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }
            
            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.($rowStart + $i);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $avgRealCell)->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.($rowStart + $i);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . ($rowStart + $i), $avgRealCell)->getStyle($abjad . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // == NON UST == 
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 1), "NONUST")->getStyle("D" . (($rowStart + $i) + 1))->applyFromArray($this->styling_title_template('FFFFFF00', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 1), $report->TGTNONUST)->getStyle($abjad . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 1), $reals['m'.$j][1])->getStyle($abjad . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 1), ($reals['m'.$j][1] != 0 ? round(((int)$reals['m'.$j][1]/$report->TGTNONUST) * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+1);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+1), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+1);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+1), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // == SELERAKU ==
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 2), "SELERAKU")->getStyle("D" . (($rowStart + $i) + 2))->applyFromArray($this->styling_title_template('FF00FF00', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 2), $report->TGTSELERAKU)->getStyle($abjad . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 2), $reals['m'.$j][2])->getStyle($abjad . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 2), ($reals['m'.$j][2] != 0 ? round(((int)$reals['m'.$j][2]/$report->TGTSELERAKU) * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+2);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+2), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+2);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+2), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // == RENDANG ==
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 3), "RENDANG")->getStyle("D" . (($rowStart + $i) + 3))->applyFromArray($this->styling_title_template('FF933DE3', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 3), $report->TGTRENDANG)->getStyle($abjad . (($rowStart + $i) + 3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 3), $reals['m'.$j][3])->getStyle($abjad . (($rowStart + $i) + 3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 3), ($reals['m'.$j][3] != 0 ? round(((int)$reals['m'.$j][3]/$report->TGTRENDANG) * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+3);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+3), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+3);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+3), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+3))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // // == GEPREK ==
            $ObjSheet->setCellValue("D" . (($rowStart + $i) + 4), "GEPREK")->getStyle("D" . (($rowStart + $i) + 4))->applyFromArray($this->styling_title_template('FFC9931E', '00000000'))->getAlignment()->setWrapText(true);
            $abjad = "D";
            for($j = 1; $j <= 12; $j++){
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 4), $report->TGTGEPREK)->getStyle($abjad . (($rowStart + $i) + 4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 4), $reals['m'.$j][4])->getStyle($abjad . (($rowStart + $i) + 4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
                $ObjSheet->setCellValue(++$abjad . (($rowStart + $i) + 4), ($reals['m'.$j][4] != 0 ? round((int)$reals['m'.$j][4]/$report->TGTGEPREK * 100) : 0) . '%')->getStyle($abjad . (($rowStart + $i) + 4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            }

            $col         = "F";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+4);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+4), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            $col         = "G";
            $temp        = [];
            for($x = 1; $x <= 12; $x++){
                $temp[] = $col++.(($rowStart + $i)+4);
            }
            $avgRealCell = "=AVERAGE(".implode(', ', $temp).")";
            $ObjSheet->setCellValue(++$abjad . (($rowStart + $i)+4), $avgRealCell)->getStyle($abjad . (($rowStart + $i)+4))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AO' . ($rowStart + $i), $DataUST['RATA']['UST'])->getStyle('AO' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AO' . (($rowStart + $i) + 1), $DataUST['RATA']['NONUST'])->getStyle('AO' . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AO' . (($rowStart + $i) + 2), $DataUST['RATA']['SELERAKU'])->getStyle('AO' . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            // $ObjSheet->setCellValue('AP' . ($rowStart + $i), $DataUST['AVG']['UST'])->getStyle('AP' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AP' . (($rowStart + $i) + 1), $DataUST['AVG']['NONUST'])->getStyle('AP' . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AP' . (($rowStart + $i) + 2), $DataUST['AVG']['SELERAKU'])->getStyle('AP' . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);

            // $ObjSheet->setCellValue('AQ' . ($rowStart + $i), $DataUST['RATIO']['UST'])->getStyle('AQ' . ($rowStart + $i))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AQ' . (($rowStart + $i) + 1), $DataUST['RATIO']['NONUST'])->getStyle('AQ' . (($rowStart + $i) + 1))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            // $ObjSheet->setCellValue('AQ' . (($rowStart + $i) + 2), $DataUST['RATIO']['SELERAKU'])->getStyle('AQ' . (($rowStart + $i) + 2))->applyFromArray($this->styling_content_template('00FFFFFF', '00000000'))->getAlignment()->setWrapText(true);
            
            $rowStart++;
            $i += 4;
        }

        $fileName = 'TREND - RPC - '.$year;
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
