<?php

function resolveA(array $a, $path, $default = null)
{
    $current = $a;
    $p = strtok($path, '.');

    while ($p !== false) {
        if (!isset($current[$p])) {
            return $default;
        }
        $current = $current[$p];
        $p = strtok('.');
    }

    return $current;
}

function resolveMany(array $a, $path, $default = [])
{
    $current = $a;
    $p = strtok($path, '.');

    while ($p !== false) {
        if ($p == '!all') {
            $ret = [];
            $p = strtok('.');
            if ($p === false) {
                return $current;
            }
            foreach ($current as $value) {
                $ret[] = resolveMany($value, $p);
            }

            return $ret;
        } else {
            if (!isset($current[$p])) {
                return $default;
            }
            $current = $current[$p];
            $p = strtok('.');
        }
    }

    return $current;
}
