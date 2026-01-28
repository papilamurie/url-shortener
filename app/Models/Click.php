<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'url_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'browser',
        'platform',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }

    public static function createFromRequest($urlId, $request): self
    {
        return self::create([
            'url_id' => $urlId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'browser' => self::getBrowser($request->userAgent()),
            'platform' => self::getPlatform($request->userAgent()),
            'clicked_at' => now(),
        ]);
    }

    protected static function getBrowser(?string $userAgent): ?string
    {
        if (!$userAgent) return null;

        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';

        return 'Other';
    }

    protected static function getPlatform(?string $userAgent): ?string
    {
        if (!$userAgent) return null;

        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac')) return 'Mac';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iOS') || str_contains($userAgent, 'iPhone')) return 'iOS';

        return 'Other';
    }
}
