<template>
    <div class="default-layout d-flex flex-column min-vh-100">
        <HeaderComponent />
        <main class="flex-grow-1">
            <slot></slot>
        </main>
        <FooterComponent />
    </div>
</template>

<script>
import HeaderComponent from './HeaderComponent.vue';
import FooterComponent from './FooterComponent.vue';

export default {
    name: 'DefaultLayout',
    components: {
        HeaderComponent,
        FooterComponent
    }
}
</script>
