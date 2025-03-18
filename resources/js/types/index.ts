import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';

declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
    }
}


export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface ChatNavItem {
    name: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface ChatMember {
    id: number;
    name: string;
    email: string;
}

export interface ChatOwner {
    id: number;
    name: string;
    email: string;
}

export interface Chat {
    id: number;
    name: string;
    owner: ChatOwner;
    members: ChatMember[][];
    created_at: string | null;
    updated_at: string;
}

export interface ChatsData {
    data: Chat[];
}

export interface Sender {
    user_id: number;
    name: string;
    email: string;
}

export interface Message {
    id: number;
    message: string;
    created_at: string;
    updated_at: string;
    sender: Sender;
    chat_id: number;
}

export type BreadcrumbItemType = BreadcrumbItem;
