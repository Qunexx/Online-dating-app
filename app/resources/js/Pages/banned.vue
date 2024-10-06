<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-5">Страница блокировки</h1>
            <div class="row">
                <div class="col-md-6">
                    <div v-if="successMessage" class="alert alert-success">
                        {{ successMessage }}
                    </div>
                    <div v-if="errorMessage" class="alert alert-success">
                        {{ errorMessage }}
                    </div>
                    <h2 class="mb-4">Свяжитесь с нами, ваш доступ к функциям сайта, требующим авторизацию, ограничен</h2>
                    <p>К сожалению, ваш аккаунт был заблокирован по какой-то причине. Для уточнения причины блокировки и возможности её обжалования, пожалуйста, заполните форму ниже. Мы свяжемся с вами по указанному email для дальнейших инструкций.</p>
                    <h2 class="mb-4">Форма для вопросов и предложений</h2>
                    <form @submit.prevent="submitForm">
                        <div class="form-group">
                            <label for="name">Ваше имя на сайте</label>
                            <input type="text" class="form-control" id="name" v-model="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Ваш email, привязанный к аккаунту</label>
                            <input type="email" class="form-control" id="email" v-model="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Ваше сообщение или вопрос</label>
                            <textarea class="form-control" id="message" rows="5" v-model="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mb-5">Отправить</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <img :src="imagePath" alt="Контакты" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '../Shared/DefaultLayout.vue';
import axios from 'axios';

export default {
    components: {
        DefaultLayout
    },
    props: {},
    data() {
        return {
            name: '',
            email: '',
            message: '',
            successMessage: '',
            errorMessage: '',
        };
    },
    computed: {
        imagePath() {
            return this.asset('storage/contact/banner.webp');
        },
    },
    methods: {
        async submitForm() {
            try {
                const response = await axios.post('/contact', {
                        name: this.name,
                        email: this.email,
                        message: this.message
                    }
                );
                this.successMessage = response.data.success;
                this.name = '';
                this.email = '';
                this.message = '';
                this.errorMessage = null;
            } catch (error) {
                console.error('Ошибка отправки сообщения:', error);
                this.errorMessage = error.response.data.error;
                this.successMessage = null;
            }
        },
        asset(path) {
            return window.location.origin + '/' + path;
        }
    },
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

.img-fluid {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-control {
    font-size: 1rem;
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
</style>
