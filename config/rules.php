<?php

// phpcs:ignoreFile

use Ignite\Billing\Enums\ChargeType;
use Ignite\Billing\Enums\DispatchMethod;
use Ignite\Billing\Enums\PaymentMethod;
use Ignite\Billing\Enums\PaymentType;
use Ignite\Billing\Enums\PriceType;
use Ignite\Billing\Rules\DispatchMethodMetadataRule;
// use Ignite\Shared\Enums\Priority;
use Ignite\Shared\Enums\Resource;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

return [
    /*
     * Rules for the users resource.
     */
    Resource::USERS->value => [
        'firstname' => ['required', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Resource::USERS->value],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'current_team_id' => ['nullable', 'integer', 'exists:' . Resource::TEAMS->value . ',id'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        'locked' => ['nullable', 'boolean'],
    ],

    /*
     * Rules for the roles resource.
     */
    Resource::ROLES->value => [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:255'],
    ],

    /*
     * Rules for the roles_permissions resource.
     */
    Resource::ROLES->value . '_' . Resource::PERMISSIONS->value => [
        'permissions' => ['required', 'array'],
        'permissions.*' => ['required', 'string', 'exists:' . Resource::PERMISSIONS->value . ',name'],
    ],

    /*
     * Rules for the permissions resource.
     */
    Resource::PERMISSIONS->value => [
        'name' => ['required', 'string', 'max:255'],
    ],

    /*
     * Rules for the changing password.
     */
    'password' => [
        'required', 'string', new Password(), 'confirmed',
    ],

    /*
     * Rules for the teams resource.
     */
    Resource::TEAMS->value => [
        'name' => ['required', 'string', 'min:1', 'max:255'],
        'description' => ['nullable', 'string', 'min:1', 'max:255'],
    ],

    /*
     * Rules for the team members resource.
     */
    Resource::TEAMS->value . '_' . Resource::USERS->value => [
        'members' => ['required', 'array'],
        'members.*' => ['required', 'integer', 'exists:' . Resource::USERS->value . ',id'],
    ],

    /*
     * Rules for the invitations resource.
     */
    Resource::INVITATIONS->value => [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Resource::INVITATIONS->value],
        'role' => [
            'nullable',
            'string',
            'exists:' . Resource::ROLES->value . ',name',
        ],
    ],

    /*
     * Rules for the user related settings resource.
     */
    Resource::USERS->value . '_' . Resource::SETTINGS->value => [
        'notifications' => ['required', 'array'],
        'notifications.*' => ['nullable'],
    ],

    /*
     * Rules for the categories resource.
     */
    Resource::CHANNELS->value => [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:255'],
        'paarent_id' => ['nullable', 'integer', 'exists:' . Resource::CHANNELS->value . ',id'],
    ],

    /*
     * Rules for the replies resource.
     */
    Resource::REPLIES->value => [
        'body' => ['required', 'string', 'max:255'],
        'user_id' => ['nullable', 'integer', 'exists:' . Resource::USERS->value . ',id'],
        'locked' => ['nullable', 'boolean'],
    ],

    /*
     * Rules for the tasks resource.
     */
    Resource::TASKS->value => [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:1024'],
        'taskable_type' => ['nullable', 'string', 'max:255'],
        'taskable_id' => ['nullable', 'integer'],
        'organisation_id' => ['required', 'integer', 'exists:organisations,id'],
    ],

    /*
     * Rules for the notes resource.
     */
    Resource::NOTES->value => [
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string'],
        'tags' => ['nullable', 'array'],
        'tags.*' => ['integer', 'exists:categories,id'],
    ],

    /*
     * Rules for the prices resource.
     */
    Resource::PRICES->value => [
        // 'id' => ['sometimes', 'integer', 'exists:' . Resource::PRICES->value . ',id'],
        // 'amount' => ['required_without:id', 'integer', 'min:0'],
        // 'type' => ['required_without:id', Rule::enum(PriceType::class)],
        // 'currency' => ['required_without:id', 'string', 'max:255'],
        // 'description' => ['sometimes', 'nullable', 'string', 'min:1', 'max:255'],
        // 'discount' => ['sometimes', 'nullable', 'integer', 'min:0'],
        // 'tax' => ['sometimes', 'nullable', 'integer', 'min:0'],
        // 'metadata' => ['sometimes', 'nullable', 'array'],
    ],

    /*
     * Rules for the charges resource.
     */
    Resource::CHARGES->value => [
        // 'description' => ['nullable', 'string', 'min:1', 'max:255'],
        // 'amount' => ['required', 'integer', 'min:0'],
        // 'type' => ['required', Rule::enum(ChargeType::class)],
        // 'metadata' => ['nullable', 'array'],
        // 'confirmed_at' => ['nullable', 'date'],
        // 'cancelled_at' => ['nullable', 'date'],
        // 'refunded_at' => ['nullable', 'date'],
    ],

    /*
     * Rules for the payments resource.
     */
    Resource::PAYMENTS->value => [
        // 'type' => ['required', Rule::enum(PaymentType::class)],
        // 'charges' => ['required', 'array'],
        // 'charges.*' => ['required', 'integer', 'exists:' . Resource::CHARGES->value . ',id'],
        // 'payment_method_id' => ['required', 'integer', 'exists:' . Resource::PAYMENT_METHODS->value . ',id'],
        // 'dispatch_method_id' => ['required', 'integer', 'exists:' . Resource::DISPATCH_METHODS->value . ',id'],
        // 'description' => ['nullable', 'string', 'max:255'],
        // 'currency' => ['required', 'string', 'max:255'],
        // 'metadata' => ['nullable', 'array'],
        // 'confirmed_at' => ['nullable', 'date'],
        // 'cancelled_at' => ['nullable', 'date'],
        // 'refunded_at' => ['nullable', 'date'],
    ],

    /*
     * Rules for the payment methods resource.
     */
    Resource::PAYMENT_METHODS->value => [
        // 'name' => ['required', 'string', 'max:255'],
        // 'description' => ['nullable', 'string', 'max:1024'],
        // 'is_default' => ['required', 'boolean'],
        // 'is_active' => ['nullable', 'boolean'],
        // 'type' => ['required', Rule::enum(PaymentMethod::class)],
        // 'currency' => ['required', 'string', 'max:255'],
        // 'metadata' => ['required', 'array'],
        // 'metadata.payable_to' => ['required_if:type,' . PaymentMethod::CHEQUE->value, 'string', 'max:255'],
        // 'metadata.recipient' => ['required_if:type,' . PaymentMethod::BACS->value, 'string', 'max:255'],
        // 'metadata.reference' => ['required_if:type,' . PaymentMethod::BACS->value, 'string', 'max:255'],
        // 'metadata.sort_code' => ['required_if:type,' . PaymentMethod::BACS->value, 'string', 'max:255'],
        // 'metadata.account_number' => ['required_if:type,' . PaymentMethod::BACS->value, 'string', 'max:255'],
        // 'locked' => ['nullable', 'boolean'],
    ],

    /*
     * Rules for the dispatch methods resource.
     */
    Resource::DISPATCH_METHODS->value => [
        // 'name' => ['required', 'string', 'max:255'],
        // 'description' => ['nullable', 'string', 'max:1024'],
        // 'is_default' => ['required', 'boolean'],
        // 'type' => ['required', Rule::enum(DispatchMethod::class)],
        // 'metadata' => ['required', 'array', new DispatchMethodMetadataRule()],
        // 'locked' => ['nullable', 'boolean'],
    ],
];
