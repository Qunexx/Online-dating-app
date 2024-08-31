<template>
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p class="mb-1">&copy; 2024 Сайт Знакомств. Все права защищены.</p>
            <div class="d-flex justify-content-center mb-2">
                <Link class="text-white mx-2" href="#">Политика конфиденциальности</Link>
                <span class="text-white">|</span>
                <Link class="text-white mx-2" href="#">Условия использования</Link>
            </div>
            <div class="d-flex justify-content-center">
                <Link class="nav-link text-white mx-2" :href="route('home')">Главная</Link>
                <Link class="nav-link text-white mx-2" :href="route('about')">О нас</Link>
                <Link class="nav-link text-white mx-2" :href="route('blog')">Блог</Link>
                <Link class="nav-link text-white mx-2" :href="route('contact')">Контакты</Link>
            </div>
        </div>
    </footer>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { route } from 'ziggy-js'; // Импортируйте функцию route

export default {
    name: 'FooterComponent',
    methods: {route},
    components: {
        Link
    }
}
</script>
