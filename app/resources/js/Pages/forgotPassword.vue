<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Забыли пароль?</h4>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <input type="hidden" v-model="email" />
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input v-model="email" type="email" class="form-control" id="email" placeholder="Введите email" required>
                                    <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">Отправить ссылку для сброса пароля</button>
                                </div>
                                <div v-if="errors.send" class="alert alert-success">{{ errors.send }}</div>
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
import {route} from "ziggy-js";

export default {
    components: {
        DefaultLayout
    },
    data() {
        return {
            email: '',
            errors: {},
        };
    },
    methods: {
        route,
        submit() {
            this.errors = {};
            if (!this.email) {
                this.errors.email = ['Email обязателен.'];
            } else if (!this.validateEmail(this.email)) {
                this.errors.email = ['Введите корректный email.'];
            }

            if (Object.keys(this.errors).length > 0) {
                return;
            }

            this.$inertia.post('/restore-password', {
                email: this.email,
            }, {
                onError: (errors) => {
                    this.errors = errors;
                },
                onSuccess: (errors) => {
                    this.errors = errors;
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
