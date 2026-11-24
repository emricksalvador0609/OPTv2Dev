/*
 * Customer Link Accounts - "Link To" dropdown.
 *
 * The full user list is fetched once per page load instead of being repeated in
 * every customer row. Each row's dropdown starts with an empty
 * .linkto-user-options container and is filled the first time it is opened.
 *
 * Filling on open (not on load) keeps a 600-user list from being cloned into
 * hundreds of dropdowns at once.
 */
(function () {
    'use strict';

    var OPTIONS_URL = '/datatable_customer_link_user_options';

    var optionsHtml = null;
    var request = null;

    function loadOptions() {
        if (optionsHtml !== null) {
            return Promise.resolve(optionsHtml);
        }

        if (request) {
            return request;
        }

        request = fetch(OPTIONS_URL, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Unable to load the user list.');
                }

                return response.json();
            })
            .then(function (data) {
                optionsHtml = typeof data.options === 'string' ? data.options : '';

                return optionsHtml;
            })
            .catch(function () {
                optionsHtml = null;
                request = null;

                return '';
            });

        return request;
    }

    function fillDropdown(menu) {
        var holder = menu.querySelector('.linkto-user-options');

        if (!holder || holder.getAttribute('data-filled') === '1') {
            return;
        }

        loadOptions().then(function (html) {
            if (holder.getAttribute('data-filled') === '1') {
                return;
            }

            holder.innerHTML = html;
            holder.setAttribute('data-filled', '1');
        });
    }

    function dropdownFor(toggle) {
        var wrapper = toggle.parentElement;

        return wrapper ? wrapper.querySelector('.dropdown-menu') : null;
    }

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest ? event.target.closest('.btn-reveal') : null;

        if (!toggle) {
            return;
        }

        var menu = dropdownFor(toggle);

        if (menu) {
            fillDropdown(menu);
        }
    });

    // Warm the cache so the first dropdown opens with content already loaded.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadOptions);
    } else {
        loadOptions();
    }
})();
