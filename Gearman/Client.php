<?php

namespace BeSimple\GearmanBundle\Gearman;

use BeSimple\GearmanBundle\Gearman\Exception\ClientException;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class Client extends \GearmanClient
{
    protected $options = array(
        Gearman::OPTION_RUN_TYPE => Gearman::RUN_AND_WAIT,
        Gearman::OPTION_PRIORITY => Gearman::PRIORITY_NORMAL,
    );

    public function put($function, $workload, array $options = array())
    {
        $options = array_merge($this->options, $options);

        if ($diff = array_diff_key($options, $this->options)) {
            throw new \InvalidArgumentException(sprintf('%s does not support the following options: "%s".', get_class($this), implode('", "', array_keys($diff))));
        }

        $method = 'do';

        switch($options[Gearman::OPTION_PRIORITY]) {
            case Gearman::PRIORITY_LOW:
                    $method .= 'Low';
                break;
            case Gearman::PRIORITY_HIGH:
                    $method .= 'High';
                break;
        }

        if (Gearman::RUN_BACKGROUND === $options[Gearman::OPTION_RUN_TYPE]) {
            $method .= 'Background';
        }

        return $this->run($method, $function, $workload);
    }

    public function run($method, $function, $workload)
    {
        do {
            $result = $this->$method($function, Gearman::serialize($workload));

            switch($this->returnCode()) {
                case \GEARMAN_SUCCESS:
                        return Gearman::unserialize($result);
                    break;

                case \GEARMAN_WORK_DATA:
                        $data = $result;
                    break;

                case \GEARMAN_WORK_STATUS:
                case \GEARMAN_WORK_WARNING:
                    break;

                case \GEARMAN_WORK_FAIL:
                        throw new ClientException(sprintf('Worker failed "%s" (function: "%s")', $this->doJobHandle(), $function));
                    break;

                default:
                    throw new ClientException(sprintf('Client error "%s" (code: %d) (ret: %d) (function: "%s")', $this->error(), $this->getErrno(), $this->returnCode(), $function));
            }
        } while ($this->returnCode() !== \GEARMAN_SUCCESS);
    }
}
