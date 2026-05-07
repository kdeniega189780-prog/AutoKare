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
                $body.html(html);
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
