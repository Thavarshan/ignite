<script setup lang="ts"></script>

<template>
    <div class="w-full lg:grid lg:grid-cols-2 min-h-screen">
        <div class="flex items-center justify-center py-12">
            <slot />
        </div>
        <div class="hidden bg-muted lg:block max-h-screen overflow-hidden">
            <!-- Stars background here -->
            <div class="stars-background" />
        </div>
    </div>
</template>

<style scoped>
.stars-background {
    width: 100%;
    height: 100%;
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradient 5s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}
</style>
