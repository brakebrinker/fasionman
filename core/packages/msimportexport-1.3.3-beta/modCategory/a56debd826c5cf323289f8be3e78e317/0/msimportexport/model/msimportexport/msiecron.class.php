<?php

class MsieCron extends xPDOSimpleObject
{
    /** @var int */
    const STATUS_WAIT = 1;

    /** @var int */
    const STATUS_RUN = 2;

    /** @var Msie $msie */
    private $msie = null;

    /**
     * @return bool
     */
    public function isDue()
    {
        $cron = Cron\CronExpression::factory($this->getSchedule());
        if (($cron->isDue() && !$this->isRun()) || !$this->isRun() && $this->status == self::STATUS_RUN) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Msie $msie
     * @return bool
     */
    public function run()
    {

        Msie::lockFile($this->getLockFileName());
        $this->getMsie();
        $result = array();
        $pid = getmypid();
        $params = $this->get('params');

        if (!$this->seek) {
            if (!$filename = $this->msie->copyFile($params['file'])) return false;
            $this->set('date_start', time());
            $this->log("[Cron] Start\n\tType: {$this->type}\n\tPid: {$pid}\n\tFile:{$filename}\n\n");
        } else {
            $filename = pathinfo($params['file'], PATHINFO_BASENAME);
        }

        $this->set('iteration', $this->get('iteration') + 1);
        $this->set('status', self::STATUS_RUN);
        $this->set('date_last_ran', time());
        $this->set('pid', $pid);

        if (!$this->save()) {
            $this->log("[Cron] Error save state (before)\n\tType:{$this->type}\n\tPid:{$pid}\n\tFile:{$filename}\n\n");
            return false;
        }

        switch ($this->type) {
            case 1:
                $result = $this->msie->import($filename, $params['preset'], $params['type'], $this->seek);
                break;
        }

        if (empty($result)) {
            $this->log("[Cron] Error result empty\n\tType:{$this->type}\n\tPid:{$this->pid}\n\tFile:{$filename}\n\tIteration number:{$this->iteration}\n\n");
            return false;
        }

        $resultStr = print_r($result, 1);

        if ($result['seek'] > 0) {
            $this->log("[Cron] Iteration\n\tType: {$this->type}\n\tPid:{$this->pid}\n\tFile:{$filename}\n\tIteration number:{$this->iteration}\n\tResult:{$resultStr}\n\n");
            $this->set('seek', $result['seek']);
        } else {
            $totalTime = time() - $this->date_start;
            $totalTime = $totalTime >= 86400 ? date('Y-m-d H:i:s', $totalTime) : date('H:i:s', $totalTime);
            $this->log("[Cron] Complete\n\tType: {$this->type}\n\tPid:{$this->pid}\n\tFile:{$filename}\n\tIteration number:{$this->iteration}\n\tResult:{$resultStr}\n\tTotal time:{$totalTime}\n\n");
            $this->reset();
        }

        if (!$this->save()) {
            $this->log("[Cron] Error save state (after)\n\tType:{$this->type}\n\tPid:{$this->pid}\n\tFile:{$filename}\n\n");
            return false;
        }
        return true;
    }

    public function abort()
    {
        if ($filename = $this->getLockFileName()) {
            Msie::unLockFile($filename);
            @posix_kill($this->pid, SIGKILL);
        }
        return $this->reset(true);
    }


    /**
     * @return bool
     */
    public function isRun()
    {
        return $this->status == self::STATUS_RUN && Msie::isLockFile($this->getLockFileName()); // posix_kill(intval($this->pid), 0)
    }

    /**
     * @param Msie $msie
     */
    public function setMsie(Msie & $msie)
    {
        $this->msie = &$msie;
    }

    /**
     * @return Msie|null|object
     */
    public function getMsie()
    {
        if ($this->msie) return $this->msie;
        $this->msie = $this->xpdo->getService('msimportexport', 'Msie', $this->xpdo->getOption('msimportexport.core_path', null, $this->xpdo->getOption('core_path') . 'components/msimportexport/') . 'model/msimportexport/', array());
        return $this->msie;
    }


    /**
     * @return string
     */
    private function getLockFileName()
    {
        return $this->pid_key ? $this->pid_key . '.lock' : '';
    }

    /**
     * @return string
     */
    private function getSchedule()
    {
        return implode(' ', $this->get('schedule'));
    }

    /**
     * @param bool $save
     * @return bool
     */
    private function reset($save = false)
    {
        $this->set('seek', 0);
        $this->set('pid', 0);
        $this->set('date_start', null);
        $this->set('iteration', 0);
        $this->set('status', self::STATUS_WAIT);
        return $save ? $this->save() : true;
    }

    /**
     * @param string $msg The message to log.
     * @param integer $level The level of the logged message.
     */
    private function log($msg, $level = xPDO::LOG_LEVEL_INFO)
    {
        if (filter_var($this->xpdo->getOption('msimportexport.import.cron_log', null, 0), FILTER_VALIDATE_BOOLEAN)) {
            $oldLevel = $this->xpdo->getLogLevel();
            if ($oldLevel != $level) $this->xpdo->setLogLevel($level);
            $this->xpdo->log($level, $msg);
            if ($oldLevel != $level) $this->xpdo->setLogLevel($oldLevel);
        }
    }
}