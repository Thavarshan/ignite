# Ignite — Project Guidelines

> Framework-level rules (PHP style, Laravel patterns, Inertia, Wayfinder, Pest, Fortify) are in `AGENTS.md` — managed by Laravel Boost. This file covers project-specific operational knowledge only.

## Stack

Laravel 13 · Inertia v2 · Vue 3 · Tailwind v4 · Fortify v1 · Wayfinder · Pest 4 · PHP 8.5 · PostgreSQL

## Dev Commands

```bash
composer run dev          # Start full stack: PHP server + queue + pail logs + Vite
composer run dev:ssr      # Same with SSR enabled
composer run ci:check && npm run build  # Frontend + PHP checks and production build (full CI equivalent)
php artisan test --compact --filter=TestName   # Run a specific test
vendor/bin/pint --dirty --format agent        # Auto-fix PHP style after edits
npm run build             # Needed after frontend changes if dev server isn't running
```

## Architecture

### Routes
- `routes/web.php` — Public + authenticated shell routes (Welcome, Dashboard via `Route::inertia()`)
- `routes/settings.php` — Settings feature routes (Profile, Security, Appearance)

### Controllers
- Single-action convention; settings controllers live in `app/Http/Controllers/Settings/`
- Reference: [ProfileController.php](../app/Http/Controllers/Settings/ProfileController.php), [SecurityController.php](../app/Http/Controllers/Settings/SecurityController.php)

### Validation
- Always use Form Request classes; never inline `$request->validate()`
- Reusable rules go in traits under `app/Concerns/` — see [ProfileValidationRules.php](../app/Concerns/ProfileValidationRules.php)
- Reference: [ProfileUpdateRequest.php](../app/Http/Requests/Settings/ProfileUpdateRequest.php)

### Authentication
- Fortify is configured in [FortifyServiceProvider.php](../app/Providers/FortifyServiceProvider.php) — auth views are Inertia callbacks
- Fortify features are **toggleable** in `config/fortify.php` — tests guard for this via `skipUnlessFortifyFeature()`
- Rate limiting: login 5/min (username+IP), 2FA 5/min (session)

### Frontend
- Pages: `resources/js/pages/` (auth/, settings/, Dashboard.vue, Welcome.vue)
- Components: `resources/js/components/`
- Layouts: `resources/js/layouts/AppLayout.vue`
- Wayfinder imports: `@/actions/App/Http/Controllers/...` (controllers), `@/routes/...` (named routes)
- Reference component: [DeleteUser.vue](../resources/js/components/DeleteUser.vue)

### Shared Inertia Props (via HandleInertiaRequests)
- `auth.user` — authenticated user
- `appName` — from `config('app.name')`
- `sidebarOpen` — sidebar toggle state

## Testing

- **Test environment**: SQLite in-memory (declared in `phpunit.xml`); `RefreshDatabase` auto-applied to all Feature tests
- **Pest syntax**: `test('description', fn() => ...)` with `expect()` assertions
- **Auth helper**: `$this->actingAs($user)`
- **Conditional feature helper**: `$this->skipUnlessFortifyFeature(Features::twoFactorAuthentication())`
- **Inertia assertions**: `->assertInertia(fn (Assert $page) => $page->component('settings/Profile')->where('key', value))`

```php
// Exemplar test pattern
test('user can update profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), ['name' => 'Test'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->name)->toBe('Test');
});
```

Reference tests: [ProfileUpdateTest.php](../tests/Feature/Settings/ProfileUpdateTest.php), [SecurityTest.php](../tests/Feature/Settings/SecurityTest.php)

## Key Reference Files

| Purpose | File |
|---------|------|
| Controller pattern | [ProfileController.php](../app/Http/Controllers/Settings/ProfileController.php) |
| Form Request pattern | [ProfileUpdateRequest.php](../app/Http/Requests/Settings/ProfileUpdateRequest.php) |
| Validation traits | [ProfileValidationRules.php](../app/Concerns/ProfileValidationRules.php) |
| Fortify / auth setup | [FortifyServiceProvider.php](../app/Providers/FortifyServiceProvider.php) |
| Inertia middleware / shared props | [HandleInertiaRequests.php](../app/Http/Middleware/HandleInertiaRequests.php) |
| Vue page + Wayfinder usage | [Profile.vue](../resources/js/pages/settings/Profile.vue) |
| Vue dialog + form component | [DeleteUser.vue](../resources/js/components/DeleteUser.vue) |
| Pest config + helpers | [tests/Pest.php](../tests/Pest.php), [tests/TestCase.php](../tests/TestCase.php) |
