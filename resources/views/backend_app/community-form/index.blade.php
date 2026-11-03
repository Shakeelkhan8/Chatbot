<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contact messages</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="overflow-x-auto bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">Name</th>
                            <th scope="col" class="px-4 py-3 font-medium">Email</th>
                            <th scope="col" class="px-4 py-3 font-medium">Phone</th>
                            <th scope="col" class="px-4 py-3 font-medium">Message</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-800">
                        @forelse ($forms as $item)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $item->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $item->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $item->phone }}</td>
                                <td class="px-4 py-3">{{ $item->message }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">No messages yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
