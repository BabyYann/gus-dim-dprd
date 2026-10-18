<?php

$diff = shell_exec('git diff');
preg_match_all('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', $diff, $m);
echo 'Total emojis in git diff: ' . count($m[0]) . PHP_EOL;
if (count($m[0]) > 0) {
    print_r($m[0]);
}
