<template>
    <AppLayout>
        <div class="flex flex-col h-[calc(100vh-4rem)]"> <!-- Adjust for header height -->
            <!-- Chat Header -->
            <div class="border-b p-4">
                <h2 class="text-lg font-semibold">Chat</h2>
            </div>

            <!-- Messages Container -->
            <div
                ref="messageContainer"
                class="flex-1 overflow-y-auto p-4 space-y-4"
            >
                <div v-for="message in messages" :key="message.id" class="flex gap-3">
                    <div
                        :class="[
                            'flex max-w-[80%] flex-col gap-2 rounded-lg px-4 py-2',
                            message.sender.user_id === user.id
                                ? 'ml-auto bg-primary text-primary-foreground'
                                : 'bg-muted'
                        ]"
                    >
                        <div
                            v-if="message.sender.user_id !== user.id"
                            class="text-sm font-semibold"
                        >
                            {{ message.sender.name }}
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
import { ref, onMounted, nextTick, watch } from 'vue'
import { usePage, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage()
const chat = page.props.chat.data
const messages = page.props.messages.data ?? []
const user = page.props.auth.user
const messageContainer = ref<HTMLElement | null>(null)
const newMessage = ref('')

const scrollToBottom = async () => {
    await nextTick()
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight
    }
}

onMounted(() => {
    scrollToBottom()
})

const formatDate = (date: string) => {
    return new Date(date).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    })
}

const sendMessage = () => {
    if (!newMessage.value.trim()) return

    router.post(route('chat.messages', chat.id), {
        message: newMessage.value
    }, {
        replace: true,
        preserveScroll: true,
        onSuccess: () => {
            newMessage.value = ''
            scrollToBottom()
        }
    })
}

watch(
    () => messages,
    () => {
        scrollToBottom()
    },
    { deep: true }
)
</script>

<style scoped>

</style>
