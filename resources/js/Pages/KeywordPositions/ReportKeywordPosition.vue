<script setup>
import { defineProps, ref, watch, onMounted } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import Chart from 'chart.js/auto'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Datepicker from 'vue-tailwind-datepicker'

// Define props
const props = defineProps({
    chartData: Object,
    filters: Object,
    links: Object,
    startDate: String,
    endDate: String,
    keyword: Object,
})

// Initialize form with props
var form = useForm({
    start_date: props.startDate,
    end_date: props.endDate,
})

// Define refs for chart
const chartRef = ref(null)
const chartInstance = ref(null)
const isChartLoaded = ref(false)
const selectedDate = ref({
  startDate: "",
  endDate: "",
});

const formatter = ref({
  date: 'YYYY-MM-DD', //'DD MM YYYY',
  month: 'MMM',
})

// Custom shortcuts for date picker
const customShortcuts = () => {
  const today = new Date();

  const getLastMonths = (num) => {
    const months = [];
    for (let i = 0; i < num; i++) {
      const monthDate = new Date(today.getFullYear(), today.getMonth() - i, 1);
      const startOfMonth = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1);
      const endOfMonth = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 0);
      const monthLabel = `${(monthDate.getMonth() + 1).toString().padStart(2, '0')}-${monthDate.getFullYear()}`;
      months.push({
        label: monthLabel,
        atClick: () => [startOfMonth, endOfMonth],
      });
    }
    return months;
  };

  return [
    {
      label: "This Month",
      atClick: () => {
        const today = new Date();
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        return [startOfMonth, new Date()];
      },
    },
    {
      label: "Last Month",
      atClick: () => {
        const today = new Date();
        const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
        return [startOfLastMonth, endOfLastMonth];
      },
    },
    {
      label: "Last 2 Months",
      atClick: () => {
        const today = new Date();
        const startOfLastTwoMonths = new Date(today.getFullYear(), today.getMonth() - 2, 1);
        return [startOfLastTwoMonths, new Date()];
      },
    },
    {
      label: "Last 3 Months",
      atClick: () => {
        const today = new Date();
        const startOfLastThreeMonths = new Date(today.getFullYear(), today.getMonth() - 3, 1);
        return [startOfLastThreeMonths, new Date()];
      },
    }, 
    {
        label: "This Year",
        atClick: () => {
            const date = new Date();
            return [new Date(date.getFullYear(), 0, 1), new Date()];
        },
    }, 
    {
      label: "Last Years",
      atClick: () => {
        const date = new Date();
        return [new Date(date.setFullYear(date.getFullYear() - 1)), new Date()];
      },
    },
    ...getLastMonths(6),
  ];
}


// Function to fetch data
const fetchData = () => {
    if (!isChartLoaded.value) return

    form.post(
        route('keyword-positions.report', {
            keyword_id: props.keyword.id,
            start_date: form.start_date,
            end_date: form.end_date,
        }),
        {
            preserveScroll: true,
            onSuccess: () => updateChart(),
            onError: errors => {
                alert('Bir hata oluştu: ' + errors)
            },
            onFinish: () => form.reset(),
        }
    )
}

// Function to set selected date range
const setDateRange = (newDate) => {

    console.log("newDate:",newDate);

    form.start_date = newDate.startDate
    form.end_date = newDate.endDate

    fetchData()
}

// Function to render chart
const renderChart = async () => {
    if (chartInstance.value) chartInstance.value.destroy()

    chartInstance.value = new Chart(chartRef.value.getContext('2d'), {
        type: 'line',
        data: {
            labels: props.chartData.labels,
            datasets: props.chartData.datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    })
    isChartLoaded.value = true
}

// Function to update chart
const updateChart = () => {
    if (chartInstance.value) chartInstance.value.destroy()
    renderChart()
}

// On mounted hook to render chart initially
onMounted(() => {
    renderChart()
})
</script>

<template>
    <Head title="Keyword Positions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Keyword Position Report: {{ keyword.keyword }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg">
                    <div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <Datepicker v-model="selectedDate" format="YYYY-MM-DD" :shortcuts="customShortcuts" @update:model-value="setDateRange" :formatter="formatter" :auto-apply="true" />
                                </div>
                                
                                <div>
                                    <Link :href="route('keywords.index')" class="mt-1 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :disabled="!isChartLoaded">
                                        Return
                                    </Link>
                                </div>
                            </div>

                            <div style="height: 400px">
                                <canvas ref="chartRef"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
