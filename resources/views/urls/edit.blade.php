<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit URL') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('urls.update', $url) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Short Code (Read-only) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Short Code</label>
                            <div class="mt-1 flex items-center gap-2">
                                <code class="bg-gray-100 px-4 py-2 rounded text-lg font-mono">{{ url('/' . $url->short_code) }}</code>
                                <span class="text-gray-500 text-sm">(Cannot be changed)</span>
                            </div>
                        </div>

                        <!-- Original URL (Read-only) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Original URL</label>
                            <div class="mt-1 text-gray-700 break-all">
                                {{ $url->original_url }}
                                <span class="text-gray-500 text-sm ml-2">(Cannot be changed)</span>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $url->title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="My Campaign Link">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="is_active" id="is_active"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="1" {{ old('is_active', $url->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $url->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Inactive links will not redirect</p>
                        </div>

                        <!-- Expiration Date -->
                        <div class="mb-4">
                            <label for="expires_at" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                            <input type="datetime-local" name="expires_at" id="expires_at"
                                value="{{ old('expires_at', $url->expires_at?->format('Y-m-d\TH:i')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('expires_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank for no expiration. The link will stop working after this date.</p>
                        </div>

                        <!-- Stats (Read-only Info) -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Statistics</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Total Clicks:</span>
                                    <span class="font-semibold ml-2">{{ number_format($url->clicks) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Created:</span>
                                    <span class="ml-2">{{ $url->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 mt-6">
                            <button type="submit" class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update URL
                            </button>
                            <a href="{{ route('urls.show', $url) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <!-- Delete Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-red-600 mb-4">Danger Zone</h3>
                        <form method="POST" action="{{ route('urls.destroy', $url) }}"
                            onsubmit="return confirm('Are you sure you want to delete this URL? This action cannot be undone and all analytics will be lost.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete URL
                            </button>
                            <p class="mt-2 text-sm text-gray-600">This will permanently delete the URL and all associated click data.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
