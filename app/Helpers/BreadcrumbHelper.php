<?php
// app/Helpers/BreadcrumbHelper.php

namespace App\Helpers;

class BreadcrumbHelper
{
    public static $breadcrumbs = [];

    public static function add($label, $url = null)
    {
        self::$breadcrumbs[] = (object) [
            'label' => $label,
            'url' => $url,
            'active' => false
        ];

        return new static;
    }

    public static function set($breadcrumbs)
    {
        // Ensure all breadcrumb objects have the active property
        self::$breadcrumbs = array_map(function($crumb) {
            if (is_array($crumb)) {
                return (object) [
                    'label' => $crumb['label'],
                    'url' => $crumb['url'] ?? null,
                    'active' => $crumb['active'] ?? false
                ];
            }

            // If it's already an object but missing active property
            if (!isset($crumb->active)) {
                $crumb->active = false;
            }

            return $crumb;
        }, $breadcrumbs);

        return new static;
    }

    public static function get()
    {
        if (!empty(self::$breadcrumbs)) {
            $lastKey = count(self::$breadcrumbs) - 1;
            self::$breadcrumbs[$lastKey]->active = true;
        }

        return self::$breadcrumbs;
    }

    public static function clear()
    {
        self::$breadcrumbs = [];
    }
}
