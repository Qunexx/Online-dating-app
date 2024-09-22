<template>
    <header class="bg-primary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <Link href="/" class="text-white text-decoration-none">
                <h1 class="text-center mb-0">Сайт Знакомств</h1>
            </Link>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <Link :href="route('home')" class="nav-link text-white">Главная</Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('search-pair')" class="nav-link text-white">Поиск пары</Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('about')" class="nav-link text-white">О нас</Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('blog')" class="nav-link text-white">Блог</Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('contact')" class="nav-link text-white">Контакты</Link>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="d-flex align-items-center">
                <template v-if="$page.props.user">
                    <div class="dropdown me-3">
                        <button
                            class="btn btn-secondary dropdown-toggle"
                            type="button"
                            id="userDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span class="ms-2">{{ $page.props.user.name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><Link class="dropdown-item" :href="route('profile.index')">Профиль</Link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><Link class="dropdown-item" :href="route('logout.post')">Выход</Link></li>
                        </ul>
                    </div>

                    <div class="dropdown me-3">
                        <button
                            class="btn btn-secondary dropdown-toggle"
                            type="button"
                            id="notificationsDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Уведомления <span class="badge bg-danger">{{ unreadNotificationsCount }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                            <li v-if="notifications.length === 0" class="dropdown-item">Нет уведомлений</li>
                            <li v-for="notification in notifications" :key="notification.id" class="notification-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="notification-message">{{ notification.message }}</span>
                                    <button @click.stop.prevent="hideNotification(notification.id)" class="btn btn-link text-muted">
                                        Пометить прочитанным
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <Link :href="route('messages.index')" class="btn btn-secondary me-3">
                        Сообщения
                    </Link>
                </template>
                <template v-else>
                    <Link :href="route('login.index')" class="text-white me-3">Войти</Link>
                    <Link :href="route('register.index')" class="text-white me-3">Зарегистрироваться</Link>
                </template>
            </div>
        </div>
    </header>
</template>

<script>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js';
import Pusher from "pusher-js";
import Echo from "laravel-echo";
import axios from 'axios';

export default {
    name: 'HeaderComponent',
    components: {
        Link
    },
    setup() {
        const notifications = ref([]);
        const authorizedUserId = ref(null);
        const unreadNotificationsCount = ref(0);

        const initializePusher = () => {
            window.Pusher = Pusher;
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                forceTLS: true,
            });
            Pusher.logToConsole = true;

            const userId = authorizedUserId.value;

            window.Echo.private(`notifications.${userId}`)
                .listen(".new-notification", (e) => {
                    notifications.value.push({
                        id: e.id,
                        message: e.message,
                    });
                    unreadNotificationsCount.value++;
                });
        };

        const fetchNotifications = async () => {
            try {
                const response = await axios.get('/notifications');
                notifications.value = response.data.notifications;
                authorizedUserId.value = response.data.user.id;
                unreadNotificationsCount.value = notifications.value.length;
            } catch (error) {
                console.error('Error', error);
            }
        };

        const hideNotification = async (notificationId) => {
            try {
                await axios.post(`/hide-notification/${notificationId}`);
                notifications.value = notifications.value.filter(notification => notification.id !== notificationId);
                unreadNotificationsCount.value = notifications.value.length;
            } catch (error) {
                console.error('Error', error);
            }
        };

        return {
            notifications,
            authorizedUserId,
            unreadNotificationsCount,
            initializePusher,
            fetchNotifications,
            hideNotification,
            route,
        };
    },
    async mounted() {
        await this.fetchNotifications();
        this.initializePusher();
    },
}
</script>
