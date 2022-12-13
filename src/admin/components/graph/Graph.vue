<template>
    <div class="apv-graph apv-wrap">
        <h1 class="wp-heading-inline apv-heading">{{__('Graph', 'apv')}}</h1>
        <div class="apv-inside">
            <LineChart :chart-data="chartData" :chart-options="options" />
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { Line as LineChart } from 'vue-chartjs';

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  CategoryScale,
  PointElement
} from 'chart.js';

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  CategoryScale,
  PointElement
);

export default {
    name: "Graph",

    components: {
        LineChart,
    },

    data() {
        return {
            data: {},
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
            chartData: {
                labels: [],
                datasets: [
                    {
                        label: this.__('Values by Date', 'apv'),
                        backgroundColor: '#2CBAF3',
                        data: [],
                    },
                ],
            },
        }
    },

    async mounted() {
        await this.$store.dispatch('base/setData', this.baseData);

        if ( ! this.isEmpty( this.baseData ) ) {
            this.data = JSON.parse(JSON.stringify(this.baseData));
        }
    },

    computed: {
        ...mapGetters({
            baseData: 'base/getData',
        }),

        graph() {
            return ! this.isEmpty( this.data ) && 'graph' in this.data ? this.data.graph : {};
        },
    },

    watch: {
        baseData() {
            if (! this.isEmpty(this.baseData)) {
                this.data = JSON.parse(JSON.stringify(this.baseData));
            }
        },

        graph() {
            Object.keys(this.graph).forEach(key => {
                this.chartData.labels.push(this.formatDate((parseInt(this.graph[key].date) * 1000), 'Y-m-d'));
                this.chartData.datasets[0].data.push(this.graph[key].value);
            });
        }
    }
}
</script>

<style lang="less" scoped>
.apv-graph {
    .apv-inside {
        padding: 50px 20px;
    }
}
</style>
