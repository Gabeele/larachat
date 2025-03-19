<template>
    <AppLayout>
        <div class="flex flex-col h-[calc(100vh-4rem)]"> <!-- Adjust for header height -->
            <!-- Chat Header -->
            <div class="border-b p-4">
                <h2 class="text-lg font-semibold">{{chat.name}}</h2>
                <p class="flex gap-2 text-sm text-muted-foreground">
                    <span v-for="(member, index) in chat.members[0]" :key="member.id">
                        {{ member.name }}{{ index < chat.members[0].length - 1 ? ',' : '' }}
                    </span>
                </p>
            </div>

            <!-- Messages Container -->
            <div
                ref="messageContainer"
                class="flex-1 overflow-y-auto p-4 flex flex-col-reverse"
            >
                    <div class="space-y-4">
                        <div v-for="message in messages" :key="message.id" class="flex gap-3">
                            <div
                                :class="[
                                    'flex max-w-[80%] flex-col gap-2 rounded-lg px-4 py-2',
                                    message.user_id === user.id
                                        ? 'ml-auto bg-primary text-primary-foreground'
                                        : 'bg-muted'
                                ]"
                            >
                                <div
                                    v-if="message.user_id !== user.id"
                                    class="text-sm font-semibold"
                                >
                                    {{ message.user_id }}
                                </div>

                                <div class="text-sm">
                                    {{ message.message }}
                                </div>

                                <div class="text-xs opacity-70">
                                    {{ formatDate(message.created_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="border-t p-4 bg-background">
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <Input
                        v-model="newMessage"
                        placeholder="Type a message..."
                        class="flex-1"
                    />
                    <Button type="submit">
                        Send
                    </Button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import {ref, onMounted} from 'vue';
import { usePage, useForm,} from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Chat, User, Message } from '@/types';
import { PageProps } from '@inertiajs/core';

interface Props {
    chat: {
        data: Chat;
    };
    messages: {
        data: Message[];
    };
    auth: {
        user: User;
    };
}

const page = usePage<PageProps & Props>();
const chat = page.props.chat.data as Chat;
const user = page.props.auth.user as User;
const messageContainer = ref<HTMLElement | null>(null);
const newMessage = ref<string>('');

const messages = ref<Message[]>(page.props.messages.data ?? []);

const form = useForm({
    message: ''
});

const formatDate = (date: string): string => {
    return new Date(date).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
};

const sendMessage = (): void => {
    if (!newMessage.value.trim()) return;

    form.message = newMessage.value;
    form.post(route('message.store', chat.id), {
        preserveScroll: true,
        only: ['messages'],
        onSuccess: () => {
            newMessage.value = '';
            form.message = '';
        }
    });
};

onMounted(() => {

    window.Echo.private(`chat`)
        .listen('MessageSavedEvent', (e) => {
            console.log(e);
            messages.value.push(e.message)
        });

});

</script>

<style scoped>

</style>
