<?php

namespace Nox\LastChaos\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class InventoryBackgroundController extends Controller
{
    public function __invoke($class)
    {
        $path = __DIR__ . '/../../../resources/images/inventories/' . $class . '.png';

        abort_if(!File::exists($path), 404);

        return response(File::get($path))->withHeaders([
            'Content-Type' => 'image/png'
        ]);
    }
}