
/**
 * Ajax handler
 */
const Api = {

    /**
     * Localized data
     */
    data: window.apvAdminData.ajax,

    /**
     * Creates a AJAX request.
     *
     * @param {String} method
     * @param {Object} data
     *
     * @returns {Promise}
     */
    ajax: (action, method, data) => {
        let override = null;

        if ('PUT' === method || 'DELETE' === method) {
            override = method;
            method   = 'POST';
        }

        if (data._wpnonce === undefined || ! data._wpnonce) {
            data._wpnonce = Api.data.nonce;
        }

        data.action = `${Api.data.prefix}${action}`;

        return jQuery.ajax({
            url: Api.data.url,
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader('X-WP-Nonce', Api.data.nonce);

                if ( override ) {
                    xhr.setRequestHeader('X-HTTP-Method-Override', override);
                }
            },
            type: method,
            data,
        });
    },

    /**
     * Creates a GET type AJAX request.
     *
     * @param {String} action
     * @param {Object} data
     *
     * @returns {Promise}
     */
    get: (action, data = {}) => Api.ajax(action, 'GET', data),

    /**
     * Creates a POST type AJAX request.
     *
     * @param {Object} data
     *
     * @returns {Promise}
     */
    post: (action, data = {}) => Api.ajax(action, 'POST', data),
}

export default Api;
