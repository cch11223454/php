<?php
$ad_path=array('dfgd','fdg');
        array_walk(
            $ad_path,
            function (&$s, $k, $prefix = '.') {
                $s = str_pad($s, strlen($prefix) + strlen($s), $prefix, STR_PAD_LEFT);
            }
        );
?>