<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $url->title ?? 'URL Details' }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('urls.edit', $url) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('urls.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- URL Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Short URL</h3>
                            <div class="flex items-center gap-2 mb-4">
                                <code class="bg-gray-100 px-4 py-2 rounded flex-1 text-lg font-mono">{{ $shortUrl }}</code>
                                <button onclick="copyToClipboard('{{ $shortUrl }}')"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Copy
                                </button>
                            </div>

                            <h3 class="text-lg font-semibold mb-2 mt-6">Original URL</h3>
                            <a href="{{ $url->original_url }}" target="_blank" class="text-blue-600 hover:text-blue-900 break-all">
                                {{ $url->original_url }}
                            </a>

                            @if($url->title)
                                <h3 class="text-lg font-semibold mb-2 mt-6">Title</h3>
                                <p class="text-gray-700">{{ $url->title }}</p>
                            @endif

                            <h3 class="text-lg font-semibold mb-2 mt-6">Status</h3>
                            @if($url->is_active && !$url->isExpired())
                                <span class="px-3 py-1 text-sm rounded bg-green-100 text-green-800">✓ Active</span>
                            @elseif($url->isExpired())
                                <span class="px-3 py-1 text-sm rounded bg-red-100 text-red-800">⏰ Expired</span>
                            @else
                                <span class="px-3 py-1 text-sm rounded bg-gray-100 text-gray-800">✗ Inactive</span>
                            @endif

                            @if($url->expires_at)
                                <div class="mt-2 text-sm text-gray-600">
                                    Expires: {{ $url->expires_at->format('F d, Y h:i A') }}
                                    @if(!$url->isExpired())
                                        ({{ $url->expires_at->diffForHumans() }})
                                    @endif
                                </div>
                            @endif

                            <div class="mt-4 text-sm text-gray-600">
                                <p>Created: {{ $url->created_at->format('M d, Y h:i A') }}</p>
                                <p>Last Updated: {{ $url->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Right Column - QR Code -->
                        <div class="text-center">
                            <h3 class="text-lg font-semibold mb-4">QR Code</h3>
                            <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded">
                                <img src="{{ route('urls.qrcode', $url) }}" alt="QR Code" class="w-64 h-64">
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Scan to visit this link</p>
                            <a href="{{ route('urls.qrcode', $url) }}" download="qrcode-{{ $url->short_code }}.svg"
                                class="mt-2 inline-block text-blue-600 hover:text-blue-900">
                                Download QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-600">Total Clicks</p>
                        <p class="text-4xl font-bold text-blue-600">{{ number_format($url->clicks) }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-600">Clicks Today</p>
                        <p class="text-4xl font-bold text-green-600">
                            {{ DB::table('clicks')->where('url_id', $url->id)->whereDate('clicked_at', today())->count() }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-600">Clicks This Week</p>
                        <p class="text-4xl font-bold text-indigo-600">
                            {{ DB::table('clicks')->where('url_id', $url->id)->where('clicked_at', '>=', now()->startOfWeek())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Clicks by Day -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Clicks Over Time (Last 30 Days)</h3>
                        @if($clicksByDay->isNotEmpty())
                            <div style="position: relative; height: 300px;">
                                <canvas id="clicksByDayChart"></canvas>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No click data yet</p>
                        @endif
                    </div>
                </div>

                <!-- Clicks by Browser -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Browsers</h3>
                        @if($clicksByBrowser->isNotEmpty())
                            <div style="position: relative; height: 300px;">
                                <canvas id="browserChart"></canvas>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No browser data yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Clicks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Clicks (Last 50)</h3>
                    @php
                        $recentClicks = DB::table('clicks')->where('url_id', $url->id)->latest('clicked_at')->limit(50)->get();
                    @endphp

                    @if($recentClicks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Browser</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Platform</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referer</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentClicks as $click)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($click->clicked_at)->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $click->browser ?? 'Unknown' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $click->platform ?? 'Unknown' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $click->referer ? Str::limit($click->referer, 50) : 'Direct' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No clicks yet. Share your link!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copied: ' + text);
            }, function() {
                alert('Failed to copy');
            });
        }

        // Clicks by Day Chart
        @if($clicksByDay->isNotEmpty())
        const dayCtx = document.getElementById('clicksByDayChart').getContext('2d');
        new Chart(dayCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($clicksByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
                datasets: [{
                    label: 'Clicks',
                    data: {!! json_encode($clicksByDay->pluck('count')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        @endif

        // Browser Chart
        @if($clicksByBrowser->isNotEmpty())
        const browserCtx = document.getElementById('browserChart').getContext('2d');
        new Chart(browserCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($clicksByBrowser->pluck('browser')) !!},
                datasets: [{
                    data: {!! json_encode($clicksByBrowser->pluck('count')) !!},
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>
