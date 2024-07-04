<script setup lang="ts">
import { route } from 'ziggy-js';
import { ref, watch, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useMotion } from '@vueuse/motion';
import { useWindowSize } from '@vueuse/core';
import { ChevronDownIcon, MenuIcon, PanelLeftCloseIcon, PanelLeftOpenIcon } from 'lucide-vue-next';
import { Button, Popover, PopoverContent, PopoverTrigger } from '@netwatch-global/ui';
import AnimatedNetwatchLogo from '@/components/logo/AnimatedNetwatchLogo.vue';
import NavigationList from '@/components/navigation/NavigationList.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import NavigationFooter from '@/components/navigation/NavigationFooter.vue';
import GlobalSearch from '@/components/search/GlobalSearch.vue';
import NotificationSheet from '@/components/NotificationSheet.vue';
import { Toaster } from '@/components/ui/sonner';
import { NAVIGATION_LIST } from '@/const/navigation';

const NAV_WIDTH = 320;

const nav = ref(null);
const navWrapper = ref(null);
const header = ref(null);
const main = ref(null);
const minimise = ref(null);

const showMobileNav = ref(false);
const minimising = ref(false);
const navMinimised = ref(false);

const navItems = ref(NAVIGATION_LIST);

const { width: windowWidth } = useWindowSize();

const navWrapperMotion = useMotion(navWrapper, {
    initial: {
        opacity: 0,
        x: -100,
        width: NAV_WIDTH,
    },
    enter: {
        opacity: 1,
        x: 0,
        transition: {
            duration: 300,
        },
    },
});

const navMotion = useMotion(nav, {
    initial: {
        y: 0,
    },
    isMinimised: {
        y: 65,
        transition: {
            type: 'spring',
            duration: 300,
        },
    },
});

router.on('navigate', () => {
    showMobileNav.value = false;
    checkActiveItem();
});

function handleNavOpen(open: boolean) {
    showMobileNav.value = open;
}

function checkActiveItem() {
    const current = route().current();

    if (!current) return;

    navItems.value = navItems.value.map((group) => {
        group.items = group.items.map((item) => {
            if (Array.isArray(item.match)) {
                item.active = item.match.some((match) => current.startsWith(match));
                return item;
            }

            item.active = current.startsWith(item.match || 'notactive');

            return item;
        });

        return group;
    });
}

async function handleMinimise(minmise: boolean) {
    minimising.value = true;

    if (minmise) {
        minimiseMotion.apply({
            y: 60,
            x: 0,
            transition: {
                duration: 300,
            },
        });

        navMotion.variant.value = 'isMinimised';

        navWrapperMotion.apply({
            width: 69,
            transition: {
                duration: 400,
                delay: 200,
                type: 'spring',
            },
        });

        navMinimised.value = true;
    } else {
        minimiseMotion.apply({
            y: 0,
            x: 0,
            transition: {
                duration: 200,
                delay: 200,
            },
        });

        await navWrapperMotion.apply({
            opacity: 1,
            x: 0,
            width: NAV_WIDTH,
            transition: {
                duration: 400,
                type: 'spring',
            },
        });

        navMotion.variant.value = 'initial';

        navMinimised.value = false;
    }
}

watch(navMinimised, (minimised) => {
    if (!minimised) {
        minimising.value = false;
        return;
    }
});

const minimiseMotion = useMotion(minimise, {
    initial: {
        y: 0,
        x: 0,
    },
});

useMotion(header, {
    initial: {
        opacity: 0,
        y: -30,
    },
    enter: {
        opacity: 1,
        y: 0,
        transition: {
            duration: 250,
            delay: windowWidth.value > 1024 ? 300 : 0,
        },
    },
});

useMotion(main, {
    initial: {
        opacity: 0,
        y: 10,
    },
    enter: {
        opacity: 1,
        y: 0,
        transition: {
            duration: 600,
            delay: windowWidth.value > 1024 ? 500 : 200,
        },
    },
});

