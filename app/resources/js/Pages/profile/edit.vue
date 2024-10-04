<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Редактировать профиль</h1>
            <form @submit.prevent="submitProfile" >
                <div class="mb-3">
                    <label for="bio" class="form-label">Биография</label>
                    <textarea v-model="form.bio" class="form-control" id="bio"></textarea>
                    <button type="button" @click="toggleEmojiPicker" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-emoji-smile" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"/>
                        </svg>
                    </button>
                    <div v-if="showEmojiPicker" class="emoji-picker-container">
                        <emoji-picker @emoji-click="onEmojiClick"></emoji-picker>
                    </div>
                    <span v-if="errors.bio" class="text-danger">{{ errors.bio[0] }}</span>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Пол</label>
                    <select v-model="form.gender" class="form-select" id="gender" required>
                        <option value="">Выберите пол</option>
                        <option value="Мужчина">Мужчина</option>
                        <option value="Женщина">Женщина</option>
                        <option value="Другой">Другой</option>
                    </select>
                    <span v-if="errors.gender" class="text-danger">{{ errors.gender[0] }}</span>
                </div>

                <div class="mb-3">
                    <label for="birthdate" class="form-label">Дата рождения</label>
                    <input v-model="form.birthdate" type="date" class="form-control" id="birthdate" />
                    <span v-if="errors.birthdate" class="text-danger">{{ errors.birthdate[0] }}</span>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Местоположение</label>
                    <input v-model="form.location" type="text" class="form-control" id="location" />
                    <span v-if="errors.location" class="text-danger">{{ errors.location[0] }}</span>
                </div>

                <div class="mb-3">
                    <label for="interests" class="form-label">Интересы</label>
                    <input v-model="form.interests" type="text" class="form-control" id="interests" />
                    <span v-if="errors.interests" class="text-danger">{{ errors.interests[0] }}</span>
                </div>

                <div v-if="$page.props.profile.photos.length > 0" class="row mb-3">
                <h3>Уже загруженные фотографии</h3>
                <a>Отметьте галочками свои фотографии, если хотите их удалить</a>
                <div class="row mb-3">
                    <div v-for="photo in $page.props.profile.photos" :key="photo.id" class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="card">
                            <img :src="`/storage/${photo.photo_path}`" class="card-img-top" alt="Фото профиля" />
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" :value="photo.id" v-model="form.delete_photos" />
                                    <label class="form-check-label">Удалить</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <div class="mb-3">
                    <label for="photos" class="form-label">Загрузить фотографии профиля (до 5)</label>
                    <input type="file" @change="handleFileUpload" class="form-control" id="photos" accept="image/*" multiple />
                    <span v-if="errors.photos" class="text-danger">{{ errors.photos[0] }}</span>
                </div>
                <div v-if="selectedFiles.length > 0" class="row mb-3">
                <h3>Новые фотографии</h3>
                <div class="row mb-3">
                    <div v-for="(photo, index) in selectedFiles" :key="index" class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="card">
                            <img :src="photo.url" class="card-img-top" alt="Фото профиля" />
                            <div class="card-body">
                                <button @click.prevent="removeFile(index)" class="btn btn-danger btn-sm">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div v-if="$page.props.profile.photos.length === 0 && selectedFiles.length === 0" class="alert alert-warning">
                    Добавьте фотографии профиля.
                    Чем больше, тем лучше, так вы быстрее найдёте свой мэтч!
                </div>

                <div class="text-center mt-4 mb-5 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary mb-5">Сохранить изменения</button>
                    <div class="ms-3">
                        <Link :href="route('profile.index')"><div class="btn btn-secondary">Назад</div></Link>
                    </div>
                </div>
            </form>

        </div>
    </DefaultLayout>
</template>

<script>
import { ref, computed} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DefaultLayout from '../../Shared/DefaultLayout.vue';
import { route } from "ziggy-js";
import { Link } from '@inertiajs/inertia-vue3';
import 'emoji-picker-element';

export default {
    methods: {route},
    components: {
        DefaultLayout,
        Link
    },
    props: {
        profile: Object,
        success: String
    },
    setup(props) {
        const showEmojiPicker = ref(false);
        const selectedFiles = ref([]);
        const errors = ref({});

        const form = ref({
            bio: props.profile.bio,
            gender: props.profile.gender,
            birthdate: props.profile.birthdate,
            location: props.profile.location,
            interests: props.profile.interests,
            delete_photos: [],
        });

        const handleFileUpload = (event) => {
            const files = Array.from(event.target.files);
            if (selectedFiles.value.length + files.length > 5) {
                alert('Вы можете загрузить не более 5 фотографий.');
                return;
            }
            selectedFiles.value = selectedFiles.value.concat(files.map(file => ({
                file,
                url: URL.createObjectURL(file)
            })));
        };

        const removeFile = (index) => {
            selectedFiles.value.splice(index, 1);
        };

        const submitProfile = () => {
            const formData = new FormData();
            formData.append('bio', form.value.bio);
            formData.append('gender', form.value.gender);
            formData.append('birthdate', form.value.birthdate);
            formData.append('location', form.value.location);
            formData.append('interests', form.value.interests);

            if (form.value.delete_photos.length > 0) {
                formData.append('delete_photos', JSON.stringify(form.value.delete_photos));
            }

            selectedFiles.value.forEach(({file}) => {
                formData.append('photos[]', file);
            });

            Inertia.post(route('profile.update'), formData, {
                onSuccess: () => {
                },
                onError: (errorResponse) => {
                    errors.value = errorResponse;
                }
            });
        };

        const formattedBirthdate = computed(() => {
            const date = new Date(props.profile.birthdate);
            return date.toLocaleDateString('ru-RU', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        });
        const onEmojiClick = (event) => {
            form.value.bio += event.detail.unicode;
        };
        const toggleEmojiPicker= () => {
            showEmojiPicker.value = !showEmojiPicker.value;
        };

        return { form, submitProfile, handleFileUpload, formattedBirthdate, errors, selectedFiles, removeFile, onEmojiClick, toggleEmojiPicker, showEmojiPicker };
    }
}
</script>

<style scoped>
.container {
    max-width: 600px;
}

.card {
    border: 1px solid #dee2e6;
}

.card-img-top {
    height: 150px;
    object-fit: cover;
}

.emoji-picker-container {
    position: absolute;
    z-index: 1000;
}
</style>
