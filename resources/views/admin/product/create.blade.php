<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="Name" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="Description" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Picture URL (path)</label>
                        <input type="text" name="PictureUrl" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">GLB URL (path)</label>
                        <input type="text" name="UrlGlb" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Price</label>
                        <input type="number" name="Price" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Product Brand ID</label>
                        <input type="number" name="ProductBrandId" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Product Type ID</label>
                        <input type="number" name="ProductTypeId" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Quantity</label>
                        <input type="number" name="Quantity" class="w-full rounded-lg border-gray-300" required>
                    </div>

                    <div class="mb-4 flex items-center">
                        <label class="mr-2 text-gray-700 dark:text-gray-300">Is Favorite?</label>
                        <input type="checkbox" name="isFav" value="1">
                    </div>

                    <div class="mb-4 flex items-center">
                        <label class="mr-2 text-gray-700 dark:text-gray-300">Is Liked?</label>
                        <input type="checkbox" name="isLike" value="1">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ __('Create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
