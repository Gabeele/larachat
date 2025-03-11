<template>
    <AppLayout>
        <div class="flex items-center justify-around">
            <Card class="w-[350px]">
                <CardHeader>
                    <CardTitle>Create Chat</CardTitle>
                    <CardDescription>Create a real time text chat.</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submitForm">
                        <div class="grid w-full items-center gap-4">
                            <div class="flex flex-col space-y-1.5">
                                <Label for="name">Chat's Name</Label>
                                <Input id="name" v-model="form.name" placeholder="Social Hub" />
                                <div v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="flex flex-col space-y-1.5">
                                <Label for="members">Members</Label>
                                <div class="relative">
                                    <Input
                                        id="members"
                                        v-model="memberInput"
                                        placeholder="Search for members"
                                        @keydown.enter.prevent="addMember"
                                        @input="showDropdown = true"
                                        @blur="delayHideDropdown"
                                        @focus="showDropdown = true"
                                    />
                                    <!-- Dropdown menu for users -->
                                    <div v-show="showDropdown && filteredUsers.length > 0"
                                         class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-md border border-gray-700 bg-gray-900 shadow-lg">
                                        <div v-for="user in filteredUsers"
                                             :key="user.id"
                                             class="px-4 py-2 text-white hover:bg-gray-800 cursor-pointer"
                                             @mousedown.prevent="selectUser(user)">
                                            {{ user.name }}
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.errors.member_ids" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.member_ids }}
                                </div>
                                <!-- Selected members display -->
                                <div v-if="form.member_ids.length > 0" class="mt-2 flex flex-wrap gap-2">
                                    <div
                                        v-for="(memberId, index) in form.member_ids"
                                        :key="memberId"
                                        class="flex items-center gap-1 rounded-full bg-primary/20 px-3 py-1 text-sm text-primary"
                                    >
                                        {{ getUserName(memberId) }}
                                        <button type="button" @click="removeMember(index)" class="text-primary/70 hover:text-primary">×</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </CardContent>
                <CardFooter class="flex justify-between px-6 pb-6">
                    <Button variant="outline" type="button" @click="form.reset()">Cancel</Button>
                    <Button type="submit" @click="submitForm" :disabled="form.processing">
                        {{ form.processing ? 'Creating chat...' : 'Create' }}
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card/index.js';
import AppLayout from '@/layouts/AppLayout.vue';

interface User {
    id: number;
    name: string;
    email: string;
}

interface PageProps {
    users: Record<string, User>;
    auth: {
        user: User;
    };
}

export default defineComponent({
    components: {
        AppLayout,
        Card,
        CardHeader,
        CardTitle,
        CardDescription,
        CardContent,
        CardFooter,
        Button,
        Input,
        Label,
    },
    setup() {
        // Access Inertia page props
        const page = usePage<PageProps>();

        // Convert the users object into an array
        const users = computed(() => {
            const usersObject = page.props.users || {};
            return Object.values(usersObject);
        });

        const memberInput = ref('');
        const showDropdown = ref(false);

        // Filtered users for dropdown
        const filteredUsers = computed(() => {
            if (!users.value || users.value.length === 0) {
                console.log('No users available in page props');
                return [];
            }

            const search = memberInput.value.toLowerCase();
            // Filter out the current user from the dropdown options
            return users.value.filter(
                user =>
                    user.id !== page.props.auth.user.id &&
                    (!search || user.name.toLowerCase().includes(search)) &&
                    !form.member_ids.includes(user.id)
            );
        });

        // Initialize form with Inertia useForm
        const form = useForm({
            name: '',
            member_ids: [] as number[],
        });

        const getUserName = (userId: number) => {
            const user = users.value.find((user) => user.id === userId);
            return user ? user.name : 'Unknown User';
        };

        const selectUser = (user: User) => {
            if (!form.member_ids.includes(user.id)) {
                form.member_ids.push(user.id);
                memberInput.value = '';
                // Reset dropdown state after selection
                setTimeout(() => {
                    showDropdown.value = true;
                }, 50);
            }
        };

        const addMember = () => {
            if (!memberInput.value || !users.value) return;

            const user = users.value.find(
                (user) => user.name.toLowerCase() === memberInput.value.toLowerCase()
            );

            if (user && !form.member_ids.includes(user.id)) {
                form.member_ids.push(user.id);
                memberInput.value = '';
            }
        };

        const removeMember = (index: number) => {
            form.member_ids.splice(index, 1);
        };

        const delayHideDropdown = () => {
            // Use a longer delay to ensure clicks register
            setTimeout(() => {
                showDropdown.value = false;
            }, 200);
        };

        const submitForm = () => {
            form.post(route('chat.store'), {
                onSuccess: () => {
                    form.reset();
                },
            });
        };

        return {
            users,
            filteredUsers,
            form,
            memberInput,
            showDropdown,
            getUserName,
            selectUser,
            addMember,
            removeMember,
            delayHideDropdown,
            submitForm,
        };
    },
});
</script>
