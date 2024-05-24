<template>
    <p v-motion :initial="{ opacity: 0, y: 10 }" :enter="{ opacity: 1, y: 0 }" class="text-[0.8rem] font-medium text-destructive">
        <slot />
    </p>
</template>
