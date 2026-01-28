<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;


class UrlController extends Controller
{
    public function index(Request $request)
    {
        $query = Url::where('user_id', Auth::id())
            ->withCount('clicks as total_clicks')
            ->latest();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $urls = $query->paginate(15);

        return view('urls.index', compact('urls'));
    }

    public function create()
    {
        return view('urls.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
            'custom_code' => 'nullable|string|max:10|alpha_dash|unique:urls,short_code',
            'title' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $shortCode = $validated['custom_code'] ?? Url::generateUniqueShortCode();

        $url = Url::create([
            'user_id' => Auth::id(),
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
            'title' => $validated['title'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return redirect()->route('urls.show', $url)
            ->with('success', 'Short URL created successfully!');
    }

    public function show(Url $url)
{
    if ($url->user_id !== Auth::id()) {
        abort(403);
    }

    $url->load(['clicks' => function ($query) {
        $query->latest('clicked_at')->take(50);
    }]);

    // Clicks by day
    $clicksByDay = DB::table('clicks')
        ->where('url_id', $url->id)
        ->select(DB::raw('DATE(clicked_at) as date'), DB::raw('COUNT(*) as count'))
        ->groupBy(DB::raw('DATE(clicked_at)'))
        ->orderBy('date', 'desc')
        ->limit(30)
        ->get();

    // Clicks by browser
    $clicksByBrowser = DB::table('clicks')
        ->where('url_id', $url->id)
        ->whereNotNull('browser')
        ->select('browser', DB::raw('COUNT(*) as count'))
        ->groupBy('browser')
        ->orderByDesc('count')
        ->get();

    // Clicks by platform
    $clicksByPlatform = DB::table('clicks')
        ->where('url_id', $url->id)
        ->whereNotNull('platform')
        ->select('platform', DB::raw('COUNT(*) as count'))
        ->groupBy('platform')
        ->orderByDesc('count')
        ->get();

    $shortUrl = url('/' . $url->short_code);

    return view('urls.show', compact(
        'url',
        'shortUrl',
        'clicksByDay',
        'clicksByBrowser',
        'clicksByPlatform'
    ));
}

    public function edit(Url $url)
    {
        if ($url->user_id !== Auth::id()) {
            abort(403);
        }

        return view('urls.edit', compact('url'));
    }

    public function update(Request $request, Url $url)
    {
        if ($url->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'expires_at' => 'nullable|date',
        ]);

        $url->update($validated);

        return redirect()->route('urls.show', $url)
            ->with('success', 'URL updated successfully!');
    }

    public function destroy(Url $url)
    {
        if ($url->user_id !== Auth::id()) {
            abort(403);
        }

        $url->delete();

        return redirect()->route('urls.index')
            ->with('success', 'URL deleted successfully!');
    }

    public function qrCode(Url $url)
    {
        if ($url->user_id !== Auth::id()) {
            abort(403);
        }

        $shortUrl = url('/' . $url->short_code);

        return response(QrCode::size(300)->generate($shortUrl))
            ->header('Content-Type', 'image/svg+xml');
    }
}
