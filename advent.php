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

//echo "\nday 3 - test 1 : ". count(array_unique($map_asText($map_position($map_direction(data_day3())))));

$splitAlternate = function($cos) {
    $alternate = array_chunk($cos, 2);

    return [
      array_column($alternate, 0),
      array_column($alternate, 1),
    ];
};

//list($santa, $robo) = $splitAlternate(data_day3());
//echo "\nday 3 - test 2 : ". count(array_unique(array_merge(
//            $map_asText($map_position($map_direction($santa))),
//            $map_asText($map_position($map_direction($robo)))
//        )));

echo "\n---------------------------------------------\n";


// DAY 4

$part = data_day4();
function findPositiveNumber ($x, $part) {
    $hash = md5($part . $x);
    return (preg_match('/^00000/', $hash))
        ? $x
        : findPositiveNumber(++$x, $part)
    ;
};

//$positive = findPositiveNumber(1, data_day4());
//echo "\nday 4 - test 1 : ". $positive;

// recursive approach aint working for such a high iteration.
//$x = 1;
//for(; $x <= 10000000; $x++) {
//    $hash = md5($part . $x);
//    if(preg_match('/^000000/', $hash)) break;
//}

//echo "\nday 4 - test 2 : ". $x;

echo "\n---------------------------------------------\n";

// filter naughty ones /(ab|cd|pq|xy)/
$withoutNaughtyOnes = array_filter(data_day5(), function($txt) { return ! preg_match('/(ab|cd|pq|xy)/', $txt); });
$atleastThreeVowels = array_filter($withoutNaughtyOnes, function($txt) { return preg_match('/(\S*[aeiou]\S*){3,}/', $txt); });
echo "\nday 5 - test 1 : ". count(array_filter($atleastThreeVowels, function($txt) { return preg_match('/(\w)\1/', $txt); }));

$repeatingLetterWithOneInBetween = array_filter(data_day5(), function($txt) { return preg_match('/(\w).\1/', $txt); });
echo "\nday 5 - test 2 : ". count(array_filter($repeatingLetterWithOneInBetween, function($txt) { return preg_match('/(\w{2,})(?=.*?\1)/', $txt); }));

echo "\n---------------------------------------------\n";


// DAY 16 - aunt sue

// detect a criteria in a string
$detect = function($criterium, $number, $operation, $text) {
    $matches = [];
    if(! preg_match('/'.$criterium.': (\d+)/', $text, $matches)) return true;

    return $operation($number, (integer)end($matches));
};

$callables = function($criteria) use ($detect) {
    return array_map(
        function($c) use ($detect) { return function($text) use ($detect, $c){ return $detect($c[0], $c[1], $c[2], $text); }; },
        $criteria
    );
};

$equals = function($a, $b) { return $a == $b; };

//which aunt sue meets all criteria
$criteria = [
    ['children', 3, $equals],
    ['cats', 7, $equals],
    ['samoyeds', 2, $equals],
    ['pomeranians', 3, $equals],
    ['akitas', 0, $equals],
    ['vizslas', 0, $equals],
    ['goldfish', 5, $equals],
    ['trees', 3, $equals],
    ['cars', 2, $equals],
    ['perfumes', 1, $equals]
];

$validSues = array_reduce(
    $callables($criteria),
    function($filteredSues, $criterium) { return array_filter($filteredSues, $criterium); },
    data_day16()
);

echo "\nday 16 - test 1 : ". reset($validSues);

$greater = function($a, $b) { return $a > $b; };
$lesser = function($a, $b) { return $a < $b; };

//which aunt sue meets all criteria
$criteria = [
    ['children', 3, $equals],
    ['cats', 7, $lesser],
    ['samoyeds', 2, $equals],
    ['pomeranians', 3, $greater],
    ['akitas', 0, $equals],
    ['vizslas', 0, $equals],
    ['goldfish', 5, $greater],
    ['trees', 3, $lesser],
    ['cars', 2, $equals],
    ['perfumes', 1, $equals]
];

$validSues = array_reduce(
    $callables($criteria),
    function($filteredSues, $criterium) { return array_filter($filteredSues, $criterium); },
    data_day16()
);

echo "\nday 16 - test 2 : ". reset($validSues);

echo "\n---------------------------------------------\n";


// DAY 17 - filling containers

// build every possible combo from elements in array , filter combo = X

function asText($arr) { return sprintf('[%s]=>%s', implode('|', $arr), array_sum($arr)); }

function removeHigher($number, $arr) { return array_filter($arr, function($x) use ($number) { return $x <= $number; }); }
$data = [20,15, 10, 5, 5];

//order possible sizes
rsort($data);

$combos = array_map(
    function($x) use ($data) {
        $combo = [];
        unset($data[array_search($x, $data)]);
        var_dump($data);

        $find = 25;
        while(array_sum($combo) < $x || count($data))
        {
            $next = array_shift($data);

            if ($find < $next) continue;

            $find -= $next;
            $combo[] = $next;
            //var_dump(asText($combo), asText($data));

            if (!$find) return $combo;
        }
    },
    array_unique($data)
);
var_dump('found: ', implode(' ',array_map(function($combo) { return asText($combo);}, $combos)));


$combo = 0;
echo "\nday 17 - test 1 : " . $combo;




