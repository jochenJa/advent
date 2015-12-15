<?php

include_once('adventData.php');
// DAY 1
$mapToFloor = function($floorArray) {
    $floor = 0;
    return array_map(
        function($updown) use (&$floor) {
            return $floor = $floor + ($updown == '(' ? 1 : -1);
        }, $floorArray);
};

$elevatorAt = $mapToFloor(data_day1());
echo "\nday 1 - test 1 : ";
echo end($elevatorAt);
echo "\nday 1 - test 2 : ";
echo array_search('-1', $elevatorAt)+1;

echo "\n---------------------------------------------\n";

// DAY 2
$toDims = function($dimstring) { $dims = explode('x', $dimstring); sort($dims); return $dims; };
$squareFeet = function($dm) { return ($dm[0] * $dm[1] * 3) + (2 * $dm[0] * $dm[2]) + (2 * $dm[1] * $dm[2]); };

$r = array_sum(array_map(function($dimstring) use ($toDims, $squareFeet) { return $squareFeet($toDims($dimstring));}, data_day2()));
echo "\nday 2 - test 1 : ".$r;

$ribbonLength = function($dm) { return (($dm[0] + $dm[1]) * 2) + array_product($dm);};
$r = array_sum(array_map(function($dimstring) use ($toDims, $ribbonLength) { return $ribbonLength($toDims($dimstring));}, data_day2()));
echo "\nday 2 - test 2 : ".$r;

echo "\n---------------------------------------------\n";


// DAY 3

//MapToDirection | MapToPosition | unique
$coMap = ['>' => [1,0],'<' => [-1,0], '^' => [0,1], 'v' => [0, -1]];
$map_direction = function($directions) use ($coMap) {
    return array_map(
        function($direction) use ($coMap) { return $coMap[$direction]; },
        $directions
    );
};

$map_position = function($cos) {
    return array_reduce(
        $cos,
        function($positions, $co) { $last = end($positions); array_push($positions, [$co[0] + $last[0], $co[1] + $last[1]]); return $positions; },
        [[0,0]]
    );
};

$map_asText = function($positions) {
    return array_map(
        function($position) { return implode(':', $position); },
        $positions
    );
};

echo "\nday 3 - test 1 : ". count(array_unique($map_asText($map_position($map_direction(data_day3())))));

$splitAlternate = function($cos) {
    $alternate = array_chunk($cos, 2);

    return [
      array_column($alternate, 0),
      array_column($alternate, 1),
    ];
};

list($santa, $robo) = $splitAlternate(data_day3());
echo "\nday 3 - test 2 : ". count(array_unique(array_merge(
            $map_asText($map_position($map_direction($santa))),
            $map_asText($map_position($map_direction($robo)))
        )));