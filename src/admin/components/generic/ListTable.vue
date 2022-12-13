<template>
    <div class="apv-table">
        <h1 class="wp-heading-inline apv-heading">{{tableTitle}}</h1>
        <table class="apv-list-table widefat fixed striped table-view-list apv-inside">
            <thead v-if="hasHeaders">
                <tr>
                    <th v-for="(header, index) in headers" :key="index" scope="col" class="manage-column">
                        <strong>{{header}}</strong>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <template v-if="loading">
                    <tr>
                        <td>
                            {{loadingText || __( 'Loading data...', 'apv' )}}
                        </td>
                    </tr>
                </template>
                <template v-else-if="hasRowData">
                    <tr v-for="(row, index) in rows" :key="index">
                        <td v-for="(data, key) in row" :key="key" :class="key" v-html="data"></td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td>
                            {{noDataText || __( 'No data found.', 'apv' )}}
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'ListTable',

    props: {
        title: {
            type: String,
            default: "",
        },
        headers: {
            type: Array,
            required: true,
        },
        rows: {
            type: Array,
            required: true,
            default: () => [],
        },
        noDataText: String,
        loadingText: String,
    },

    computed: {
        ...mapGetters({
            loading: 'spinner/getStatus',
        }),

        tableTitle() {
            if (this.isEmpty(this.title)) {
                return this.$route.name;
            }
            return this.title;
        },

        hasRowData() {
            return this.rows.length;
        },

        hasHeaders() {
            return this.headers.length;
        },
    },
};
</script>

<style lang="less">
.apv-heading {
    margin-bottom: 15px;
}

.apv-list-table {
    position: relative;
    width: 100%;
    margin-bottom: 1rem;
    border-radius: 3px;

    th, td {
        color: #525252;
        padding: 18px 10px;
        vertical-align: middle;
        border-top: 2px solid #f6f6f6;
    }

    thead {
        tr {
            box-shadow: 0 5px 12px 0 rgb(0 100 235 / 6%);
            transform: translateY(-0.1px);
        }
    }
}
</style>
