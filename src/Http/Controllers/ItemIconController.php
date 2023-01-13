<?php

namespace Nox\LastChaos\Http\Controllers;

use Illuminate\Routing\Controller;
use Nox\LastChaos\Models\Item;

class ItemIconController extends Controller
{
    public function __invoke(Item $item)
    {
        dd($item);
    }
}