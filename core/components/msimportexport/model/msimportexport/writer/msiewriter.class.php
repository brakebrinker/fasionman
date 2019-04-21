<?php

interface MsieWriterInterface
{

    public function write(array $data, array $options);

}

abstract class MsieWriter implements MsieWriterInterface
{
    /** @var modX $modx */
    public $modx;

    /**
     * MsieWriter constructor.
     * @param $modx
     */
    public function __construct(& $modx)
    {
        $this->modx = &$modx;
    }
}

