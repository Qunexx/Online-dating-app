<template>
    <div>
        <h2>Пользователи</h2>
        <div class="mb-3">
            <input type="text" v-model="searchQuery" @input="searchUsers" placeholder="Поиск по имени или email" class="form-control">
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th @click="sortBy('id')">ID</th>
                    <th @click="sortBy('name')">Имя</th>
                    <th @click="sortBy('email')">Email</th>
                    <th @click="sortBy('role')">Роль</th>
                    <th @click="sortBy('is_banned')">Заблокирован</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in filteredUsers" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>{{ user.is_banned ? 'Да' : 'Нет' }}</td>
                    <td>
                        <button @click="editUser(user.id)" class="btn btn-xs btn-primary me-2">Редактировать</button>
                        <button @click="showProfile(user.id)" class="btn btn-xs btn-primary me-2">Показать профиль</button>
                        <div v-if="user.is_banned">
                            <button @click="banUser(user.id)" class="btn btn-xs btn-danger">Разблокировать</button>
                        </div>
                        <div v-else>
                            <button @click="banUser(user.id)" class="btn btn-xs btn-danger">Заблокировать</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
    props: {
        users: Array
    },
    emits: ['edit-user', 'ban-user', 'show-user-profile'],
    setup(props, { emit }) {
        const searchQuery = ref('');
        const sortField = ref('');
        const sortDirection = ref('asc');
        const filteredUsers = computed(() => {
            let users = props.users;
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase();
                users = users.filter(user => {
                    return user.name.toLowerCase().includes(query) || user.email.toLowerCase().includes(query);
                });
            }
            if (sortField.value) {
                users = users.sort((a, b) => {
                    const aValue = a[sortField.value];
                    const bValue = b[sortField.value];

                    if (aValue < bValue) return sortDirection.value === 'asc' ? -1 : 1;
                    if (aValue > bValue) return sortDirection.value === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            return users;
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
        const editUser = (userId) => {
            emit('edit-user', userId);
        };

        const banUser = (userId) => {
            emit('ban-user', userId);
        };

        const showProfile = (userId) => {
            emit('show-user-profile', userId);
        };

        return {
            searchQuery,
            filteredUsers,
            sortBy,
            searchUsers,
            editUser,
            banUser,
            showProfile
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