onMounted(() => {
    checkActiveItem();
});
</script>

<template>
    <div id="search-provider">
        <!-- global search teleport location -->
    </div>

    <div id="modal-provider">
        <!-- global modal teleport location -->
    </div>

    <Toaster />

    <TooltipProvider>
        <div class="flex h-screen max-w-[100vw]">
            <div
                ref="navWrapper"
                v-motion
                class="bg-background border-r border-border h-full hidden relative lg:flex flex-col z-20 grow shrink-0"
            >
                <div class="p-4 relative w-full mb-4 h-16">
                    <Link href="/" class="block">
                        <span class="sr-only">Netwatch</span>
                        <AnimatedNetwatchLogo class="h-[31px] w-auto mt-1" :reduced="navMinimised" animate />
                    </Link>
                    <Button
                        ref="minimise"
                        variant="outline"
                        size="icon"
                        class="absolute right-3 top-3"
                        @click="handleMinimise(!navMinimised)"
                    >
                        <PanelLeftCloseIcon v-if="!navMinimised" class="h-5 w-5 stroke-2" />
                        <PanelLeftOpenIcon v-else class="h-5 w-5 stroke-2" />
                    </Button>
                </div>

                <NavigationList
                    ref="nav"
                    :items="navItems"
                    :minimised="navMinimised"
                    class="transition-[padding] overflow-y-auto"
                    :class="{ 'pl-4 pr-2': !navMinimised, 'px-2': navMinimised }"
                />
                <NavigationFooter :minimised="navMinimised" />
            </div>

            <div class="flex flex-col w-full h-screen overflow-hidden">
                <header
                    ref="header"
                    class="p-2 bg-background lg:justify-end fixed z-10 transition-[width] duration-200 w-full"
                    :class="{ 'w-[calc(100vw-320px)] duration-150': !minimising, 'w-[calc(100vw-69px)]': minimising }"
                >
                    <div class="h-px w-[100vw] bg-border absolute right-0 bottom-0" />
                    <div class="flex items-center gap-x-4 justify-between">
                        <div class="hidden lg:block w-[400px]">
                            <GlobalSearch />
                        </div>

                        <div class="flex items-center gap-x-4 justify-between w-full lg:justify-end">
                            <Link href="/" class="block">
                                <span class="sr-only">Netwatch</span>
                                <AnimatedNetwatchLogo class="h-7 w-auto lg:hidden" animate />
                            </Link>
                            <div class="flex items-center gap-x-2">
                                <div class="block lg:hidden">
                                    <GlobalSearch size="sm" />
                                </div>

                                <Popover>
                                    <PopoverTrigger class="hidden lg:block">
                                        <Button variant="outline">
                                            New
                                            <ChevronDownIcon class="h-5 w-5 ml-2" />
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent side="bottom" align="end">
                                        <div>items</div>
                                    </PopoverContent>
                                </Popover>

                                <ThemeSwitcher />

                                <NotificationSheet />

                                <Popover @update:open="handleNavOpen">
                                    <PopoverTrigger class="lg:hidden">
                                        <Button variant="outline" size="icon">
                                            <MenuIcon class="h-5 w-5" />
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent v-if="showMobileNav" class="lg:hidden" side="bottom" align="end">
                                        <ScrollArea class="w-full h-[350px]">
                                            <NavigationList :items="navItems" class="gap-y-6 h-[350px]" />
                                        </ScrollArea>
                                        <div class="h-px bg-border mb-4" />
                                        <NavigationFooter :minimised="navMinimised" />
                                    </PopoverContent>
                                </Popover>
                            </div>
                        </div>
                    </div>
                </header>

                <main ref="main" class="bg-background min-h-screen pt-20 w-full overflow-y-auto">
                    <slot />
                </main>
            </div>
        </div>
    </TooltipProvider>
</template>
