<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('urls.create') }}" class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Short URL
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total URLs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total URLs</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $totalUrls }}</p>
                            </div>
                            <div class="text-4xl">🔗</div>
                        </div>
                    </div>
                </div>

                <!-- Total Clicks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Clicks</p>
                                <p class="text-3xl font-bold text-green-600">{{ number_format($totalClicks) }}</p>
                            </div>
                            <div class="text-4xl">📊</div>
                        </div>
                    </div>
                </div>

                <!-- Active URLs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Active URLs</p>
                                <p class="text-3xl font-bold text-indigo-600">{{ $activeUrls }}</p>
                            </div>
                            <div class="text-4xl">✅</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Clicks Over Time -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Clicks Last 7 Days</h3>
                        @if($clicksOverTime->count() > 0)
                            <div style="position: relative; height: 300px;">
                                <canvas id="clicksChart"></canvas>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No click data yet</p>
                        @endif
                    </div>
                </div>

                <!-- Top Browsers -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Top Browsers</h3>
                        @if($topBrowsers->count() > 0)
                            <div style="position: relative; height: 300px;">
                                <canvas id="browsersChart"></canvas>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No browser data yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent URLs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent URLs</h3>
                        <a href="{{ route('urls.index') }}" class="text-gray-600 hover:text-blue-900">View All</a>
                    </div>

                    @if($urls->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Short URL</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original URL</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clicks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($urls as $url)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('urls.show', $url) }}" class="text-blue-600 hover:text-blue-900 font-mono">
                                                    {{ $url->short_code }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $url->original_url }}">
                                                    {{ Str::limit($url->original_url, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-semibold text-gray-900">{{ number_format($url->clicks) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($url->is_active && !$url->isExpired())
                                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Active</span>
                                                @elseif($url->isExpired())
                                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Expired</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $url->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No URLs yet.</p>
                            <a href="{{ route('urls.create') }}" class="text-blue-600 hover:text-blue-900 mt-2 inline-block">
                                Create your first short URL
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Clicks Over Time Chart
        @if($clicksOverTime->count() > 0)
        const clicksCtx = document.getElementById('clicksChart').getContext('2d');
        new Chart(clicksCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($clicksOverTime->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
                datasets: [{
                    label: 'Clicks',
                    data: {!! json_encode($clicksOverTime->pluck('clicks')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        @endif

        // Top Browsers Chart
        @if($topBrowsers->count() > 0)
        const browsersCtx = document.getElementById('browsersChart').getContext('2d');
        new Chart(browsersCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topBrowsers->pluck('browser')) !!},
                datasets: [{
                    data: {!! json_encode($topBrowsers->pluck('count')) !!},
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ]
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
