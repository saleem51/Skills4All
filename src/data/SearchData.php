<?php

namespace App\data;

class SearchData
{
    //Class SearchData pour la mise en place des filtres par nom et par catégories

    public int $page = 1;

    public string  $search = '';

    public array $categories = [];

    public int|null $min;

    public int|null $max;

}