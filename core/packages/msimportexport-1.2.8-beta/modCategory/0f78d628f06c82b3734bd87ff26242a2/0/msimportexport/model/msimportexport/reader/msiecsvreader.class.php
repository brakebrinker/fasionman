<?php
require_once dirname(__FILE__) . '/msiereader.class.php';

class MsieCsvReader extends MsieReader
{

    public function read(array $provider, $callback = null)
    {
        $delimiter = $this->modx->getOption('msimportexport.delimeter', null, ';');
        $enclosure = $this->modx->getOption('msimportexport.import.csv_enclosure', null, '"');
        $escape = $this->modx->getOption('msimportexport.import.csv_escape', null, '\\');

        if (!file_exists($provider['file'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, sprintf($this->modx->lexicon('msimportexport.err_nf_file'), $provider['file']));
            return false;
        }

        if (!$handle = fopen($provider['file'], "r")) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, sprintf($this->modx->lexicon('msimportexport.err_open_file'), $provider['file']));
            return false;
        }

        $this->provider = $provider;

        if (isset($provider['seek']) && !empty($provider['seek'])) {
            fseek($handle, $this->provider['seek']);
        }

        while ($data = fgetcsv($handle, 0, $delimiter, $enclosure, $escape)) {
            if (is_callable($callback)) {
                if ($callback($this, $data) !== true) {
                    $this->setSeek(ftell($handle));
                    unset($data);
                    fclose($handle);
                    return true;
                }
            }
        }
        $this->setSeek(-1);
        fclose($handle);
        return true;
    }

    /**
     * @return int|null
     */
    public function getTotalRows()
    {
        if ($this->totalRows === null && !empty($this->provider)) {
            $this->totalRows = count(file($this->provider['file'], FILE_SKIP_EMPTY_LINES));
        }
        return $this->totalRows;
    }

}
