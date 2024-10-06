<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Административная панель</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'stats' }" @click="setActiveTab('users')">Общая статистика</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'users' }" @click="setActiveTab('users')">Пользователи</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'questions' }" @click="setActiveTab('questions')">Вопросы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'reports' }" @click="setActiveTab('users')">Жалобы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'sessions' }" @click="setActiveTab('users')">Активные сессии</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'admin_activity' }" @click="setActiveTab('users')">Последние действия в админке</a>
                </li>
            </ul>
            <div class="tab-content mt-4">
                <div v-if="success" class="alert alert-success">
                    {{ success }}
                </div>
                <div v-if="activeTab === 'users'" class="tab-pane active">
                    <UsersTab :users="users" @edit-user="editUser" @ban-user="banUser" @show-user-profile="showUserProfile" />
                </div>
                <div v-if="activeTab === 'questions'" class="tab-pane active">
                    <QuestionsTab :questions="questions" @process-question="processQuestion"/>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DefaultLayout from '../../Shared/DefaultLayout.vue';
import UsersTab from './usersTab.vue';
import {route} from "ziggy-js";
import axios from "axios";
import QuestionsTab from "./questionsTab.vue";

export default {
    components: {
        QuestionsTab,
        DefaultLayout,
        UsersTab,
    },
    props: {
        users: Array,
        questions: Array,
        success: String
    },
    methods: route(),
    setup(props) {
        const activeTab = ref('users');
        const success = ref(props.success);
        const setActiveTab = (tab) => {
            activeTab.value = tab;
        };

        const editUser = (userId) => {
            Inertia.get(route('admin.users.edit', { id: userId }));
        };

        const banUser = async (userId) => {
            if (confirm('Вы уверены в этом действии?')) {
                try {
                    await axios.post(`/admin/user/ban/${userId}`, {});
                    Inertia.visit(route('admin.index'), {
                        preserveState: true,
                        preserveScroll: true,
                        only: ['users', 'success']
                    });
                    success.value = "Статус пользователя успешно изменён";
                } catch (error) {
                    console.error('Ошибка при изменении статуса:', error);
                }
            }
        };
        const showUserProfile = (userId) => {
            Inertia.get(route('profile.show', { id: userId }));
        };


        const processQuestion = async (questionId) => {
            if (confirm('Вы уверены в этом действии?')) {
                try {
                    await axios.post(`/admin/question/${questionId}/process`, {});
                    Inertia.visit(route('admin.index'), {
                        preserveState: true,
                        preserveScroll: true,
                        only: ['questions', 'success']
                    });
                    setActiveTab('questions')
                    success.value = "Статус вопроса успешно изменён";
                } catch (error) {
                    console.error('Ошибка при изменении статуса:', error);
                }
            }
        };


        return { activeTab, setActiveTab, editUser, banUser, showUserProfile, success, processQuestion };
    }
}
</script>

<style scoped>
.container {
    max-width: 800px;
    padding-bottom: 50px;
}

.nav-tabs .nav-link {
    cursor: pointer;
}

.tab-content {
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-top: none;
}
</style>
