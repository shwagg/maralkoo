<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function (window, $) {
        if (!window || !$) {
            return;
        }

        var tokenMeta = document.querySelector('meta[name="csrf-token"]');
        var tokenNameMeta = document.querySelector('meta[name="csrf-token-name"]');
        var headerNameMeta = document.querySelector('meta[name="csrf-header-name"]');

        window.Meralkoo = window.Meralkoo || {};

        window.Meralkoo.getCsrfToken = function () {
            return tokenMeta ? tokenMeta.getAttribute('content') || '' : '';
        };

        window.Meralkoo.updateCsrfToken = function (token) {
            if (tokenMeta && token) {
                tokenMeta.setAttribute('content', token);
            }
        };

        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function (xhr, settings) {
                var csrfToken = window.Meralkoo.getCsrfToken();
                var csrfHeader = headerNameMeta ? headerNameMeta.getAttribute('content') : '';
                var csrfTokenName = tokenNameMeta ? tokenNameMeta.getAttribute('content') : '';

                if (csrfToken && csrfHeader) {
                    xhr.setRequestHeader(csrfHeader, csrfToken);
                }

                if (csrfToken && csrfTokenName && settings && settings.type && settings.type.toUpperCase() !== 'GET') {
                    if (typeof settings.data === 'string' && settings.data.indexOf(csrfTokenName + '=') === -1) {
                        settings.data += (settings.data ? '&' : '') + encodeURIComponent(csrfTokenName) + '=' + encodeURIComponent(csrfToken);
                    } else if ($.isPlainObject(settings.data) && settings.data[csrfTokenName] === undefined) {
                        settings.data[csrfTokenName] = csrfToken;
                    }
                }
            }
        });

        window.Meralkoo.ajax = function (options) {
            return $.ajax(options).done(function (response, textStatus, jqXHR) {
                var nextToken = jqXHR.getResponseHeader('X-CSRF-TOKEN');

                if (!nextToken && response && typeof response === 'object') {
                    nextToken = response.csrfHash || response.csrf_hash || response.token || '';
                }

                if (nextToken) {
                    window.Meralkoo.updateCsrfToken(nextToken);
                }
            });
        };
    })(window, window.jQuery);
</script>
