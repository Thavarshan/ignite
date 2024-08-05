<script setup lang="ts"></script>

<template>
  <div>
    <div class="flex items-center justify-center min-h-screen bg-gray-50">
      <div class="w-full max-w-sm">
        <div class="flex flex-col items-center">
          <div class="w-full rounded-lg">
            <slot></slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
