<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Редактирование пользователя</h1>
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="updateUser">
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="name" v-model="form.name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" v-model="form.email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Роль</label>
                            <select class="form-select" id="role" v-model="form.role" required>
                                <option value="user">Пользователь</option>
                                <option value="admin">Администратор</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <button @click="goBack" class="btn btn-primary ms-2">Назад</button>
                    </form>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import DefaultLayout from '../../Shared/DefaultLayout.vue';
import { route } from "ziggy-js";

export default {
    components: {
        DefaultLayout
    },
    props: {
        user: Object
    },
    data() {
        return {
            form: {
                name: this.user.name,
                email: this.user.email,
                role: this.user.role,
            }
        };
    },
    methods: {
        updateUser() {
            Inertia.post(route('admin.users.update', {id: this.user.id}), this.form);
        },
        goBack() {
            Inertia.visit(history.back());
        },
    }
}
</script>

<style scoped>
.container {
    max-width: 600px;
    padding-bottom: 50px;
}

.card {
    border: 1px solid #dee2e6;
}

.form-label {
    font-weight: bold;
}
</style>
