<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="user-list card">
                        <div class="card-header">
                            Пользователи
                        </div>
                        <ul class="list-group list-group-flush">
                            <li v-for="user in users" :key="user.id" @click="selectRecipient(user.id)" class="list-group-item">
                                {{ user.name }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="chat-box card">
                        <div v-if="selectedRecipient" class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Чат с пользователем:</strong> {{ selectedRecipientName }}
                            </div>
                            <div>
                                <a :href="`/profile/${selectedRecipient}`" class="btn btn-link">Перейти в профиль</a>
                            </div>
                        </div>
                        <div v-else class="card-header">
                            <div class="text-center">
                                <strong>Выберите пользователя для начала диалога</strong>
                            </div>
                        </div>

                        <div class="card-body" ref="chatBox" style="height: 300px; overflow-y: scroll;" @scroll="handleScroll">
                            <div v-for="message in messages" :key="message.id" class="message">
                                <strong>{{ message.sender.name }}:</strong> {{ message.content }}
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="sendMessage" class="mt-3">
                        <div class="input-group">
                            <input v-model="newMessage" placeholder="Type a message" class="form-control" />
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';
import DefaultLayout from "../../Shared/DefaultLayout.vue";
import axios from 'axios';

export default {
    components: {DefaultLayout},
    props: {
        initialMessages: Array,
        users: Array,
        selectedRecipient: Number,
        current_user_id: Number,
    },
    data() {
        return {
            messages: [...this.initialMessages],
            newMessage: '',
            scrollPosition: 0,
            isAtBottom: true,
            selectedRecipientName: '',
        };
    },
    mounted() {
        this.initializePusher();
        this.restoreScrollPosition();
        this.restoreSelectedRecipientName();
    },
    methods: {
        initializePusher() {
            window.Pusher = Pusher;
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                forceTLS: true,
            });
            Pusher.logToConsole = true;


                window.Echo.private(`chat.3`)
                    .listen(".new-message", (e) => {
                        const senderName = this.users.find(user => user.id === e.sender);
                        const receiverName = this.users.find(user => user.id === e.receiver);
                        if (this.$refs.chatBox) {
                            this.scrollPosition = this.$refs.chatBox.scrollTop;
                            localStorage.setItem(`scrollPosition_${this.selectedRecipient}`, this.scrollPosition);
                        }
                        const isCurrentUserSender = e.sender !== this.current_user_id;
                        this.messages.push({
                            content: e.message,
                            sender: {
                                name: isCurrentUserSender ? senderName.name : receiverName.name
                            },
                            receiver: {
                                name: isCurrentUserSender ? receiverName.name : senderName.name
                            }
                        });
                        this.$nextTick(() => {
                            if (this.$refs.chatBox) {
                                if (this.isAtBottom) {
                                    this.$refs.chatBox.scrollTop = this.$refs.chatBox.scrollHeight;
                                } else {
                                    this.$refs.chatBox.scrollTop = this.scrollPosition;
                                }
                            }
                        });
                    });

        },
        selectRecipient(recipientId) {
            if (this.$refs.chatBox) {
                this.scrollPosition = this.$refs.chatBox.scrollTop;
                localStorage.setItem(`scrollPosition_${this.selectedRecipient}`, this.scrollPosition);
            }
            const recipientUser = this.users.find(user => user.id === recipientId);
            this.selectedRecipientName = recipientUser.name;
            localStorage.setItem(`selectedRecipientName_${recipientId}`, this.selectedRecipientName);
            this.$inertia.visit(`/messages/${recipientId}`, {preserveScroll: true});
        },
        restoreScrollPosition() {
            const savedScrollPosition = localStorage.getItem(`scrollPosition_${this.selectedRecipient}`);
            if (savedScrollPosition !== null && this.$refs.chatBox) {
                this.$refs.chatBox.scrollTop = parseInt(savedScrollPosition, 10);
            }
        },
        restoreSelectedRecipientName() {
            if (this.selectedRecipient) {
                const savedRecipientName = localStorage.getItem(`selectedRecipientName_${this.selectedRecipient}`);
                if (savedRecipientName !== null) {
                    this.selectedRecipientName = savedRecipientName;
                } else {
                    const recipientUser = this.users.find(user => user.id === this.selectedRecipient);
                    if (recipientUser) {
                        this.selectedRecipientName = recipientUser.name;
                    }
                }
            }
        },
        handleScroll() {
            if (this.$refs.chatBox) {
                this.isAtBottom = this.$refs.chatBox.scrollTop + this.$refs.chatBox.clientHeight >= this.$refs.chatBox.scrollHeight;
            }
        },
        async sendMessage() {
            try {
                const response = await axios.post('/messages', {
                    recipient_id: this.selectedRecipient,
                    message: this.newMessage,
                });
                this.newMessage = '';
                await this.selectRecipient(this.selectedRecipient);
                this.restoreScrollPosition();
            } catch (error) {
                console.error('Error sending message:', error);
            }
        },
    },
};
</script>

<style scoped>
.user-list {
    height: 400px;
    overflow-y: scroll;
}

.chat-box {
    height: 400px;
    overflow-y: scroll;
}

.message {
    margin-bottom: 10px;
}
</style>
