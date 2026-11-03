<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $productName }} Subscription</h2>
        <p class="mt-1 text-sm text-gray-600">Unlock ongoing personalized coaching with a Pro subscription (Stripe test mode).</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <x-alert type="error">{{ $errors->first() }}</x-alert>
            @endif

            <x-card>
                @if ($subscription)
                    <h3 class="text-lg font-medium text-gray-900">Status: {{ $subscription->status->value }}</h3>
                    <p class="mt-1 text-gray-600">Plan is active for your account.</p>
                    @if ($subscription->current_period_ends_at)
                        <p class="mt-1 text-sm text-gray-500">Current period ends {{ $subscription->current_period_ends_at->toDayDateTimeString() }}</p>
                    @endif
                @else
                    <h3 class="text-lg font-medium text-gray-900">Free experience</h3>
                    <p class="mt-1 text-gray-600">
                        Habit check-ins and weekly plans are included, plus {{ config('mentor.limits.free_coach_messages_per_day') }} AI coach messages a day.
                    </p>

                    <h4 class="mt-6 font-medium text-gray-900">Pro subscription</h4>
                    <p class="mt-1 text-gray-600">Unlimited AI coach messages. Subscribe via Stripe Checkout (test mode).</p>

                    <div class="mt-4">
                        @if ($priceConfigured)
                            <form method="POST" action="{{ route('billing.checkout') }}">
                                @csrf
                                <x-primary-button>Subscribe with Stripe (test mode)</x-primary-button>
                            </form>
                        @else
                            <x-alert type="warning">
                                Stripe price is not configured. Set <code>STRIPE_PRICE_ID</code> (and test keys) in your environment.
                            </x-alert>
                        @endif
                    </div>
                @endif
            </x-card>

            @if ($latestSubscription && ! $subscription)
                <x-card>
                    <div class="text-sm text-gray-500">Latest subscription status</div>
                    <div class="font-medium text-gray-900">{{ $latestSubscription->status->value }}</div>
                </x-card>
            @endif

            <p class="text-sm text-gray-500">{{ $disclaimer }}</p>
        </div>
    </div>
</x-app-layout>
