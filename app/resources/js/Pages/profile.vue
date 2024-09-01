<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Профиль</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
<!--                                <img v-if="user.avatar" :src="user.avatar" alt="Аватар" class="rounded-circle" width="100" height="100">-->
                                <h5 class="mt-2">Имя: {{ user.name }}</h5>
                                <h5 class="mt-2">Email: {{ user.email }}</h5>
                                    <h5 class="mt-2">Дата регистрации: {{ formatDate(user.created_at) }}</h5>
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

export default {
    components: {
        DefaultLayout
    },
    props: {
        user: Object,
    },
    methods: {
        formatDate(dateString) {
            const options = {year: 'numeric', month: 'long', day: 'numeric'};
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
    },
}
</script>
