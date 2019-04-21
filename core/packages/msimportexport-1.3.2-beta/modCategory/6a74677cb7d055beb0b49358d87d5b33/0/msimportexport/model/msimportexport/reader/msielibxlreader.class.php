<?php
require_once dirname(__FILE__) . '/msiereader.class.php';


class MsieLibXlReader extends MsieReader
{
    private $useXlsxFormat = true;

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
            $xlBook = new ExcelBook(null, null, $this->useXlsxFormat);
            $xlBook->setLocale('UTF-8');
            $xlBook->loadFile($this->provider['file']);
            $xlSheet = $xlBook->getSheet(0);
            $chunkSize = (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50);
            $seek = 1;

            $totalRow = $xlSheet->lastRow();

            if (isset($this->provider['seek']) && !empty($this->provider['seek'])) {
                $seek = $this->provider['seek'];
            }

            if ($seek + $chunkSize > $totalRow) {
                $chunkSize = $totalRow - $seek;
            }


            for ($r = 0; $r < $chunkSize; $r++) {
                $index = $seek + $r;
               // $this->modx->log(modX::LOG_LEVEL_ERROR, 'index=' . $index);
                $data = array();
                for ($c = $xlSheet->firstCol(); $c <= $xlSheet->lastCol(); $c++) {
                    $data[] = $xlSheet->read($index, $c);
                }

                $index++;

                if ($index >= $totalRow) {
                    $this->setSeek(-1);
                } else {
                    $this->setSeek($index);
                }

                if (is_callable($callback)) {
                    if ($callback($this, $data) !== true) {
                        unset($xlBook);
                        unset($data);
                        unset($xlSheet);
                        return true;
                    }
                }

            }
            unset($data);
            unset($xlBook);
            unset($xlSheet);
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
    }


}
