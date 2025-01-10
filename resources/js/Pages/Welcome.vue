<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Game from './Game.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Welcome">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Welcome
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10">
                    <Game class="w-full" :grid-size="3"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
