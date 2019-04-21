<?php
require_once dirname(__FILE__) . '/msiereader.class.php';

class ReadFilter implements PHPExcel_Reader_IReadFilter
{
    private $_startRow = 0;
    private $_endRow = 0;
    private $inputFileType = '';

    /**  Set the list of rows that we want to read  */
    public function setRows($startRow, $chunkSize)
    {
        $this->_startRow = $startRow;
        $this->_endRow = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
            return true;
        }
        return false;
    }
}

class MsieExcelReader extends MsieReader
{
    /** @var PHPExcel $objPHPExcel */
    private $objPHPExcel = null;

    public function __construct(& $modx)
    {

        parent::__construct($modx);
    }

    public function read(array $provider, $callback = null)
    {

        if (!file_exists($provider['file'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, sprintf($this->modx->lexicon('msimportexport.err_nf_file'), $provider['file']));
            return false;
        }

        $this->provider = $provider;

        if (!isset($this->provider['seek'])) {
            $this->provider['seek'] = 0;
        }

        try {
            if ($this->initPHPExcel()) {
                $objSheet = $this->objPHPExcel->getActiveSheet();
                $rowIterator = $objSheet->getRowIterator();
                $emptyValue = 0;
                $index = 1;

                if (isset($this->provider['seek']) && !empty($this->provider['seek'])) {
                    $index = $this->provider['seek'];
                    $rowIterator->resetStart($index);
                }

                while ($rowIterator->valid()) {
                    $cellIterator = $rowIterator->current()->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $data = array();
                    while ($cellIterator->valid()) {
                        //$val = $cellIterator->current()->getCalculatedValue();
                        $val = $cellIterator->current()->getValue();
                        if (!empty($val)) $emptyValue++;
                        $data[] = $val;
                        $cellIterator->next();
                    }

                    $rowIterator->next();
                    $index++;

                    if (empty($emptyValue)) {
                        $this->setSeek(-1);
                    } else {
                        $this->setSeek($index);
                    }

                    if (is_callable($callback)) {
                        if (empty($emptyValue) || $callback($this, $data) !== true) {
                            unset($data);
                            unset($objSheet);
                            $this->disconnect();
                            return true;
                        }
                    }
                    $emptyValue = 0;
                }
                unset($data);
                unset($objSheet);
                $this->disconnect();
            }
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[MsieExcelReader] Exception ' . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @return int|null
     */
    public function getTotalRows()
    {
        return -1;
        if ($this->totalRows === null && !empty($this->provider)) {
            if ($this->initPHPExcel()) {
                $objSheet = $this->objPHPExcel->getActiveSheet();
                $this->totalRows = $objSheet->getHighestRow();
            }
        }
        $this->disconnect();
        return $this->totalRows;
    }


    private function disconnect()
    {
        $this->objPHPExcel->disconnectWorksheets();
        unset($this->objPHPExcel);
    }

    /**
     * @return PHPExcel
     */
    private function initPHPExcel()
    {
        if (!$this->objPHPExcel) {

            $chunkSize = (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50);
            try {
                /*$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = array('memoryCacheSize' => '256MB');
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);*/
                PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip);

                $this->inputFileType = PHPExcel_IOFactory::identify($this->provider['file']);
                $objReader = PHPExcel_IOFactory::createReader($this->inputFileType);
                $objReader->setReadDataOnly(true);
                //  $objReader->setLoadSheetsOnly($sheetname);

                $readFilter = new ReadFilter();
                $readFilter->setRows((int)$this->provider['seek'], $chunkSize + 1);
                $objReader->setReadFilter($readFilter);

                $this->objPHPExcel = $objReader->load($this->provider['file']);
                $this->objPHPExcel->setActiveSheetIndex(0);

                unset($objReader);

            } catch (Exception $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[MsieExcelReader] Exception ' . $e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }

}
