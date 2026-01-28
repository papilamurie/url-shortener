<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Short URLs') }}
            </h2>
            <a href="{{ route('urls.create') }}" class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New
            </a>
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

            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('urls.index') }}" class="flex gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- URLs List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($urls->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Short Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title/URL</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clicks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($urls as $url)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">{{ $url->short_code }}</code>
                                                    <button onclick="copyToClipboard('{{ url('/' . $url->short_code) }}')"
                                                        class="text-blue-600 hover:text-blue-900" title="Copy link">
                                                        📋
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($url->title)
                                                    <div class="font-semibold text-gray-900">{{ $url->title }}</div>
                                                @endif
                                                <div class="text-sm text-gray-500 truncate max-w-md" title="{{ $url->original_url }}">
                                                    {{ Str::limit($url->original_url, 60) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-lg font-bold text-gray-900">{{ number_format($url->total_clicks) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($url->is_active && !$url->isExpired())
                                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">✓ Active</span>
                                                @elseif($url->isExpired())
                                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">⏰ Expired</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">✗ Inactive</span>
                                                @endif
                                                @if($url->expires_at && !$url->isExpired())
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Expires: {{ $url->expires_at->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $url->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('urls.show', $url) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                    <a href="{{ route('urls.edit', $url) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form method="POST" action="{{ route('urls.destroy', $url) }}" class="inline" onsubmit="return confirm('Delete this URL?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $urls->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No URLs found.</p>
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copied: ' + text);
            }, function() {
                alert('Failed to copy');
            });
        }
    </script>
    @endpush
</x-app-layout>
