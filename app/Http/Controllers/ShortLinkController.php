<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function redirect(string $code)
    {
        $shortLink = ShortLink::where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        $shortLink->increment('clicks');

        return redirect()->away($shortLink->original_url);
    }
}
