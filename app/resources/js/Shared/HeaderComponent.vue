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
                            <li v-for="notification in notifications" :key="notification.id">
                                <Link class="dropdown-item" :href="route('notifications.show', notification.id)">
                                    {{ notification.message }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <Link :href="route('messages.index')" class="btn btn-secondary me-3">
                        Сообщения <span class="badge bg-danger">{{ unreadMessagesCount }}</span>
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
import {Link} from '@inertiajs/inertia-vue3';
import {route} from 'ziggy-js';

export default {
    name: 'HeaderComponent',
    components: {
        Link
    },
    data() {
        return {
            unreadNotificationsCount: 0,
            unreadMessagesCount: 0,
            notifications: [],
        };
    },
    mounted() {
        this.fetchNotifications();
        this.fetchMessagesCount();
    },
    methods: {
        route,
        async fetchNotifications() {

        },
        async fetchMessagesCount() {

        }
    }
}
</script>
