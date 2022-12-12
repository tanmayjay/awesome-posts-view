import {__} from "@wordpress/i18n";

export default {
    methods: {
        /**
         * Converts a text in a translatable form.
         *
         * @param {String} text The text that needs to be translatable.
         * @param {String} domain The text domain
         *
         * @returns {String}
         */
        __: (text, domain) =>  __(text, domain),

        /**
         * Checks if a value is empty.
         *
         * @param {Any} data
         *
         * @returns {Boolean}
         */
        isEmpty: data => Object.keys(data || {}).length === 0,

        /**
         * Deep copy an object or array by values.
         *
         * @param {Object|Array} data
         *
         * @returns {Object|Array}
         */
        deepCopy: data => JSON.parse(JSON.stringify(data)),

        /**
         * Checks if two values are equal.
         *
         * @param {Any} value1
         * @param {Any} value2
         *
         * @returns {Boolean}
         */
        isEqual: (value1, value2) => {
            const dataType = typeof value1;

            if (dataType !== typeof value2) {
                return false;
            }

            let isEqual = false;

            switch (dataType) {
                case 'object':
                    isEqual = JSON.stringify(value1) === JSON.stringify(value2);
                    break;

                case 'array':
                    isEqual = value1.join() === value2.join();
                    break;

                default:
                    isEqual = value1 === value2;
            }

            return isEqual;
        },

        /**
         * Checks if two objects are equal.
         *
         * @param {Object} obj1
         * @param {Object} obj2
         *
         * @returns {Boolean}
         */
        isEqualObject: (obj1, obj2) => JSON.stringify(obj1) === JSON.stringify(obj2),

        /**
         * Checks if two arrays are equal.
         *
         * @param {Array} arr1
         * @param {Array} arr2
         *
         * @returns {Boolean}
         */
        isEqualArray: (arr1, arr2) => arr1.join() === arr2.join(),

        /**
         * Converts dates in a given format.
         *
         * @param {String} date
         * @param {String} format
         *
         * @returns {String}
         */
        formatDate: (date, format) => {
            if (! date) {
                return '-';
            }

            let dateObj = new Date(date),
                month   = dateObj.getMonth() + 1,
                day     = dateObj.getDate(),
                year    = dateObj.getFullYear();

            if (month.toString().length < 2) {
                month = '0' + month;
            }

            if (day.toString().length < 2) {
                day = '0' + day;
            }

            switch (format) {
                case 'd/m/Y':  // -- 31/12/2020
                    return [day, month, year].join('/');

                case 'm/d/Y':  // -- 12/31/2020
                    return [month, day, year].join('/');

                case 'm-d-Y':  // -- 12-31-2020
                    return [month, day, year].join('-');

                case 'd-m-Y':  // -- 31-12-2020
                    return [day, month, year].join('-');

                case 'Y-m-d':  // -- 2020-12-31
                    return [year, month, day].join('-');

                case 'd.m.Y':  // -- 31.12.2020
                    return [day, month, year].join('.');

                default:
                    return dateObj.toDateString().replace(/^\S+\s/, '');
            }
        },
    }
};
