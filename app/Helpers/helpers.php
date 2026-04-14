<?php

function filesPath($slug)
{
    $data = [
        'admins' =>'images/admins',
        'users' =>'images/users',
    ];
    return $data[$slug];
}
