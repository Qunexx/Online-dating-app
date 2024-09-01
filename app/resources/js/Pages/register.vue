<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Регистрация</h4>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <div class="form-group mb-3">
                                    <label for="name">Имя</label>
                                    <input v-model="name" type="text" class="form-control" id="name" placeholder="Введите имя" required>
                                    <div v-if="errors.name" class="text-danger">{{ errors.name }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input v-model="email" type="email" class="form-control" id="email" placeholder="Введите email" required>
                                    <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Пароль</label>
                                    <input v-model="password" type="password" class="form-control" id="password" placeholder="Введите пароль" required>
                                    <div v-if="errors.password" class="text-danger">{{ errors.password }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password_confirmation">Подтверждение пароля</label>
                                    <input v-model="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Подтвердите пароль" required>
                                    <div v-if="passwordMismatch" class="text-danger">Пароли не совпадают.</div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '../Shared/DefaultLayout.vue';

export default {
        components: {
            DefaultLayout
        },
        data() {
            return {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                passwordMismatch: false,
                errors: {},
            };
        },
        methods: {
            submit() {
                this.errors = {};
                if (!this.name) {
                    this.errors.name = ['Имя обязательно.'];
                }
                if (!this.email) {
                    this.errors.email = ['Email обязателен.'];
                } else if (!this.validateEmail(this.email)) {
                    this.errors.email = ['Введите корректный email.'];
                }
                if (!this.password) {
                    this.errors.password = ['Пароль обязателен.'];
                } else if (this.password.length < 8) {
                    this.errors.password = ['Пароль должен содержать минимум 8 символов.'];
                }
                if (this.password !== this.password_confirmation) {
                    this.passwordMismatch = true;
                } else {
                    this.passwordMismatch = false;
                }

                if (Object.keys(this.errors).length > 0 || this.passwordMismatch) {
                    return;
                }

                this.$inertia.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                }, {
                    onError: (errors) => {
                        this.errors = errors;
                    },
                    onSuccess: () => {
                    }
                });
            },
            validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            },
        }
    }
</script>
