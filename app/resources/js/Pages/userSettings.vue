<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Настройки аккаунта</h4>
                            <p class="mb-1 mt-3">Здесь вы можете изменить пароль своего аккаунта</p>
                        </div>

                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <div class="form-group mb-3">
                                    <label for="new_password">Текущий пароль</label>
                                    <input v-model="current_password" type="password" class="form-control" id="new_password" placeholder="Введите текущий пароль" >
                                    <div v-if="errors.current_password" class="text-danger">{{ errors.current_password }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="new_password">Новый пароль</label>
                                    <input v-model="new_password" type="password" class="form-control" id="new_password" placeholder="Введите новый пароль" >
                                    <div v-if="errors.new_password" class="text-danger">{{ errors.new_password }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="new_password_confirmation">Подтверждение нового пароля</label>
                                    <input v-model="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation" placeholder="Подтвердите новый пароль" >
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
        errors: Object,
    },
    data() {
        return {
            current_password: '',
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
            if (!this.current_password) {
                this.errors.current_password = ['Текущий пароль обязателен.'];
            }
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

            axios.post('/settings/change-password', {
                current_password: this.current_password,
                new_password: this.new_password,
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
