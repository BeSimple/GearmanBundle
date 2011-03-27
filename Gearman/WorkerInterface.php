<?php

namespace BeSimple\GearmanBundle\Gearman;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
interface WorkerInterface
{
    function setFunctions();

    function execute();
}
