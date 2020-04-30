<?php

namespace App\Services;

class SondagePositionCounter
{

    public static function doPositionCounter(int $position, int $doPosition):array
    {
        $allPositions=[
            "tmpBefor"=>$position,
            "after"=>$position,
            "befor"=>$position
        ];
        // TODO: change counter with foreach key
        $counter = 0;
        foreach ($doPosition as $do) {
            if ($counter==1) {
                $counter++;
            }
            if ($do == $position) {
                $allPositions["befor"] = $allPositions["tmpBefor"];
                $counter++;
            } else {
                $allPositions["tmpBefor"] = $do;
            }
            if ($counter==2) {
                $allPositions["after"] = $do;
                $counter++;
            }
        }
        return $allPositions;
    }
}
