<?php
require_once dirname(__FILE__) . '/msiewriter.class.php';

class MsieExcelWriter extends MsieWriter
{
    /** @var PHPExcel $objPHPExcel */
    private $objPHPExcel = null;
    private $title = '';
    private $date = '';
    private $headStyle = '';
    private $sheet = null;
    private $seek = 1;

    public function __construct(& $modx)
    {
        $this->date = date('d-m-Y H:i:s');
        $this->title = 'miniShop2 export ' . $this->date;
        $this->headStyle = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
            )
        , 'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            ));

        PHPExcel_Shared_File::setUseUploadTempDirectory(true);
        PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip);

        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->getProperties()->setTitle($this->title)->setSubject($this->title);
        $this->objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $this->objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->sheet = $this->objPHPExcel->getActiveSheet();
        $this->sheet->setTitle('Export');

        parent::__construct($modx);
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->objPHPExcel->getProperties()->setTitle($this->title)->setSubject($this->title);
    }

    public function save($filename = '', $path = '')
    {
        $filename = empty($filename) ? 'export_' . date('d_m_Y_H_i_s') . '.xlsx' : $filename;
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        if (empty($path)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter->save('php://output');
        } else {
            $objWriter->save($path . $filename);
        }
        $this->objPHPExcel->disconnectWorksheets();
        unset($objWriter);
        unset($this->sheet);
        unset($this->objPHPExcel);
        gc_collect_cycles();
    }

    public function write(array $data, array $options = array('isHeader' => false))
    {

        foreach ($data as $k => $v) {
            $this->sheet->setCellValueByColumnAndRow($k, $this->seek, $v);
            $сell = $this->sheet->setCellValueByColumnAndRow($k, $this->seek, $v, true);
            if ($options['isHeader']) {
                $this->sheet->getStyleByColumnAndRow($k, $this->seek)->applyFromArray($this->headStyle);
            } else {
                $сell->setValueExplicit($v, PHPExcel_Cell_DataType::TYPE_STRING);
            }
            // $this->sheet->getColumnDimensionByColumn($k)->setAutoSize(true);
        }
        $this->seek++;
        return true;
    }
}
