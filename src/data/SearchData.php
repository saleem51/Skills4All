<?php

namespace App\data;

class SearchData
{

    public int $page = 1;

    public string  $search = '';

    public array $categories = [];

    public int|null $min;

    public int|null $max;

}