<script setup lang="ts"></script>

<template>
    <div class="w-full lg:grid lg:grid-cols-2 min-h-screen">
        <div class="flex items-center justify-center py-12">
            <slot />
        </div>
        <div class="hidden bg-muted lg:block max-h-screen overflow-hidden">
            <!-- Stars background here -->
            <img
                src="https://images.unsplash.com/photo-1617309215127-ebeb4eddeba5?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Ignite"
                width="1920"
                height="1080"
                class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
            />
        </div>
    </div>
</template>
