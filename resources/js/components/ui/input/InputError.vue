<template>
    <p v-motion :initial="{ opacity: 0, y: 10 }" :enter="{ opacity: 1, y: 0 }" class="text-red-600 text-xs mt-1">
        <slot />
    </p>
</template>
