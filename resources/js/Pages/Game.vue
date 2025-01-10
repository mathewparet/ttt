<script setup>
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref, computed } from 'vue';
    import { onMounted } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    const page = usePage();

    const props = defineProps({
        gridSize: {
            type: Number,
            default: 3,
        }
    });

    const form = useForm({
        gridSize: props.gridSize,
        grid: [],
    });

    const grid = ref([])
    const newGame = ref(true);
    const gameStart = ref(null)
    const gameEnd = ref(null)
    const intervalId = ref(null)

    const isActiveGame = computed(() => {
        if(newGame.value) {
            return true;
        }
        else {
            return !page.props.jetstream.flash?.is_game_over
        }
    });

    onMounted(() => {
        prepareGrid();
    });

    const prepareGrid = () => {
        const size = form.gridSize;
        grid.value = Array.from({ length: size }, () => Array.from({ length: size }, () => ''));
        newGame.value = true;
    }

    const getLabel = (rowIndex, colIndex) => {
        return grid.value[rowIndex][colIndex];
    };

    const setCellValue = (rowIndex, colIndex, value) => {
        grid.value[rowIndex][colIndex] = value;
        newGame.value = false;
    };

    const getDuration = () => {
        return (gameEnd.value - gameStart.value) / 1000;
    };

    const saveWinner = (winner) => {
        form.transform((data) => 
                {
                    return {
                        ...data,
                        name: winner,
                        grid: grid.value,
                        duration: getDuration(),
                    }
                }
            )
            .post(page.props.jetstream.flash.winner_link, {});
    };

    const cellAlredyPlayed = (rowIndex, colIndex) => {
        return grid.value[rowIndex][colIndex] !== '';
    };

    const play = (rowIndex, colIndex) => {
        if(gameStart.value == null)
        {
            gameStart.value = new Date();

            intervalId.value = setInterval(() => {
                if(isActiveGame.value)
                    gameEnd.value = new Date();
            }, 100);
        }
        setCellValue(rowIndex, colIndex, 'X');

        form.transform((data) => 
                {
                    return {
                        matrix: grid.value,
                    }
                }
            )
            .post(route('game.play'), {
                preserveScroll: true,
                onSuccess: () => {
                    if(!page.props.jetstream.flash.is_player_win) {
                        if(page.props.jetstream.flash.col != null)
                            setCellValue(page.props.jetstream.flash.row, page.props.jetstream.flash.col, 'O');
                    }
                    else {
                        intervalId.value = clearInterval(intervalId.value);
                        gameEnd.value = new Date();
                        let winner = prompt('Congratulations, you won!. Please provide your name for the leaderboard.');

                        if(winner.length)
                            saveWinner(winner);
                    }
                },
            });
    };
</script>
<template>
    <div class="mb-2">
        <TextInput v-model="form.gridSize" placeholder="Grid Size" type="number" min="3" class="text-gray-800" @input="prepareGrid"/>
    </div>
    <table id="game_grid">
        <tr v-for="(row, rowIndex) in grid" :key="rowIndex">
            <td v-for="(cell, colIndex) in row" :key="colIndex" >
                <PrimaryButton class="w-12 h-12 border border-gray-300 flex items-center justify-center" @click="play(rowIndex, colIndex)" :disabled="!isActiveGame || cellAlredyPlayed(rowIndex, colIndex)" :class="{'opacity-50': !isActiveGame || cellAlredyPlayed(rowIndex, colIndex)}">
                    <span :class="{'text-red-400': cell === 'O' && $page.props.jetstream.flash.is_computer_win, 'text-green-400': cell === 'X' && $page.props.jetstream.flash.is_player_win}">
                        {{  getLabel(rowIndex, colIndex) }}
                    </span>
                </PrimaryButton>
            </td>
        </tr>
    </table>
    <div>{{ (getDuration()).toFixed(0) }} seconds</div>
    <div v-if="page.props.jetstream.flash.is_player_win" class="text-green-400 dark:text-green-600">Player won</div>
    <div v-if="page.props.jetstream.flash.is_computer_win" class="text-red-400 dark:text-red-600">Computer won</div>
</template>
