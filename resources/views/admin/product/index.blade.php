<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 text-right">
                <a href="{{ route('products.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ __('Create Product') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <img src="{{ $product->PictureUrl }}" alt="{{ $product->Name }}" class="w-full h-48 object-cover rounded-lg">
                        <h3 class="text-xl font-semibold mt-4">{{ $product->Name }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $product->Description }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">${{ $product->Price }}</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Quantity: {{ $product->Quantity }}</span>
                        </div>

                        <!-- A container to ensure the buttons are aligned -->
                        <div class="mt-4 space-x-2 flex justify-between">
                            <!-- Edit button -->
                            <a href="{{--{{ route('products.edit', $product->Id) }}--}}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 w-full sm:w-auto text-center">
                                {{ __('Edit') }}
                            </a>

                            <!-- Delete button -->
                            <form action="{{ route('products.destroy', $product->Id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 w-full sm:w-auto text-center">
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
