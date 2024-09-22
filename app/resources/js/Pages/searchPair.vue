<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center">Поиск пары</h1>
            <div class="card mt-5">
                <div class="card-header">
                    <h2 class="card-title">{{ $page.props.profiles_user.name }}</h2>
                </div>
                <div class="card-body">
                    <div v-if="$page.props.profile.photos.length > 0" class="row mb-3">
                        <div class="row">
                            <div v-for="photo in $page.props.profile.photos" :key="photo.id" class="col-6 col-md-4 mb-3">
                                <div class="card">
                                    <img :src="`/storage/${photo.photo_path}`" class="card-img-top" alt="Фото профиля" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="card-text"><strong>Биография:</strong> {{ $page.props.profile.bio }}</p>
                    <p class="card-text"><strong>Пол:</strong> {{ $page.props.profile.gender }}</p>
                    <p class="card-text"><strong>Дата рождения:</strong> {{ formattedBirthdate }}</p>
                    <p class="card-text"><strong>Местоположение:</strong> {{ $page.props.profile.location }}</p>
                    <p class="card-text"><strong>Интересы:</strong> {{ $page.props.profile.interests }}</p>

                    <div class="mt-4">
                        <button @click="likeProfile" class="btn btn-primary me-2">Лайк</button>
                        <button @click="dislikeProfile" class="btn btn-secondary">Дизлайк</button>
                    </div>

                    <div v-if="showMessageForm" class="mt-4">
                        <button @click="sendMessage" class="btn btn-primary mt-2">Отправить сообщение</button>
                        <button @click="continueSearch" class="btn btn-secondary mt-2 ms-2">Продолжить поиск</button>
                    </div>
                </div>
            </div>

            <div v-if="successMessage" class="alert alert-success mt-3">
                {{ successMessage }}
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DefaultLayout from '../Shared/DefaultLayout.vue';
import { route } from "ziggy-js";

export default {
    components: {
        DefaultLayout
    },
    props: {
        profile: Object,
        profiles_user: Object
    },
    setup(props) {
        const successMessage = ref('');
        const showMessageForm = ref(false);
        const message = ref('');

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
                await Inertia.post(route('profiles.like', props.profile.id), {}, { preserveState: true });
                successMessage.value = 'Лайк успешно отправлен! Начните общение первым! (Нажмите отправить сообщение) Либо если вы не желаете писать в первую очередь, нажимите продолжить поиск';
                showMessageForm.value = true;
            } catch (error) {
                console.error('Ошибка при отправке лайка:', error);
            }
        }

        async function dislikeProfile() {
            try {
                successMessage.value = 'Дизлайк успешно отправлен!';
                setTimeout(() => {
                    Inertia.post(route('profiles.dislike', props.profile.id));
                    successMessage.value = '';
                }, 500);
            } catch (error) {
                console.error('Ошибка при отправке дизлайка:', error);
            }
        }
        async function sendMessage() {
            try {
                Inertia.visit(route('messages.fetchMessages', props.profiles_user.id));
                showMessageForm.value = false;
            } catch (error) {
                console.error('Ошибка при отправке сообщения:', error);
            }
        }
        function continueSearch() {
            showMessageForm.value = false;
            Inertia.visit(route('search-pair'));
        }

        return {
            formattedBirthdate,
            likeProfile,
            dislikeProfile,
            successMessage,
            showMessageForm,
            message,
            sendMessage,
            continueSearch,
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
