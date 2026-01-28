<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\Click;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect(Request $request, string $shortCode)
    {
        $url = Url::where('short_code', $shortCode)->first();

        if (!$url) {
            abort(404, 'Short URL not found');
        }

        if (!$url->isAccessible()) {
            if ($url->isExpired()) {
                abort(410, 'This link has expired');
            }
            abort(403, 'This link is no longer active');
        }

        // Log the click
        Click::createFromRequest($url->id, $request);

        // Increment counter
        $url->incrementClicks();

        // Redirect to original URL
        return redirect($url->original_url, 301);
    }
}
