<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type ChatNavItem, ChatsData, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Speech, MessageSquare } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
const chats = usePage().props.chats as ChatsData;

const chatsItems: ChatNavItem[] = chats && chats.data
    ? chats.data.map(chat => ({
        name: chat.name,
        href: `/chats/${chat.id}`,
        icon: MessageSquare,
    }))
    : [];

const footerNavItems: NavItem[] = [
    {
        title: 'New Chat',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Speech,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="chatsItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
