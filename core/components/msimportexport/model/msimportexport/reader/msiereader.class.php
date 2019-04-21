<?php

interface MsieReaderInterface
{

    public function read(array $provider, $callback = null);

}

abstract class MsieReader implements MsieReaderInterface
{
    /** @var modX $modx */
    public $modx;
    /** @var int $seek */
    protected $seek = 0;
    /** @var int $totalRows */
    protected $totalRows = null;
    /** @var array $provider */
    protected $provider = array();

    /**
     * MsieReader constructor.
     * @param $modx
     */
    public function __construct(& $modx)
    {
        $this->modx = &$modx;
    }

    /**
     * @param int $seek
     */
    public function setSeek($seek)
    {
        $this->seek = $seek;
    }

    /**
     * @return int
     */
    public function getTotalRows()
    {
       return  $this->totalRows;
    }

    /**
     * @return int
     */
    public function getSeek()
    {
        return $this->seek;
    }

}

