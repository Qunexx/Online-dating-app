<template>
    <div>
        <h2>Вопросы и предложения</h2>
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
                    <th @click="sortBy('message')">Сообщение</th>
                    <th @click="sortBy('is_processed')">Обработана</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="question in filteredQuestions" :key="question.id">
                    <td>{{ question.id }}</td>
                    <td>{{ question.name }}</td>
                    <td>{{ question.email }}</td>
                    <td>{{ question.message }}</td>
                    <td>{{ question.is_processed ? 'Да' : 'Нет' }}</td>
                    <td>
                        <div v-if="!question.is_processed">
                            <button @click="processQuestion(question.id)" class="btn btn-xs btn-primary me-2" :disabled="question.is_processed">Обработать</button>
                        </div>
                        <button @click="resendEmail(question.id)" class="btn btn-xs btn-primary me-2" :disabled="!question.is_processed">Ответить</button>
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
        questions: Array
    },
    emits: ['process-question'],
    setup(props, { emit }) {
        const searchQuery = ref('');
        const sortField = ref('');
        const sortDirection = ref('asc');
        const filteredQuestions = computed(() => {

            let questions = props.questions;
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase();
                questions = questions.filter(question => {
                    return question.name.toLowerCase().includes(query) || question.email.toLowerCase().includes(query);
                });
            }
            if (sortField.value) {
                questions = questions.sort((a, b) => {
                    const aValue = a[sortField.value];
                    const bValue = b[sortField.value];

                    if (aValue < bValue) return sortDirection.value === 'asc' ? -1 : 1;
                    if (aValue > bValue) return sortDirection.value === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            return questions;
        });
        const sortBy = (field) => {
            if (sortField.value === field) {
                sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
            } else {
                sortField.value = field;
                sortDirection.value = 'asc';
            }
        };
        const processQuestion = (questionId) => {
            emit('process-question', questionId);
        };


        return {
            searchQuery,
            filteredQuestions,
            sortBy,
            processQuestion,
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
