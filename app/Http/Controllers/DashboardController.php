<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\Click;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get user's URLs
        $urls = Url::where('user_id', $userId)
            ->withCount('clicks as total_clicks')
            ->latest()
            ->take(10)
            ->get();

        // Total stats
        $totalUrls = Url::where('user_id', $userId)->count();
        $totalClicks = Url::where('user_id', $userId)->sum('clicks');
        $activeUrls = Url::where('user_id', $userId)
            ->where('is_active', true)
            ->count();

        // Clicks over time (last 7 days)
        $clicksOverTime = Click::whereHas('url', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('clicked_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(clicked_at) as date'), DB::raw('COUNT(*) as clicks'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top browsers
        $topBrowsers = Click::whereHas('url', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select('browser', DB::raw('COUNT(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Top platforms
        $topPlatforms = Click::whereHas('url', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select('platform', DB::raw('COUNT(*) as count'))
            ->groupBy('platform')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'urls',
            'totalUrls',
            'totalClicks',
            'activeUrls',
            'clicksOverTime',
            'topBrowsers',
            'topPlatforms'
        ));
    }
}
