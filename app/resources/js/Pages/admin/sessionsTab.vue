<template>
    <div>
        <h2>Активные сессии</h2>
        <div class="mb-3">
            <input type="text" v-model="searchQuery" @input="searchUsers" placeholder="Поиск по имени или email"
                   class="form-control">
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th @click="sortBy('user_id')">User_id</th>
                    <th @click="sortBy('name')">Имя</th>
                    <th @click="sortBy('email')">Email</th>
                    <th @click="sortBy('user_agent')">Юзер-агент</th>
                    <th @click="sortBy('ip_address')">Айпи адрес</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="session in filteredSessions" :key="session.id">
                    <td>{{ session.user_id }}</td>
                    <td>{{ session.name }}</td>
                    <td>{{ session.email }}</td>
                    <td>{{ session.user_agent }}</td>
                    <td>{{ session.ip_address }}</td>
                    <td>
                        <Link :href="route('profile.show',session.user_id)" class="btn btn-xs btn-primary me-2 mb-2">Перейти в профиль </Link>
                        <button @click="closeSession(session.id)" class="btn btn-xs btn-danger me-2 mb-2">Закрыть сессию</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {ref, computed} from 'vue';
import {route} from "ziggy-js";

export default {
    methods: {route},
    props: {
        sessions: Array
    },
    emits: ['close-session'],
    setup(props, {emit}) {
        const searchQuery = ref('');
        const sortField = ref('');
        const sortDirection = ref('asc');
        const filteredSessions = computed(() => {
            let sessions = props.sessions;
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase();
                sessions = users.filter(user => {
                    return session.name.toLowerCase().includes(query) || session.email.toLowerCase().includes(query);
                });
            }
            if (sortField.value) {
                sessions = sessions.sort((a, b) => {
                    const aValue = a[sortField.value];
                    const bValue = b[sortField.value];

                    if (aValue < bValue) return sortDirection.value === 'asc' ? -1 : 1;
                    if (aValue > bValue) return sortDirection.value === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            return sessions;
        });
        const searchUsers = () => {
        };
        const sortBy = (field) => {
            if (sortField.value === field) {
                sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
            } else {
                sortField.value = field;
                sortDirection.value = 'asc';
            }
        };

        const closeSession = (sessionId) => {
            emit('close-session', sessionId);
        };



        return {
            searchQuery,
            filteredSessions,
            sortBy,
            searchUsers,
            closeSession
        };
    }
}
</script>

<style scoped>
.table-container {
    max-height: 600px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
}

.table {
    width: auto;
    margin-bottom: 1rem;
    color: #212529;
}

.table td {
    padding: 0.3rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
    cursor: pointer;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    position: sticky;
    top: 0;
    background-color: #fff;
}

.btn-xs {
    padding: 0.1rem 0.3rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.me-2 {
    margin-right: 0.5rem;
}
</style>
