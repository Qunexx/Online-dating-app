<template>
    <DefaultLayout>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Административная панель</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'users' }" @click="setActiveTab('users')">Пользователи</a>
                </li>
            </ul>
            <div class="tab-content mt-4">
                <div v-if="activeTab === 'users'" class="tab-pane active">
                    <UsersTab :users="users" />
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


export default {
    components: {
        DefaultLayout,
        UsersTab,
    },
    props: {
        users: Array,
    },
    setup() {
        const activeTab = ref('users');

        const setActiveTab = (tab) => {
            activeTab.value = tab;
        };

        return { activeTab, setActiveTab};
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
