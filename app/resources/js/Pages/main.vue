<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div v-if="success" class="alert alert-success">
                {{ success }}
            </div>
            <div v-if="error" class="alert alert-danger">
                {{ error }}
            </div>
            <div class="jumbotron text-center">
                <h1 class="display-4">Добро пожаловать на сайт знакомств!</h1>
                <p class="lead">Найдите свою идеальную пару прямо сейчас!</p>
                <hr class="my-4">
                <p>Наш сайт поможет вам найти человека, с которым вы будете счастливы вместе.</p>
                <Link :href="redirectLink" class="btn btn-primary btn-lg">Начать поиск пары</Link>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2 class="mb-4">Почему выбирают нас?</h2>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Безопасность и конфиденциальность</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Простой и удобный интерфейс</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Поддержка 24/7</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Уникальные алгоритмы подбора пар</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Большой выбор анкет</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Регулярные обновления и улучшения</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Положительные отзывы от пользователей</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Бесплатный доступ к базовым функциям</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <Link :href="redirectLink">
                    <img :src="imagePath" alt="Знакомства" class="img-fluid rounded shadow">
                    </Link>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '../Shared/DefaultLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { route } from "ziggy-js";
export default {
    components: {
        DefaultLayout
    },
    props: {
        user: Object,
        errors: Object,
        error: String,
        success: String,
    },
    computed: {
        imagePath() {
            return this.asset('storage/main/banner.webp');
        },
        redirectLink() {
            return this.user ? route('search-pair') : route('register.index');
        }
    },
    methods: {
        route,
        asset(path) {
            return window.location.origin + '/' + path;
        }
    }
}
</script>

<style scoped>
.jumbotron {
    background-color: #f8f9fa;
    padding: 2rem 1rem;
    margin-bottom: 2rem;
    border-radius: .3rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

h2 {
    font-size: 2rem;
    color: #333;
    font-weight: bold;
}

ul {
    list-style: none;
    padding-left: 0;
}

ul li {
    font-size: 1.2rem;
    color: #555;
}

ul li i {
    color: #28a745;
}

.img-fluid {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
