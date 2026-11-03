<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <h2 class="text-xl font-semibold text-gray-900">Payment received</h2>
                <p class="mt-2 text-gray-600">
                    Thanks for supporting {{ $productName }}.
                    @if ($subscription)
                        Your subscription is <strong>{{ $subscription->status->value }}</strong>.
                    @else
                        If checkout just finished, webhook sync may take a moment — refresh Billing shortly.
                    @endif
                </p>
                <a href="{{ route('billing.show') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back to Billing</a>
            </x-card>
        </div>
    </div>
</x-app-layout>
