<?php

namespace Languages\Mapper;

use Phoenix\Mapper\Xls as BaseXls;

use Zend\Stdlib\ArrayObject;

class Xls extends BaseXls
{
    const   lineDataStart   = 4;
    const   lineMaxData     = 1048576; // max number of cells per column, do not change this, because PHPExcel do a column styling with this number

    // DB and column data
    //const   DB_DATABASE = 0;
    const   DB_ITEMID   = 0;
    //const   DB_AREA     = 2;
    const   DB_NAME     = 1;
    const   DB_TEXT     = 2;
    const   DB_LANG     = 3;
    const   USER_TEXT   = 4;
    const   ERL_TRANS   = 5;
    //const   DB_ROWID    = 11;
    //const   DB_PTABLE   = 12;
    //const   DB_DFIELD   = 13;
    //const   DB_DSECTION = 14;
    //const   DB_TYPE     = 15;
    //const   OLD_HTML    = 4; // compatibility
    //const   MASTER_TEXT = 6;
    //const   COL_COMPAT3 = 8;

    protected $unwrappedArray = array();

    protected $lineTagColor = '00666666'; // argb
    protected $allStyles = array(
        // self::COL_COMPAT3 => array(
        //     'column'    => array(
        //         'name'      => 'ah',
        //         'visible'   => false,
        //         'width'     => 5,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // ),
        // self::MASTER_TEXT => array(
        //     'column'    => array(
        //         'name'      => 'full masterText',
        //         'visible'   => false,
        //         'width'     => 44,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => array(
        //         'alignment' => array('wrap' => false)
        //     )
        // ),
        // self::OLD_HTML => array(
        //     'column'    => array(
        //         'name'      => 'eh',
        //         'visible'   => false,
        //         'width'     => 5,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // ),
        // self::DB_DATABASE => array(
        //     'column'    => array(
        //         'name'      => 'DataBase',
        //         'visible'   => false,
        //         'width'     => 10,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // ),
        self::DB_ITEMID => array(
            'column'    => array(
                'name'      => 'itemID',
                'visible'   => true,
                'width'     => 10,
                'style'     => array(
                    'locked' => true,
                    'font' => array('bold' => true),
                    'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
                )
            ),
            'cell'      => null
        ),
        // self::DB_AREA => array(
        //     'column'    => array(
        //         'name'      => 'area',
        //         'visible'   => true,
        //         'width'     => 24,
        //         'style'     => array(
        //             'locked' => true,
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => array(
        //         'alignment' => array('wrap' => false)
        //     )
        // ),
        self::DB_NAME => array(
            'column'    => array(
                'name'      => 'field',
                'visible'   => true,
                'width'     => 24,
                'style'     => array(
                    'locked' => true,
                    'font' => array('bold' => true),
                    'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
                )
            ),
            'cell'      => array(
                'alignment' => array('wrap' => false)
            )
        ),
        self::DB_TEXT => array(
            'column'    => array(
                'name'      => 'masterText',
                'visible'   => true,
                'width'     => 44,
                'style' => array(
                    'locked' => true,
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'wrap'=>false),
                    'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('argb' => 'FF276E99'))
                )
            ),
            'cell'      => array(
                'alignment' => array(
                    'horizontal'    => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            )
        ),
        self::DB_LANG => array(
            'column'    => array(
                'name'      => 'langCode',
                'visible'   => true,
                'width'     => 10,
                'style' => array(
                    'locked' => true,
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'wrap'=>false),
                    'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
                )
            ),
            'cell'      => array(
                'alignment' => array('horizontal'   => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            )
        ),
        self::USER_TEXT => array(
            'column'    => array(
                'name'      => 'langText',
                'visible'   => true,
                'width'     => 44,
                'style' => array(
                    'locked' => true,
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'wrap'=>false),
                    'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('argb' => 'FF009E00'))
                )
            ),
            'cell'      => array(
                'alignment' => array(
                    'horizontal'    => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            )
        ),
        self::ERL_TRANS => array(
            'column'    => array(
                'name'      => 'earlierTranslation',
                'visible'   => true,
                'width'     => 44,
                'style' => array(
                    'locked' => true,
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'wrap'=>false),
                    'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('argb' => 'FF948B54'))
                )
            ),
            'cell'      => array(
                'font' => array('color' => array('argb' => 'FF974807')),
                'alignment' => array(
                    'horizontal'    => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                )
        ),
        // self::DB_ROWID => array(
        //     'column'    => array(
        //         'name'      => 'parentRowID',
        //         'visible'   => false,
        //         'width'     => 30,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // ),
        // self::DB_PTABLE => array(
        //     'column'    => array(
        //         'name'      => 'parentTable',
        //         'visible'   => false,
        //         'width'     => 30,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // ),
        // self::DB_DFIELD => array(
        //     'column'    => array(
        //         'name'      => 'dynamicField',
        //         'visible'   => false,
        //         'width'     => 30,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // ),
        // self::DB_DSECTION => array(
        //     'column'    => array(
        //         'name'      => 'dataSection',
        //         'visible'   => false,
        //         'width'     => 30,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        // ),
        //     'cell'      => null
        // ),
        // self::DB_TYPE => array(
        //     'column'    => array(
        //         'name'      => 'type',
        //         'visible'   => false,
        //         'width'     => 30,
        //         'style'     => array(
        //             'font' => array('bold' => true),
        //             'borders' => array('bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        //         )
        //     ),
        //     'cell'      => null
        // )
    );


    protected $generalStyle =    array(
            'alignment' => array(
                'vertical'      => \PHPExcel_Style_Alignment::VERTICAL_TOP,
                'wrap'          => true
            )
    );

    protected $sheetHeader = array(
        'A1'    => array(
            'text' => 'TEXT FOR TRANSLATION',
            'style' => array(
                    'locked' => true,
                    'font' => array(
                        'bold' => true,
                        'size' => 20,
                        'color' => array('argb' => 'FF009E00')),
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'wrap'          => false),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('argb' => 'FFAAAAAA'))
            )
        ),
        'A2'    => array(
            'text' => 'Changes to the master English text will be ignored!',
            'style' => array(
                    'locked' => true,
                    'font' => array(
                        'bold' => true,
                        'size' => 14,
                        'color' => array('argb' => 'FF9E0000')),
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'wrap'          => false),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('argb' => 'FFAAAAAA'))
            )
        )
    );

    public function build()
    {
        $phpExcel = $this->getPhpExcel();

        //Prepare common style sheet
        $this->commonSheet($phpExcel->getActiveSheet());

        foreach ($this->getData() as $keySheet => $valSheet) {
            $componentSheet = clone ($phpExcel->getSheet(0));
            $phpExcel->addSheet($componentSheet);

            $componentSheet->setTitle($keySheet);

            $this->buildSheet($componentSheet, $valSheet);
            // echo '<pre>';
            // var_dump($componentSheet);
            // echo '</pre>';
            // die;
            
        }
    }

    public function getUnwrappedArray()
    {
        return $this->unwrappedArray;
    }

    public function buildSheet($sheet, $sheetData)
    {
        $stringFromColumnIndex = array(
            self::USER_TEXT     => \PHPExcel_Cell::stringFromColumnIndex(self::USER_TEXT),
            self::DB_TEXT       => \PHPExcel_Cell::stringFromColumnIndex(self::DB_TEXT),
            self::ERL_TRANS     => \PHPExcel_Cell::stringFromColumnIndex(self::ERL_TRANS),
            self::DB_ITEMID     => \PHPExcel_Cell::stringFromColumnIndex(self::DB_ITEMID),
            self::DB_NAME       => \PHPExcel_Cell::stringFromColumnIndex(self::DB_NAME),
            self::DB_LANG       => \PHPExcel_Cell::stringFromColumnIndex(self::DB_LANG),
        );

        $line = self::lineDataStart;

        $languages = $this->getServiceManager()->get('phoenix-languages');

        //Get Language Options
        $languageOptions = $languages->getLanguageOptions();

        $defaultLanguage = $languages->getDefaultLanguage();

        foreach ($sheetData as $keyItem => $valItem) {
            if ($line > self::lineMaxData) {
                break;
            }

            $itemId = $keyItem;

            if (empty($valItem['fields'])) {
                //If there are no fields for this item, don't create blank lines.
                continue;
            }

            foreach ($valItem['fields'] as $keyField => $valField) {
                //If no default value, don't translate.
                if (empty($valField['defaultLanguageValue'])) {
                    continue;
                }

                $dbText = $valField['defaultLanguageValue'];

                if (empty($valField['translations'])) {
                    continue;
                }

                foreach ($languageOptions as $keyOption => $valOption) {
                    //Don't want rows for the default language.
                    if ($keyOption == $defaultLanguage->getCode()) {
                        continue;
                    }                    
                    $languageCode = $keyOption;
                    if (!empty($valField['translations'][$keyOption])) {
                        $previousTranslation = $userTranslation = $valField['translations'][$keyOption];
                    } else {
                        $previousTranslation = $userTranslation = '';
                    }

                    $sheet->setCellValueExplicit($stringFromColumnIndex[self::DB_NAME] . $line, $keyField);
                    $sheet->setCellValueExplicit($stringFromColumnIndex[self::DB_ITEMID] . $line, $itemId);
                    $sheet->setCellValueExplicit($stringFromColumnIndex[self::DB_LANG] . $line, $languageCode);
                    $sheet->setCellValueExplicit($stringFromColumnIndex[self::DB_TEXT] . $line, $dbText);
                    $sheet->setCellValueExplicit($stringFromColumnIndex[self::USER_TEXT] . $line, $userTranslation);
                    $sheet->setCellValueExplicit($stringFromColumnIndex[self::ERL_TRANS] . $line, $previousTranslation);

                    $line++;
                }
            }
        }
    }


    /**
    * Gives a sheet a standard style.
    *
    * @param    $sheet  sheet to customize
    *
    */
    protected function commonSheet($sheet)
    {
        foreach ( $this->allStyles as $column => $style )
        {
            $letterColumn = \PHPExcel_Cell::stringFromColumnIndex($column);

            $col =& $style['column'];

            $sheet->getColumnDimension( $letterColumn ) ->setWidth      ( $col['width'] )
                                                        ->setVisible    ( $col['visible'] );

            // colum title and style
            $sheet->setCellValueExplicit($letterColumn . (self::lineDataStart -1), $col['name'] );
            $sheet->getStyle( $letterColumn . (self::lineDataStart -1) )->applyFromArray( $col['style'] );

            if(!$style['cell']) continue; // this saves memory if no related styles with the column

            $sheet->getStyle($letterColumn .'1:'. $letterColumn . self::lineMaxData)->applyFromArray( $style['cell'] );
            //$sheet->getStyle($letterColumn . self::lineDataStart .':'. $letterColumn . self::lineMaxData)->applyFromArray( $style['cell'] );

        }

        $sheet->getProtection()->setSheet(true); // protects all sheet
        //$sheet->protectCells('A'. (self::lineDataStart -1) .':K'. (self::lineDataStart -1) , 'PHPExcel'); // protect column title with password

        $letterColumn = \PHPExcel_Cell::stringFromColumnIndex(self::USER_TEXT);
        $sheet->getStyle($letterColumn .'1:'. $letterColumn . self::lineMaxData)->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $letterColumn = \PHPExcel_Cell::stringFromColumnIndex(self::ERL_TRANS);
        $sheet->getStyle($letterColumn .'1:'. $letterColumn . self::lineMaxData)->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        //$sheet->getProtection()->setInsertRows(false);

        // sheet header
        $sheet->mergeCells('A1:F1')->mergeCells('A2:F2');

        foreach( $this->sheetHeader as $cell => $style )
        {
            $sheet  ->setCellValue( $cell, $style['text'] )
                    ->getStyle( $cell )->applyFromArray( $style['style'] );
        }
    }    

    public function unwrap()
    {
        $unwrappedArray = new ArrayObject();

        foreach ($this->getPhpExcel()->getWorksheetIterator() as $valSheet) {
            $this->unwrapSheet($valSheet, $unwrappedArray);
        }

        $this->unwrappedArray = $unwrappedArray;
    }

    public function unwrapSheet($sheet, $unwrappedArray)
    {
        $highestRow = (int) $sheet->getHighestRow();

        $sheetArray = new ArrayObject();

        $row = self::lineDataStart;

        while ($row <= $highestRow) {
            $itemId = (int) $sheet->getCellByColumnAndRow(self::DB_ITEMID, $row)->getValue();

            if(empty($itemId)) // do not process blank cells without itemId
            {
                ++$row;
                continue;
            }

            if (empty($sheetArray[$itemId])) {
                $sheetArray[$itemId] = new ArrayObject();
                $sheetArray[$itemId]['fields'] = new ArrayObject();
            }

            $fields = $sheetArray[$itemId]['fields'];

            $fieldName = $sheet->getCellByColumnAndRow(self::DB_NAME, $row)->getValue();

            if (empty($fields[$fieldName])) {
                $defaultLanguageValue = $sheet->getCellByColumnAndRow(self::DB_TEXT, $row)->getValue();
                $fields[$fieldName] = new ArrayObject();
                $fields[$fieldName]['defaultLanguageValue'] = $defaultLanguageValue;
                $fields[$fieldName]['translations'] = new ArrayObject();
            }

            $languageCode = $sheet->getCellByColumnAndRow(self::DB_LANG, $row)->getValue();

            $value = $sheet->getCellByColumnAndRow(self::USER_TEXT, $row)->getValue();

            if (!empty($value)) {
                $fields[$fieldName]['translations'][$languageCode] = $value;
            }
            
            ++$row;
        }

        $unwrappedArray[$sheet->getTitle()] = $sheetArray;
    }

    public function prepForSave()
    {
        $this->getPhpExcel()->removeSheetByIndex(0);
        $this->getPhpExcel()->setActiveSheetIndex(0);
        $this->getPhpExcel()->getProperties()
            ->setCreator("TravelClick")
            ->setLastModifiedBy("TravelClick")
            ->setTitle('Language Translations')
            ->setSubject('Language Translations')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');  
    }
}