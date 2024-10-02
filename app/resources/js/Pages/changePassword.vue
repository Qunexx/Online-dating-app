<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Изменение пароля</h4>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <input type="hidden" :value="email" />
                                <div class="form-group mb-3">
                                    <label for="new_password">Новый пароль</label>
                                    <input v-model="new_password" type="password" class="form-control" id="new_password" placeholder="Введите новый пароль" required>
                                    <div v-if="errors.new_password" class="text-danger">{{ errors.new_password }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="new_password_confirmation">Подтверждение нового пароля</label>
                                    <input v-model="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation" placeholder="Подтвердите новый пароль" required>
                                    <div v-if="passwordMismatch" class="text-danger">Пароли не совпадают.</div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">Изменить пароль</button>
                                </div>
                            </form>
                            <div v-if="successMessage" class="alert alert-success mt-3">{{ successMessage }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '../Shared/DefaultLayout.vue';
import axios from 'axios';
import {route} from "ziggy-js";

export default {
    components: {
        DefaultLayout
    },
    props: {
        token: String,
        email: String,
    },
    data() {
        return {
            new_password: '',
            new_password_confirmation: '',
            passwordMismatch: false,
            errors: {},
            successMessage: '',
        };
    },
    methods: {
        submit() {
            this.errors = {};
            this.successMessage = '';
            if (!this.new_password) {
                this.errors.new_password = ['Новый пароль обязателен.'];
            } else if (this.new_password.length < 8) {
                this.errors.new_password = ['Новый пароль должен содержать минимум 8 символов.'];
            }
            if (this.new_password !== this.new_password_confirmation) {
                this.passwordMismatch = true;
            } else {
                this.passwordMismatch = false;
            }

            if (Object.keys(this.errors).length > 0 || this.passwordMismatch) {
                return;
            }

            axios.post('/reset-password', {
                token: this.token,
                email: this.email,
                password: this.new_password,
                password_confirmation: this.new_password_confirmation,
            })
                .then(response => {
                    if (response.data.success) {
                        this.successMessage = response.data.message;
                        setTimeout(() => {
                           this.$inertia.visit(route('login.index'));
                        }, 1000);
                    } else {
                        this.errors = response.data.errors;
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },
    }
}
</script>
