# AI Mentor Health

[![tests](https://github.com/Shakeelkhan8/ai-mentor-health/actions/workflows/tests.yml/badge.svg)](https://github.com/Shakeelkhan8/ai-mentor-health/actions/workflows/tests.yml)

**AI Mentor Health is an AI-powered wellness companion that helps users build healthier habits through personalized coaching, progress tracking, and weekly wellness plans.**

It is **not** a generic chatbot. It remembers user goals and focus areas, tracks daily habit check-ins, generates personalized weekly plans, and supports a Pro subscription via Stripe (test mode).

> AI Mentor Health provides general wellness guidance and does not diagnose conditions or replace medical professionals.

## What's worth reading

The backend parts that are easy to get wrong, each with the tests that pin it down:

| What | Code | Tests |
|------|------|-------|
| **Stripe webhooks safe against redelivery and reordering.** The event id is recorded with `insertOrIgnore` in the same transaction as the change, and an older event can't overwrite newer subscription state. | [`HandleStripeWebhook`](app/Domains/Billing/Actions/HandleStripeWebhook.php) | [`StripeWebhookDeliveryTest`](tests/Feature/Billing/StripeWebhookDeliveryTest.php) |
| **Plan limits decided in one place.** Free users get a daily number of AI messages that resets at midnight in their own timezone; Pro is unlimited. | [`Entitlements`](app/Domains/Billing/Services/Entitlements.php) | [`CoachDailyLimitTest`](tests/Feature/Coaching/CoachDailyLimitTest.php) |
| **AI calls kept out of database transactions.** The provider call holds no locks; if it fails after retries, nothing is saved and no quota is used, and the failure is logged with the user and conversation. | [`SendCoachMessage`](app/Domains/Coaching/Actions/SendCoachMessage.php) | [`SendCoachMessageReliabilityTest`](tests/Feature/Coaching/SendCoachMessageReliabilityTest.php) |
| **Concurrent writes that stay correct on PostgreSQL.** When another request inserts the same day's check-in or the same week's plan first, the losing request updates that row instead of failing, and only one row remains. | Habits and Plans actions | [`ConcurrentWritesTest`](tests/Feature/ConcurrentWritesTest.php) |

CI runs the suite on SQLite and PostgreSQL, plus Larastan (level 5) and Pint, on every pull request.

## Why this project

Portfolio + MVP foundation for a B2C wellness product:

- Domain-oriented Laravel architecture (Identity, Coaching, Habits, Plans, Billing)
- Swappable AI provider via `AiCoachClient`
- Stripe Checkout + webhooks for subscriptions
- Feature flags that switch whole modules on and off

## Core product loop

1. **Onboard** — goals, focus areas, timezone  
2. **Coach** — personalized AI wellness chat with safety framing  
3. **Check in** — daily habit progress  
4. **Plan** — AI weekly plan (strict JSON + safe fallback)  
5. **Subscribe** — Pro checkout for unlimited coaching (Stripe test mode)

## Architecture

```text
HTTP (Controllers, FormRequests, Middleware)
        ↓
Application (Actions, Domain Services)
        ↓
Domain (Enums, Contracts, Models)
        ↓
Infrastructure (RapidAPI coach, Stripe billing)
```

See [docs/architecture.md](docs/architecture.md) and [docs/domains.md](docs/domains.md).

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
# configure DB (MySQL recommended locally)
php artisan migrate
npm install && npm run build
php artisan serve
```

Requires PHP **8.2 or newer** (CI runs on 8.4) and Node.js for the Vite build.

### Important env vars

| Variable | Purpose |
|----------|---------|
| `MENTOR_PRODUCT_NAME` | Product display name |
| `FEATURE_AI_COACH` | AI coach routes |
| `FEATURE_HABIT_TRACKING` | Habit check-ins |
| `FEATURE_WEEKLY_PLANS` | Weekly plans |
| `FEATURE_SUBSCRIPTIONS` | Billing UI |
| `RAPIDAPI_KEY` | AI coach provider |
| `STRIPE_KEY` / `STRIPE_SECRET` | Stripe test keys |
| `STRIPE_WEBHOOK_SECRET` | Webhook verification (required outside local/testing) |
| `STRIPE_PRICE_ID` | Recurring Price id for Pro |
| `FREE_COACH_MESSAGES_PER_DAY` | Free plan's daily AI coach messages (default 10) |

## Demo

Follow the 5–10 minute walkthrough: **[docs/DEMO.md](docs/DEMO.md)**

The hosted demo runs on Render's free plan in demo mode: pre-written coach replies (no AI costs) and a one-click **Try the demo** account. Setup and trade-offs: **[docs/DEPLOY.md](docs/DEPLOY.md)**.

## Tests and code quality

```bash
npm run build      # auth pages render through @vite, so build assets first
php artisan test
composer lint      # Laravel Pint, style check (run `vendor/bin/pint` to fix)
composer analyse   # Larastan (PHPStan for Laravel), level 5
```

Tests run against in-memory SQLite (configured in `phpunit.xml`), so no database setup is needed. GitHub Actions runs all three checks on every pull request and on pushes to `main`. Larastan runs with no baseline: findings are fixed, not recorded.

## Pricing model (MVP)

| Tier | What you get |
|------|----------------|
| **Free** | Onboarding, habit check-ins, weekly plans, and 10 AI coach messages a day |
| **Pro** | Everything in Free, with unlimited AI coach messages |

The daily allowance resets at midnight in the user's timezone and is set by `FREE_COACH_MESSAGES_PER_DAY`. Limits are decided in one place, `App\Domains\Billing\Services\Entitlements`, and are off while `FEATURE_SUBSCRIPTIONS=false`.

## Explicitly deferred / out of scope

- Doctor marketplace  
- Hospital finder / appointments  
- Clinical diagnosis  
- Medical decision support  

The earlier doctor, hospital and appointment code was removed rather than kept behind a flag.

## License

The application code in this repository is released under the [MIT License](LICENSE).

Third-party code keeps its own license and is not covered by this one: the front-end libraries vendored under `public/assets/` for the marketing pages (Bootstrap, jQuery, Popper, Owl Carousel, AOS, Swiper), and the Composer and npm dependencies (including the Laravel framework), which are installed separately.
