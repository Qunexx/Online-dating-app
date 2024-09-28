<template>
    <DefaultLayout>
        <div class="container mt-5 mb-3">
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
                                <Link :href="route('profile.show',selectedRecipient)" class="btn btn-link">Перейти в профиль</Link>
                            </div>
                        </div>
                        <div v-else class="card-header">
                            <div class="text-center">
                                <strong>Выберите пользователя для начала диалога</strong>
                            </div>
                        </div>

                        <div class="card-body" ref="chatBox" style="height: 300px; overflow-y: scroll;" @scroll="handleScroll">
                            <div v-for="message in messages" :key="message.id" class="message">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ message.sender.name || 'Загрузка...' }}:</strong> {{ message.content }}
                                    </div>
                                    <div class="message-date">
                                        {{ formatDate(message.created_at) || 'Загрузка...' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form v-if="selectedRecipient" @submit.prevent="sendMessage" class="mt-3">
                        <div class="input-group">
                            <button type="button" @click="toggleEmojiPicker" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-emoji-smile" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"/>
                                </svg>
                            </button>
                            <input v-model="newMessage" placeholder="Напишите сообщение" class="form-control" />
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </div>
                        <div v-if="showEmojiPicker" class="emoji-picker-container">
                            <emoji-picker @emoji-click="onEmojiClick"></emoji-picker>
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
import {route} from "ziggy-js";
import 'emoji-picker-element';


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
            showEmojiPicker: false,
        };
    },
    mounted() {
        this.initializePusher();
        this.restoreScrollPosition();
        this.restoreSelectedRecipientName();
    },
    methods: {
        route,
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
        onEmojiClick(event) {
            this.newMessage += event.detail.unicode;
        },
        toggleEmojiPicker() {
            this.showEmojiPicker = !this.showEmojiPicker;
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
        formatDate(date) {
            if (!date) return '';
            const dateObj = new Date(date);
            if (isNaN(dateObj.getTime())) return '';
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            const dateOptions = { day: '2-digit', month: '2-digit', year: '2-digit' };
            const timeString = dateObj.toLocaleTimeString('ru-RU', timeOptions);
            const dateString = dateObj.toLocaleDateString('ru-RU', dateOptions);
            return `${timeString} ${dateString}`;
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

.emoji-picker-container {
    position: absolute;
    left: 40%;
    transform: translateX(-50%);
    z-index: 1000;
}
</style>
