<?php

namespace App\Data;

use App\Entity\Category;
use App\Entity\Quarter;


class SearchData
{ 
    /**
     * @var int
     */
    public $page = 1;

    // /**
    //  * @var string
    //  */
    // public $q = '';

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var Quarter
     */
    public $quarter;

    // /**
    //  * @var null|integer
    //  */
    // public $min;

    // /**
    //  * @var null|integer
    //  */
    // public $max;

}