<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Профиль пользователя</h1>
            <p>Ваш профиль будет виден другим пользователям, пожалуйста, учтите это при его заполнении.</p>
            <div class="card">
                <div v-if="success" class="alert alert-success" role="alert">
                    {{ success }}
                </div>
                <div class="card-body">
                    <h2 class="card-title">Имя: {{ $page.props.user.name }}</h2>
                    <p class="card-text"><strong>Биография:</strong> {{ $page.props.profile.bio }}</p>
                    <p class="card-text"><strong>Пол:</strong> {{ $page.props.profile.gender }}</p>
                    <p class="card-text"><strong>Дата рождения:</strong> {{ formattedBirthdate }}</p>
                    <p class="card-text"><strong>Местоположение:</strong> {{ $page.props.profile.location }}</p>
                    <p class="card-text"><strong>Интересы:</strong> {{ $page.props.profile.interests }}</p>

                    <div class="mt-4">
                        <h3>Фотографии профиля</h3>
                        <div v-if="$page.props.profile.photos.length > 0" class="row mb-3">
                            <div class="row">
                                <div v-for="photo in $page.props.profile.photos" :key="photo.id" class="col-6 col-md-4 mb-3">
                                    <div class="card">
                                        <img :src="`/storage/${photo.photo_path}`" class="card-img-top" alt="Фото профиля" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="alert alert-warning">
                            Пожалуйста, добавьте фотографии профиля. Так вы быстрее найдёте свой мэтч.
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3>Статистика профиля за последнюю неделю:</h3>
                        <p><strong>Лайков:</strong> {{ $page.props.profile.likes }}</p>
                        <p><strong>Просмотров:</strong> {{ $page.props.profile.views }}</p>
                    </div>

                    <div class="text-center mt-4">
                        <button @click="editProfile" class="btn btn-primary">Редактировать профиль</button>
                    </div>

                    <div class="text-center mt-4">
                        <button @click="goToSearch" class="btn btn-secondary me-2">Поиск пары</button>
                        <button @click="goToMessages" class="btn btn-info">Сообщения</button>
                    </div>


                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import { computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DefaultLayout from '../../Shared/DefaultLayout.vue';
import { route } from "ziggy-js";

export default {
    components: {
        DefaultLayout
    },
    props: {
        profile: Object,
        success: String
    },
    setup(props) {
        const editProfile = () => {
            Inertia.get(route('profile.edit'));
        };

        const formattedBirthdate = computed(() => {
            const date = new Date(props.profile.birthdate);
            return date.toLocaleDateString('ru-RU', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        });

        return { editProfile, formattedBirthdate };
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

.card-title {
    font-size: 1.5rem;
}

.card-text {
    font-size: 1rem;
}

.card-img-top {
    height: 150px;
    object-fit: cover;
}
</style>
