<?php


namespace App\Http\Middleware;


class Slugify
{
    public function Slug($title) {
        $random = rand();
        $slug = str_slug($title, '-');
        $newSlug = $slug . "-" . $random;
        return $newSlug;
    }
}
