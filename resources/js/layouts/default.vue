<script setup lang="ts">
import { CircleUser, Menu, Package2, Search } from 'lucide-vue-next';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import { Head, router, Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    withMenu?: boolean;
}>();

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Head :title="title" />

    <Toaster />

    <div class="flex min-h-screen w-full flex-col bg-muted/40">
        <header class="sticky top-0 h-16 border-b bg-background">
            <div class="container flex items-center gap-4 px-4 md:px-6 h-full">
                <nav
                    class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6"
                >
                    <a href="#" class="flex items-center gap-2 text-lg font-semibold md:text-base">
                        <Package2 class="h-6 w-6" />
                        <span class="sr-only">Acme Inc</span>
                    </a>
                    <a href="#" class="text-muted-foreground transition-colors hover:text-foreground"> Channels </a>
                    <a href="#" class="text-muted-foreground transition-colors hover:text-foreground"> Newest </a>
                    <a href="#" class="text-muted-foreground transition-colors hover:text-foreground"> Popular </a>
                </nav>
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline" size="icon" class="shrink-0 md:hidden">
                            <Menu class="h-5 w-5" />
                            <span class="sr-only">Toggle navigation menu</span>
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left">
                        <nav class="grid gap-6 text-lg font-medium">
                            <a href="#" class="flex items-center gap-2 text-lg font-semibold">
                                <Package2 class="h-6 w-6" />
                                <span class="sr-only">Acme Inc</span>
                            </a>
                            <a href="#" class="text-muted-foreground hover:text-foreground"> Dashboard </a>
                            <a href="#" class="text-muted-foreground hover:text-foreground"> Orders </a>
                            <a href="#" class="text-muted-foreground hover:text-foreground"> Products </a>
                            <a href="#" class="text-muted-foreground hover:text-foreground"> Customers </a>
                            <a href="#" class="hover:text-foreground"> Settings </a>
                        </nav>
                    </SheetContent>
                </Sheet>
                <div class="flex w-full items-center gap-4 md:ml-auto md:gap-2 lg:gap-4">
                    <form class="ml-auto flex-1 sm:flex-initial">
                        <div class="relative">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <!-- eslint-disable-next-line vuejs-accessibility/form-control-has-label-->
                            <Input
                                type="search"
                                placeholder="Search discussions..."
                                class="pl-8 sm:w-[300px] md:w-[200px] lg:w-[300px]"
                            />
                        </div>
                    </form>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="secondary" size="icon" class="rounded-full">
                                <CircleUser class="h-5 w-5" />
                                <span class="sr-only">Toggle user menu</span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel> Jerome </DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem class="cursor-pointer" as-child>
                                <Link :href="route('profile.show')"> Profile </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem class="cursor-pointer" as-child>
                                <Link :href="route('profile.edit')"> Settings </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem class="cursor-pointer" as-child>
                                <Link :href="route('profile.show')"> Support </Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem class="cursor-pointer" @click="logout">
                                <Link href="#" as="button" @click.prevent="logout" class="w-full text-left">
                                    Logout
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </header>
        <main class="mx-auto container flex flex-1 flex-col gap-4 px-4 lg:gap-6 lg:px-6 py-10">
            <div class="mx-auto grid w-full gap-2">
                <h1 class="text-3xl font-semibold">{{ title }}</h1>
            </div>
            <div
                v-if="withMenu"
                class="mx-auto grid w-full items-start gap-6 md:grid-cols-[180px_1fr] lg:grid-cols-[250px_1fr]"
            >
                <nav class="grid gap-4 text-sm text-muted-foreground">
                    <slot name="menu" />
                </nav>
                <div class="grid gap-6">
                    <slot />
                </div>
            </div>
            <div v-else>
                <div class="mx-auto grid w-full gap-6">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
