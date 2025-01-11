<script setup lang="ts">
import {
    Sun,
    Bell,
    User,
    Home,
    LineChart,
    Menu,
    Package,
    Search,
    ShoppingCart,
    Users,
    Ticket,
    MessagesSquare,
    Flame,
    Tags,
} from 'lucide-vue-next';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
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
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Team } from '@/types/team';

defineProps<{
    title?: string;
}>();

const showingNavigationDropdown = ref(false);

const switchToTeam = (team: Team) => {
    router.put(
        route('current-team.update'),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        },
    );
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Head :title="title" />

    <Toaster />

    <div class="relative min-h-screen w-full">
        <div class="hidden fixed w-64 inset-y-0 border bg-muted/60 md:block my-5 ml-5 rounded-lg overflow-hidden">
            <div class="flex h-full max-h-screen flex-col gap-2">
                <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                    <a href="/" class="flex items-center gap-1 font-semibold">
                        <Flame class="h-6 w-6" />
                        <span class="">Ignite</span>
                    </a>
                    <Button variant="outline" size="icon" class="ml-auto h-8 w-8">
                        <Bell class="size-5" />
                        <span class="sr-only">Toggle notifications</span>
                    </Button>
                </div>
                <div class="flex-1">
                    <nav class="grid items-start px-2 text-sm font-medium lg:px-4">
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                            :class="{ 'text-primary': route().current('dashboard') }"
                        >
                            <Home class="size-5" />
                            Dashboard
                        </Link>
                        <Link
                            :href="route('channels.index')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <Tags class="size-5" />
                            Channels
                        </Link>
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <Ticket class="size-5" />
                            Tickets
                            <Badge class="ml-auto flex h-6 w-6 shrink-0 items-center justify-center rounded-full">
                                6
                            </Badge>
                        </a>
                        <Link
                            :href="route('channels.index')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <MessagesSquare class="size-5" />
                            Discussions
                        </Link>
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <Users class="size-5" />
                            Users
                        </a>
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <LineChart class="size-5" />
                            Analytics
                        </a>
                    </nav>
                </div>
                <div class="mt-auto p-6">
                    <p class="text-xs text-muted-foreground">Copyright © {{ new Date().getFullYear() }}</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col pb-5 md:ml-[17.1rem]">
            <header class="flex h-14 items-center gap-4 px-4 lg:h-[60px] lg:px-6 mt-5">
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline" size="icon" class="shrink-0 md:hidden">
                            <Menu class="size-5" />
                            <span class="sr-only">Toggle navigation menu</span>
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="flex flex-col">
                        <nav class="grid gap-2 text-lg font-medium">
                            <a href="#" class="flex items-center gap-2 text-lg font-semibold">
                                <Flame class="h-6 w-6" />
                                <span class="sr-only">Ignite</span>
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Home class="size-5" />
                                Dashboard
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl bg-muted px-3 py-2 text-foreground hover:text-foreground"
                            >
                                <ShoppingCart class="size-5" />
                                Orders
                                <Badge class="ml-auto flex h-6 w-6 shrink-0 items-center justify-center rounded-full">
                                    6
                                </Badge>
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Package class="size-5" />
                                Products
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Users class="size-5" />
                                Customers
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <LineChart class="size-5" />
                                Analytics
                            </a>
                        </nav>
                        <div class="mt-auto">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Upgrade to Pro</CardTitle>
                                    <CardDescription>
                                        Unlock all features and get unlimited access to our support team.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <Button size="sm" class="w-full"> Upgrade </Button>
                                </CardContent>
                            </Card>
                        </div>
                    </SheetContent>
                </Sheet>
                <div class="w-full flex-1">
                    <form>
                        <div class="relative">
                            <Search class="absolute left-2.5 top-2 size-5 text-muted-foreground" />
                            <!-- eslint-disable-next-line vuejs-accessibility/form-control-has-label-->
                            <Input
                                type="search"
                                placeholder="Search..."
                                class="w-full appearance-none bg-background shadow-none pl-10 md:w-2/3 lg:w-1/3"
                            />
                        </div>
                    </form>
                </div>
                <Button variant="outline" size="icon">
                    <Sun class="size-5" />
                    <span class="sr-only">Toggle theme</span>
                </Button>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" size="icon" class="rounded-full">
                            <User class="size-5" />
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
                            <Link href="#" as="button" @click.prevent="logout" class="w-full text-left">Logout</Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>
            <main class="mx-auto container flex flex-1 flex-col gap-4 px-4 lg:gap-6 lg:px-6 pt-2">
                <div class="flex items-center mt-1">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        <slot name="header" />
                    </h2>
                </div>
                <div class="-mt-2">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
