<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if($users->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">No users found.</p>
                @else
                    <table class="min-w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Phone</th>
                                <th class="px-4 py-2">Gender</th>
                                <th class="px-4 py-2">Verified at</th>
                                <th class="px-4 py-2">Role</th> 
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @foreach ($users as $user)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">
                                        @if($user->image)
                                            <img src="{{ asset($user->image) }}" alt="User Image" class="w-16 h-16 rounded-full">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                                                No Image
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->phone ?? 'No phone provided' }}</td>
                                    <td class="px-4 py-2">{{ $user->gender ?? 'No gender specified' }}</td>
                                    <td class="px-4 py-2">{{ $user->email_verified_at ?? 'No verified yet' }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($user->role) }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('user.edit', $user->id) }}" class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                                                Edit
                                            </a>
                                    
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
