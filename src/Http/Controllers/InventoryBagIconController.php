<?php

namespace Nox\LastChaos\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class InventoryBagIconController extends Controller
{
    public function __invoke(string $type)
    {
        $image = File::get(__DIR__ . '/../../../resources/images/buttons/bag-' . $type . '.png');

        return response($image)->withHeaders([
            'Content-Type' => 'image/png'
        ]);
    }
}