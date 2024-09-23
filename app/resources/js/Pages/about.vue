<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-5">О нас</h1>
            <div class="row">
                <div class="col-md-6">
                    <h2 class="mb-4">Наша миссия</h2>
                    <p>Мы стремимся создать платформу, которая поможет людям найти свою идеальную пару. Наш сайт знакомств объединяет тысячи пользователей, которые ищут любовь, дружбу и новые знакомства.</p>
                    <p>Мы верим, что каждый человек заслуживает найти свою вторую половинку, и наша миссия — сделать этот процесс максимально простым и эффективным.</p>
                </div>
                <div class="col-md-6">
                    <img :src="imagePath" alt="О нас" class="img-fluid rounded shadow">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <h2 class="mb-4">Наши ценности</h2>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Безопасность и конфиденциальность</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Простота и удобство использования</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Поддержка пользователей 24/7</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Уникальные алгоритмы подбора пар</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Положительные отзывы от пользователей</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Наша команда</h2>
                    <p>Мы — команда профессионалов, которые любят свою работу и стремятся сделать мир лучше. Наши специалисты работают над созданием инновационных решений, которые помогают людям найти свою любовь.</p>
                    <p>Мы постоянно улучшаем наш сайт, чтобы обеспечить вам лучший опыт знакомств.</p>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <h2 class="mb-4">Свяжитесь с нами</h2>
                    <p>Если у вас есть вопросы или предложения, свяжитесь с нами по адресу <a href="mailto:СайтЗнакомств@сайтзнакомств.ру">СайтЗнакомств@сайтзнакомств.ру</a>.</p>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '../Shared/DefaultLayout.vue';
import {route} from "ziggy-js";

export default {
    components: {
        DefaultLayout
    },
    props: {},
    computed: {
        imagePath() {
            return this.asset('storage/about/banner.webp');
        },
    },
    methods: {
        asset(path) {
            return window.location.origin + '/' + path;
        }
}
}
</script>

<style scoped>
h1 {
    font-size: 2.5rem;
    color: #333;
    font-weight: bold;
}

h2 {
    font-size: 2rem;
    color: #333;
    font-weight: bold;
}

p {
    font-size: 1.2rem;
    color: #555;
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
