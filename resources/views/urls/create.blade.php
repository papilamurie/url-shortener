<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Short URL') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('urls.store') }}">
                        @csrf

                        <!-- Original URL -->
                        <div class="mb-4">
                            <label for="original_url" class="block text-sm font-medium text-gray-700">Long URL *</label>
                            <input type="url" name="original_url" id="original_url" value="{{ old('original_url') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required placeholder="https://example.com/very-long-url-here">
                            @error('original_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Enter the long URL you want to shorten</p>
                        </div>

                        <!-- Custom Short Code (Optional) -->
                        <div class="mb-4">
                            <label for="custom_code" class="block text-sm font-medium text-gray-700">Custom Short Code (Optional)</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    {{ url('/') }}/
                                </span>
                                <input type="text" name="custom_code" id="custom_code" value="{{ old('custom_code') }}"
                                    class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="my-link" maxlength="10" pattern="[a-zA-Z0-9_-]+">
                            </div>
                            @error('custom_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank for auto-generated code. Only letters, numbers, dashes, and underscores.</p>
                        </div>

                        <!-- Title (Optional) -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title (Optional)</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="My Campaign Link">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Give this link a memorable name</p>
                        </div>

                        <!-- Expiration Date (Optional) -->
                        <div class="mb-4">
                            <label for="expires_at" class="block text-sm font-medium text-gray-700">Expiration Date (Optional)</label>
                            <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('expires_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">The link will stop working after this date</p>
                        </div>

                        <!-- Preview -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600">{{ url('/') }}/</span>
                                <span id="preview-code" class="font-mono font-bold text-blue-600">abc123</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 mt-6">
                            <button type="submit" class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Short URL
                            </button>
                            <a href="{{ route('urls.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live preview of custom code
        document.getElementById('custom_code').addEventListener('input', function() {
            const code = this.value || 'abc123';
            document.getElementById('preview-code').textContent = code;
        });
    </script>
    @endpush
</x-app-layout>
