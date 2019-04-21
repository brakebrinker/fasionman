<?php
require_once dirname(__FILE__) . '/msiewriter.class.php';

class MsieCsvWriter extends MsieWriter
{
    private $delimiter = ';';
    private $enclosure = '"';
    private $lineEnding = "\r\n";//PHP_EOL;
    private $handle = null;
    private $out = '';

    public function __construct(& $modx)
    {
        parent::__construct($modx);
        $this->delimiter = $this->modx->getOption('msimportexport.export.delimeter', null, ';');
    }


    public function save($filename = '', $path = '')
    {
        $filename = empty($filename) ? 'export_' . date('d_m_Y_H_i_s') . '.csv' : $filename;
        if (empty($path)) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            return $this->out;
        } else {
            if (!$this->handle = fopen($path . $filename, 'wb+'))
                return false;
            fwrite($this->handle, $this->out);
            fclose($this->handle);
        }
    }


    public function write(array $data, array $options = array())
    {
        if (is_array($data)) {
            // No leading delimiter
            $writeDelimiter = false;

            // Build the line
            $line = '';

            foreach ($data as $element) {
                // Escape enclosures
                $element = str_replace($this->enclosure, $this->enclosure . $this->enclosure, $element);
                // Add delimiter
                if ($writeDelimiter) {
                    $line .= $this->delimiter;
                } else {
                    $writeDelimiter = true;
                }

                // Add enclosed string
                $line .= $this->enclosure . $element . $this->enclosure;
            }

            // Add line ending
            $line .= $this->lineEnding;
            $this->out .= $line;
            return true;
        } else {
            return false;
        }
    }

}
