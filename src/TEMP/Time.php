<?php

namespace TEMP;

use DateTime;

class Time
{

    public function convert($time, $tense = 'ago')
    {
        $periods = ['year', 'month', 'day', 'hour', 'minute', 'second'];

        if(!(strtotime($time)>0)) {
            return trigger_error("Wrong time format: '$time'", E_USER_ERROR);
        }

        $now    = new DateTime('now');
        $time   = new DateTime($time);
        $diff   = $now->diff($time)->format('%y %m %d %h %i %s');
        $diff   = explode(' ', $diff);
        $diff   = array_combine($periods, $diff);
        $diff   = array_filter($diff);
        $period = key($diff);
        $value  = current($diff);

        if(!$value) {
            $period = $tense === 'ago' ? 'seconds' : 's';
            $value  = 0;
        }
        else {
            if($period=='day' && $value>=7) {
                $period = $tense === 'ago' ? 'week' : 'w';
                $value  = floor($value/7);
            }

            if($value>1) {
                $period .= 's';
            }
        }
        return "$value $period $tense";
    }

}
