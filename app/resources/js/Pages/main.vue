<template>
    <DefaultLayout>
        <div class="container mt-5">
            <div v-if="success" class="alert alert-success">
                {{ success }}
            </div>
            <div v-if="error" class="alert alert-danger">
                {{ error }}
            </div>
            <h1 class="text-center">Это проверка, что Vue и Inertia работают!!</h1>
            <button class="btn btn-primary">Нажми меня</button>
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
        errors: Object,
        error: String,
        success: String,
    }
}
</script>
