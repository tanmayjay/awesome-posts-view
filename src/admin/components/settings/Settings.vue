<template>
    <div class="apv-settings apv-wrap">
        <h1 class="wp-heading-inline apv-heading">{{__('Settings', 'apv')}}</h1>
        <div class="apv-inside">
            <form>
                <table class="form-table">
                    <tbody>
                        <template v-if="! loading">
                            <tr v-if="('numrows' in settings)">
                                <th scope="row">
                                    <label for="numrows">{{__( 'Number of Rows', 'apv' )}}</label>
                                </th>
                                <td>
                                    <input id="numrows" type="number" max="5" min="1" v-model="settings.numrows" />
                                    <p class="description">{{__( 'The number of rows to be shown for table.', 'apv' )}}</p>
                                </td>
                            </tr>
                            <tr v-if="('humandate' in settings)">
                                <th scope="row">
                                    <label>{{__( 'Human Readable Date', 'apv' )}}</label>
                                </th>
                                <td>
                                    <input id="humandate" type="checkbox" class="switch is-rounded is-info" :checked="settings.humandate" @change="toggleHumanDate" />
                                    <label for="humandate"></label>
                                    <p class="description">{{__( 'Show the date in human readable format.', 'apv' )}}</p>
                                </td>
                            </tr>
                            <tr v-if="('emails' in settings)">
                                <th scope="row">
                                    <label for="emails">{{__( 'Emails', 'apv' )}}</label>
                                </th>
                                <td>
                                    <div v-for="(email, index) in settings.emails" :key="index" class="input-group">
                                        <input type="text" v-model="settings.emails[index]" />
                                        <span v-if="allowRemovingEmail"
                                            @click="removeEmailField(index)"
                                            class="dashicons dashicons-dismiss repeater-control remove"
                                        ></span>
                                        <span v-if="allowNewEmail && index === totalEmails -1"
                                            @click="addEmailField"
                                            class="dashicons dashicons-plus-alt repeater-control add"
                                        ></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <button class="apv-btn apv-btn-submit" type="submit" @click.prevent="submit">
                                        {{__('Save Settings', 'apv')}}
                                    </button>
                                </th>
                            </tr>
                        </template>
                        <template v-else>
                            <tr class="info-text">{{__('Loading data...', 'apv')}}</tr>
                        </template>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'Settings',

    data() {
        return {
            settings: {},
            settingsChanged: {},
        }
    },

    mounted() {
        this.settings = this.deepCopy(this.settingsData);
    },

    created() {
        if (this.totalEmails === 0) {
            this.addEmailField();
        }
    },

    computed: {
        ...mapGetters({
            settingsData: 'settings/getData',
            loading: 'spinner/getStatus'
        }),

        totalEmails() {
            return 'emails' in this.settings ? this.settings.emails.length : 0;
        },

        allowNewEmail() {
            return this.totalEmails < 5 && 'emails' in this.settings && this.settings.emails.every(email => ! this.isEmpty(email));
        },

        allowRemovingEmail() {
            return this.totalEmails > 1;
        },

        emails() {
            return this.settings.emails;
        },

        humandate() {
            return this.settings.humandate;
        },

        numrows() {
            return this.settings.numrows;
        }
    },

    methods: {
        toggleHumanDate() {
            this.settings.humandate = ! this.settings.humandate;
        },

        addEmailField() {
            if(this.allowNewEmail) {
                this.settings.emails.push('');
            }
        },

        removeEmailField(index) {
            if(this.allowRemovingEmail) {
                this.settings.emails.splice(index, 1);
            }
        },

        async submit() {
            let newData = {};

            for (let key of Object.keys(this.settingsChanged)) {
                if (this.settingsChanged[key]) {
                    const data = {};
                    data[key] = this.settings[key];
                    await this.$store.dispatch('settings/updateData', {...data}).then(success => {
                        this.settingsChanged[key] = false;
                        newData[key] = data[key];
                    }, error => {
                        this.$toast.error(error);
                    });
                }
            }

            if (! this.isEmpty(newData)) {
                this.$store.commit('settings/setData', {...this.settingsData, ...newData});
                this.$toast.success(this.__('Settings saved successfully.', 'apv'));
            }
        },
    },

    watch: {
        settingsData() {
            this.settings = this.deepCopy(this.settingsData);
        },

        emails(newVal) {
            this.settingsChanged.emails = ! this.isEqual(newVal, this.settingsData.emails);

            if (newVal.length === 0) {
                this.addEmailField();
            }
        },

        numrows(newVal) {
            this.settingsChanged.numrows = parseInt(newVal) !== parseInt(this.settingsData.numrows);
        },

        humandate(newVal) {
            this.settingsChanged.humandate = newVal !== this.settingsData.humandate;
        },
    }
}
</script>

<style scoped>
@import 'bulma-switch/dist/css/bulma-switch.min.css';

.apv-settings p.description {
    display: block;
}

.apv-settings .form-table {
    padding: 20px;
}

.apv-settings .form-table .input-group {
    display: flex;
}

.apv-settings .form-table th {
    padding-left: 20px;
    width: 250px;
}

.apv-settings .form-table td {
    padding-left: 20px;
}

.apv-settings .form-table input {
    width: 80%;
    min-width: 80%;
    line-height: 2.5;
    outline: none;
    border-color: #d3d3d394;
    border-width: 1.5px;
}

.apv-settings .form-table tr {
    border-bottom: 1px solid #e4e4e4;
    padding: 30px 0;
    font-size: 14px;
    line-height: 1.3;
}

.apv-settings .form-table tr:first-child {
    border-top: 1px solid #e4e4e4;
}

.apv-settings .dashicons.repeater-control {
    font-size: 30px;
    margin: 5px 3px 0 3px;
    cursor: pointer;
    color: #d3d3d394;
}

.apv-settings .dashicons.repeater-control.remove:hover {
    color: rgb(203, 90, 90);
}

.apv-settings .dashicons.repeater-control.add:hover {
    color: rgb(28, 170, 226);
}
</style>
