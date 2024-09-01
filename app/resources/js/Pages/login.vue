<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Вход</h4>
                        </div>
                        <div class="card-body">
                            <div v-if="success" class="alert alert-success">
                                {{ success }}
                            </div>
                            <div v-if="error" class="alert alert-danger">
                                {{ error }}
                            </div>
                            <form @submit.prevent="submit">
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input v-model="email" type="email" class="form-control" id="email" placeholder="Введите email" required>
                                    <div v-if="$page.props.errors.email" class="text-danger">{{ $page.props.errors.email }}</div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Пароль</label>
                                    <input v-model="password" type="password" class="form-control" id="password" placeholder="Введите пароль" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mb-3">Войти</button>
                            </form>
                            <div class="mt-3 text-center">
                                <Link :href="route('register.index')">Нет аккаунта? Зарегистрируйтесь</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '../Shared/DefaultLayout.vue';
import { Link } from "@inertiajs/inertia-vue3";
import { route } from "ziggy-js";

export default {
    components: {
        Link,
        DefaultLayout
    },
    props: {
        errors: Object,
        error: String,
        success: String,
    },
    data() {
        return {
            email: '',
            password: '',
        };
    },
    methods: {
        route,
        submit() {
            this.$inertia.post('/login', { email: this.email, password: this.password });
        },
    },
}
</script>
