<?php

namespace BeSimple\GearmanBundle\Gearman;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class Gearman
{
    const OPTION_RUN_TYPE = 'run';
    const OPTION_PRIORITY = 'priority';

    const RUN_AND_WAIT    = 1;
    const RUN_BACKGROUND  = 2;

    const PRIORITY_LOW    = 1;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_HIGH   = 3;

    static public function serialize($data)
    {
        return is_scalar($data) ? $data : serialize($data);
    }

    static public function unserialize($data)
    {
        $r = @unserialize($data);

        if ($r !== false || $data === 'b:0;') {
            return $r;
        }

        return $data;
    }
}
