<?php

namespace BeSimple\GearmanBundle\Gearman;

use BeSimple\GearmanBundle\Gearman\Exception\TimeoutException;
use BeSimple\GearmanBundle\Gearman\Exception\WorkerException;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
abstract class Worker extends \GearmanWorker implements WorkerInterface
{
    public function loop($maxIteration = null)
    {
        $i = 0;

        do {
            $i++;

            @$this->work();

            $this->checkReturn();
        } while (!$maxIteration || $i < $maxIteration);
    }

    public function checkReturn()
    {
        switch ($this->returnCode()) {
            case \GEARMAN_TIMEOUT:
                    throw new TimeoutException(sprintf('Worker error "%s" (code: %d)', $this->error(), $this->returnCode()));
                break;

            case \GEARMAN_SUCCESS:
                break;

            default:
                throw new WorkerException(sprintf('Worker error "%s" (code: %d)', $this->error(), $this->returnCode()));
        }
    }
}
