<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Профиль пользователя</h1>
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Имя: {{ $page.props.profiles_user.name }}</h2>
                    <p class="card-text"><strong>Биография:</strong> {{ $page.props.profile.bio}}</p>
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
                            Фотографии отсутствуют.
                        </div>
                    </div>

                    <div class="mt-4">
                        <button @click="likeProfile" class="btn btn-primary me-2">Лайк</button>
                        <button @click="goToChat" class="btn btn-secondary">Написать сообщение</button>
                    </div>
                    <div v-if="successMessage" class="alert alert-success mt-3">
                        {{ successMessage }}
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import {computed, ref} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DefaultLayout from '../../Shared/DefaultLayout.vue';
import { route } from "ziggy-js";
import axios from "axios";

export default {
    components: {
        DefaultLayout
    },
    props: {
        profile: Object,
        profiles_user: Object,
        success: String,
    },
    setup(props) {
        const successMessage = ref('');
        function formatBirthdate() {
            const date = new Date(props.profile.birthdate);
            return date.toLocaleDateString('ru-RU', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        }

        const formattedBirthdate = computed(formatBirthdate);
        async function likeProfile() {
            try {
                await axios.post(`/profiles/${props.profile.id}/like`, {});
                successMessage.value = 'Лайк успешно отправлен! Начните общение первым! (Нажмите написать сообщение)';
            } catch (error) {
                console.error('Ошибка при отправке лайка:', error);
            }
        }

        function goToChat() {
            Inertia.get(route('messages.fetchMessages', props.profiles_user.id));
        }

        return {
            formattedBirthdate,
            likeProfile,
            goToChat,
            successMessage
        };
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
