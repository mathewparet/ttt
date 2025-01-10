<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    winners: Array,
    total_winners: Number,
});

</script>

<template>
    <AppLayout title="Leaderboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Leaderboard
                <small class="ms-2 font-semibold text-gray-500 dark:text-gray-400">
                    Total Winners: {{ total_winners }} <span class="text-xs italic">(Only top 20 are shown in the leaderboard)</span>
                </small>
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3  text-right">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-right">
                                    Duration (Seconds)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Grid Size
                                </th>
                                <th cope="col" class="px-6 py-3">
                                    Played at
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(winner, index) in winners" :key="winner.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class=" text-right px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index+1 }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ winner.name }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{ winner.duration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ winner.grid_size }}x{{ winner.grid_size }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ DateTime.fromISO(winner.created_at).toLocaleString(DateTime.DATETIME_MED) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
