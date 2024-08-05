<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthCard from '@/components/block/AuthCard.vue';
</script>

<template>
  <Head title="Register" />
  <AuthCard>
    <Card class="w-full max-w-sm">
      <CardHeader>
        <CardTitle class="text-2xl">
          Register
        </CardTitle>
        <CardDescription>
          Enter your details below to create your account.
        </CardDescription>
      </CardHeader>
      <CardContent class="grid gap-4">
        <div class="grid gap-2">
          <Label for="name">Name</Label>
          <Input id="name" type="text" placeholder="John Doe" required />
        </div>
        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input id="email" type="email" placeholder="m@example.com" required />
        </div>
        <div class="grid gap-2">
          <Label for="password">Password</Label>
          <Input id="password" type="password" placeholder="Password" required />
        </div>
        <div class="grid gap-2">
          <Label for="confirm-password">Confirm password</Label>
          <Input id="confirm-password" type="password" placeholder="Password confirm" required />
        </div>
      </CardContent>
      <CardFooter>
        <Button class="w-full">
          Create account
        </Button>
      </CardFooter>
    </Card>
    <div class="mt-4 text-center text-sm text-slate-500">
      Already have an account? <a href="/login" class="text-blue-500 hover:underline">Sign in</a>
    </div>
  </AuthCard>
</template>
