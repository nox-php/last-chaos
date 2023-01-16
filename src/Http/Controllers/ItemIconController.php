<?php

namespace Nox\LastChaos\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Nox\LastChaos\Models\Item;

class ItemIconController extends Controller
{
    public function __invoke($id)
    {
        $item = Item::query()
            ->findOrFail($id);

        return $this->getIcon($item);
    }

    private function getIcon(Item $item)
    {
        $response = Cache::rememberForever(
            'last-chaos.item.icons.' . $item->a_index,
            fn() => $this->generateIcon($item)
        );

        return response($response)->withHeaders([
            'Content-Type' => 'image/png'
        ]);
    }

    private function generateIcon(Item $item): string
    {
        $src = imagecreatefrompng(__DIR__ . '/../../../resources/images/icons/ItemBtn' . $item->a_texture_id . '.png');
        $dest = imagecreatetruecolor(32, 32);
        $row_start = $item->a_texture_row * 32;
        $row_end = $row_start + 32;
        $col_start = $item->a_texture_col * 32;
        $col_end = $col_start + 32;
        imagecopy($dest, $src, 0, 0, $col_start, $row_start, $col_end, $row_end);

        ob_start();

        imagepng($dest);
        imagedestroy($dest);
        imagedestroy($src);

        $image = ob_get_contents();
        ob_end_clean();

        return $image;
    }
}
