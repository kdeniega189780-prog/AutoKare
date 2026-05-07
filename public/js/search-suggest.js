/* VMS shared search-suggest: jQuery-based, debounced, fill-and-submit. */
(function ($) {
    'use strict';

    if (typeof $ === 'undefined') return;

    var DEBOUNCE_MS = 200;

    function escapeHtml(text) {
        if (text == null) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function buildItem(item, index) {
        var primary = escapeHtml(item.primary || item.value || '');
        var secondary = item.secondary ? '<div class="vms-suggest-secondary">' + escapeHtml(item.secondary) + '</div>' : '';
        var meta = item.meta ? '<span class="vms-suggest-meta">' + escapeHtml(item.meta) + '</span>' : '';
        return (
            '<button type="button" class="vms-suggest-item" data-index="' + index + '" data-value="' + escapeHtml(item.value) + '">' +
                '<span class="vms-suggest-text">' +
                    '<span class="vms-suggest-primary">' + primary + '</span>' +
                    secondary +
                '</span>' +
                meta +
            '</button>'
        );
    }

    function wireInput($input) {
        var url = $input.data('suggest-url');
        if (!url) return;

        $input.attr('autocomplete', 'off');

        if (!$input.parent().hasClass('vms-suggest-wrap')) {
            $input.wrap('<div class="vms-suggest-wrap"></div>');
        }
        var $wrap = $input.parent();

        var $dropdown = $('<div class="vms-suggest-dropdown" hidden></div>');
        $wrap.append($dropdown);

        var debounceTimer = null;
        var currentXhr = null;
        var activeIndex = -1;
        var items = [];

        function close() {
            $dropdown.attr('hidden', true).empty();
            activeIndex = -1;
            items = [];
        }

        function setActive(idx) {
            $dropdown.find('.vms-suggest-item').removeClass('is-active');
            if (idx >= 0 && idx < items.length) {
                activeIndex = idx;
                $dropdown.find('.vms-suggest-item').eq(idx).addClass('is-active');
            } else {
                activeIndex = -1;
            }
        }

        function choose(idx) {
            if (idx < 0 || idx >= items.length) return;
            var picked = items[idx];
            $input.val(picked.value);
            close();
            var form = $input.closest('form');
            if (form.length) form.trigger('submit');
        }

        function render(data) {
            items = Array.isArray(data) ? data : [];
            if (items.length === 0) {
                $dropdown.html('<div class="vms-suggest-empty">No matches</div>').removeAttr('hidden');
                return;
            }
            var html = items.map(buildItem).join('');
            $dropdown.html(html).removeAttr('hidden');
            setActive(-1);
        }

        function fetchSuggestions(q) {
            if (currentXhr && currentXhr.readyState !== 4) currentXhr.abort();
            currentXhr = $.ajax({
                url: url,
                method: 'GET',
                data: { q: q },
                dataType: 'json'
            }).done(render).fail(function (jqXHR, status) {
                if (status !== 'abort') close();
            });
        }

        $input.on('input', function () {
            var q = $input.val();
            if (debounceTimer) clearTimeout(debounceTimer);
            if (q.length < 1) { close(); return; }
            debounceTimer = setTimeout(function () { fetchSuggestions(q); }, DEBOUNCE_MS);
        });

        $input.on('keydown', function (e) {
            if ($dropdown.is('[hidden]')) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); setActive(Math.min(activeIndex + 1, items.length - 1)); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(Math.max(activeIndex - 1, 0)); }
            else if (e.key === 'Enter') {
                if (activeIndex >= 0) { e.preventDefault(); choose(activeIndex); }
            } else if (e.key === 'Escape' || e.key === 'Tab') {
                close();
            }
        });

        $dropdown.on('click', '.vms-suggest-item', function () {
            choose(parseInt($(this).data('index'), 10));
        });

        $dropdown.on('mouseenter', '.vms-suggest-item', function () {
            setActive(parseInt($(this).data('index'), 10));
        });

        $(document).on('click.vmsSuggest' + Math.random(), function (e) {
            if (!$.contains($wrap[0], e.target)) close();
        });

        $input.on('blur', function () {
            setTimeout(close, 150);
        });
    }

    $(function () {
        $('input[data-suggest-url]').each(function () { wireInput($(this)); });
    });
})(window.jQuery);
