<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { ChatNavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: ChatNavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Chats</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.name">
                <SidebarMenuButton as-child :is-active="item.href === page.url">
                    <Link :href="item.href" prefetch cache-for="10s" class="relative">
                        <component :is="item.icon" />
                        <span>{{ item.name }}</span>
                        <span 
                            v-if="item.hasNewMessage"
                            class="absolute right-0 top-0 -translate-y-1/2 translate-x-1/2 h-2 w-2 rounded-full bg-primary"
                            aria-hidden="true"
                        />
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
