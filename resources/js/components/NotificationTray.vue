<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const page = usePage();
const notifications = ref(page.props.notifications || []);
const isOpen = ref(false);
const isLoading = ref(false);

const toggleTray = () => {
    isOpen.value = !isOpen.value;
};

const markAsRead = async (id: string) => {
    try {
        isLoading.value = true;
        await router.put(route('notifications.read', { id }));
        notifications.value = notifications.value.filter(n => n.id !== id);
    } catch (error) {
        console.error('Failed to mark notification as read:', error);
    } finally {
        isLoading.value = false;
    }
};

const markAllAsRead = async () => {
    try {
        isLoading.value = true;
        await router.put(route('notifications.readAll'));
        notifications.value = [];
    } catch (error) {
        console.error('Failed to mark all notifications as read:', error);
    } finally {
        isLoading.value = false;
    }
};

const deleteNotification = async (id: string) => {
    try {
        isLoading.value = true;
        await router.delete(route('notifications.destroy', { id }));
        notifications.value = notifications.value.filter(n => n.id !== id);
    } catch (error) {
        console.error('Failed to delete notification:', error);
    } finally {
        isLoading.value = false;
    }
};

const formatNotification = (notification: any) => {
    switch (notification.type) {
        case 'App\\Notifications\\AddedToGroup':
            return {
                message: `${notification.data.user_name} added you to ${notification.data.chat_name}`,
                link: route('chat.show', { id: notification.data.chat_id }),
                created_at: new Date(notification.created_at).toLocaleDateString()
            };
        default:
            return {
                message: 'New notification',
                link: null,
                created_at: new Date(notification.created_at).toLocaleDateString()
            };
    }
};
</script>

<template>
    <div class="relative">
        <button
            @click="toggleTray"
            class="p-2 text-gray-600 hover:text-gray-800 focus:outline-none"
        >
            <div class="relative">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    />
                </svg>
                <span
                    v-if="notifications.length > 0"
                    class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full h-4 w-4 flex items-center justify-center text-xs"
                >
                    {{ notifications.length }}
                </span>
            </div>
        </button>

        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50"
        >
            <div class="px-4 py-2 border-b flex items-center justify-between">
                <h3 class="font-medium text-gray-900">Notifications</h3>
                <button
                    v-if="notifications.length > 0"
                    @click="markAllAsRead"
                    class="text-sm text-blue-600 hover:text-blue-800 disabled:opacity-50"
                    :disabled="isLoading"
                >
                    Mark all as read
                </button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <div v-if="notifications.length === 0" class="px-4 py-2 text-gray-500">
                    No new notifications
                </div>
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="group px-4 py-3 hover:bg-gray-50 border-b last:border-b-0"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div v-if="notification.type === 'App\\Notifications\\AddedToGroup'">
                                <Link
                                    :href="formatNotification(notification).link"
                                    class="block"
                                    @click="markAsRead(notification.id)"
                                >
                                    <p class="text-sm text-gray-800">
                                        {{ formatNotification(notification).message }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ formatNotification(notification).created_at }}
                                    </p>
                                </Link>
                            </div>
                            <div v-else>
                                <p class="text-sm text-gray-800">
                                    {{ formatNotification(notification).message }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ formatNotification(notification).created_at }}
                                </p>
                            </div>
                        </div>
                        <div class="ml-4 flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <button
                                @click="markAsRead(notification.id)"
                                class="text-blue-600 hover:text-blue-800 p-1"
                                :disabled="isLoading"
                                title="Mark as read"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button
                                @click="deleteNotification(notification.id)"
                                class="text-red-600 hover:text-red-800 p-1"
                                :disabled="isLoading"
                                title="Delete"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
