<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { ChatNavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import Badge from '@/components/ui/badge/Badge.vue';

defineProps<{
    items: ChatNavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-2">
        <SidebarGroupLabel>Chats</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.name">
                <SidebarMenuButton as-child :is-active="item.href === page.url">
                    <Link :href="item.href" prefetch cache-for="10s" class="relative">
                        <component :is="item.icon" />
                        <span class="truncate block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap">{{ item.name }}</span>
                        <Badge v-if="item.hasNewMessage" variant="highlight" class="text-sm my-1">Unread</Badge>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
