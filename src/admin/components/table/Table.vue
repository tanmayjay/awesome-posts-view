<template>
    <div class="apv-data-table apv-wrap">
        <ListTable
            :title="title"
            :headers="headers"
            :rows="rows"
        />
        <div v-if="emails.length" class="apv-settings-emails">
            <h2>{{__('Emails', 'apv')}}</h2>
            <ul>
                <li class="list-items" v-for="(email, index) in emails" :key="index">
                    <a :href="'mailto:' + email">{{email}}</a>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ListTable from '../generic/ListTable.vue';

export default {
    name: "Table",

    components: {
        ListTable,
    },

    data() {
        return {
            data: {},
        }
    },

    async mounted() {
        await this.$store.dispatch('base/setData', this.baseData);

        if ( ! this.isEmpty( this.baseData ) ) {
            this.data = this.deepCopy(this.baseData);
        }
    },

    computed: {
        ...mapGetters({
            baseData: 'base/getData',
            settings: 'settings/getData',
        }),

        table() {
            return ! this.isEmpty( this.data ) && 'table' in this.data ? this.data.table : {};
        },

        title() {
            return 'title' in this.table ? this.table.title : '';
        },

        tableData() {
            return 'data' in this.table ? this.table.data : {};
        },

        headers() {
            let headers = [];

            if ('headers' in this.tableData) {
                headers = this.tableData.headers.slice();
                headers.splice(headers.indexOf('Url'), 1);
            }

            return headers;
        },

        rows() {
            let rows = [];

            if (! ('rows' in this.tableData)) {
                return rows;
            }

            rows = this.tableData.rows.slice();

            if ('numrows' in this.settings) {
                rows = rows.filter((row, index) => index < this.settings.numrows);
            }

            rows = rows.map(row => {
                row.date = this.settings.humandate ? this.formatDate(parseInt(row.date) * 1000) : row.date;
                row.id = `<a href="${row.url}" target="_blank">#${row.id}</a>`;
                delete row.url;
                return row;
            });

            return rows;
        },

        emails() {
            return 'emails' in this.settings ? this.settings.emails : [];
        },
    },

    watch: {
        baseData() {
            if (! this.isEmpty(this.baseData )) {
                this.data = this.deepCopy(this.baseData);
            }
        }
    }
}
</script>

<style lang="less" scoped>
.apv-settings-emails {
    ul {
        width: max-content;
        background: #fff;

        li {
            background: #fff;
            padding: 8px 18px;
            border-top: 1px solid;
            border-bottom: 1px solid;
            border-color: #e2e2e2;
            font-size: 14px;
            margin: 0;

            a {
                text-decoration: none;
                color: #595959;
                font-weight: 400;
            }
        }
    }
}
</style>
