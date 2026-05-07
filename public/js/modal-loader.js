(function ($) {
    'use strict';

    if (typeof $ === 'undefined') {
        console.warn('[modal-loader] jQuery not loaded');
        return;
    }

    $(function () {
        var $modal = $('#vms-modal');
        var $body = $('#vms-modal-content');

        if (!$modal.length) {
            return;
        }

        function setHtmlAndRunScripts($container, html) {
            // IMPORTANT: When inserting HTML via .html()/innerHTML, <script> tags do not execute.
            // We extract scripts, inject the remaining DOM, then re-create scripts so they run.
            var wrapper = document.createElement('div');
            wrapper.innerHTML = html;

            var scripts = Array.prototype.slice.call(wrapper.querySelectorAll('script'));
            scripts.forEach(function (s) { s.parentNode && s.parentNode.removeChild(s); });

            $container.html(wrapper.innerHTML);

            scripts.forEach(function (oldScript) {
                var newScript = document.createElement('script');
                // Copy attributes (src, type, etc.)
                for (var i = 0; i < oldScript.attributes.length; i++) {
                    var attr = oldScript.attributes[i];
                    newScript.setAttribute(attr.name, attr.value);
                }

                // Inline script content
                if (!newScript.src) {
                    newScript.text = oldScript.text || oldScript.textContent || '';
                }

                // Append into modal so it has access to the injected DOM
                $container[0].appendChild(newScript);
            });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        function openFromUrl(url, size) {
            $body.html('<div class="modal-body text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>');
            $modal.find('.modal-dialog').removeClass('modal-sm modal-md modal-lg modal-xl').addClass('modal-' + (size || 'lg'));
            $modal.modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html'
            }).done(function (html) {
                setHtmlAndRunScripts($body, html);
            }).fail(function (xhr) {
                $body.html('<div class="modal-header"><h5 class="modal-title text-danger">Error</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p>Failed to load modal (' + xhr.status + ').</p></div>');
            });
        }

        $(document).on('click', '[data-modal-url]', function (e) {
            e.preventDefault();
            var url = $(this).data('modal-url');
            var size = $(this).data('modal-size') || 'lg';
            if (url) {
                openFromUrl(url, size);
            }
        });

        $(document).on('click', '[data-modal-close]', function (e) {
            e.preventDefault();
            $modal.modal('hide');
        });

        $modal.on('hidden.bs.modal', function () {
            $body.empty();
        });

        window.vmsOpenModal = openFromUrl;
    });
})(window.jQuery);
