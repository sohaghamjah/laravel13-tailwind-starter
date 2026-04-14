<?php

function filesPath($slug)
{
    $data = [
        'admins' =>'images/admins',
    ];
    return $data[$slug];
}
