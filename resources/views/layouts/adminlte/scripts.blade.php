<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ $refAsset }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ $refAsset }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ $refAsset }}/dist/js/adminlte.min.js"></script>
<script>
    (function () {
        const modalId = 'vms-modal';
        const bodyId = 'vms-modal-body';
        const titleId = 'vms-modal-title';

        function setHtmlAndRunScripts(container, html) {
            if (!container) return;

            // When injecting HTML with innerHTML, <script> tags do not execute.
            // Extract them, inject DOM, then recreate scripts so they run.
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;

            const scripts = Array.from(wrapper.querySelectorAll('script'));
            scripts.forEach((s) => s.parentNode && s.parentNode.removeChild(s));

            container.innerHTML = wrapper.innerHTML;

            scripts.forEach((oldScript) => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach((attr) => {
                    newScript.setAttribute(attr.name, attr.value);
                });

                if (!newScript.src) {
                    newScript.text = oldScript.text || oldScript.textContent || '';
                }

                container.appendChild(newScript);
            });
        }

        function setLoading(title) {
            const $title = document.getElementById(titleId);
            const $body = document.getElementById(bodyId);
            if ($title && title) $title.textContent = title;
            if ($body) {
                $body.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center py-5">
                        <div class="text-center text-muted">
                            <div class="spinner-border" role="status" aria-label="Loading"></div>
                            <div class="mt-3">Loading…</div>
                        </div>
                    </div>
                `;
            }
        }

        async function loadIntoModal(url, title) {
            setLoading(title);
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                credentials: 'same-origin',
            });

            if (!res.ok) {
                throw new Error(`Failed to load modal (${res.status})`);
            }

            const html = await res.text();
            const $body = document.getElementById(bodyId);
            setHtmlAndRunScripts($body, html);
        }

        function showModal() {
            // Bootstrap 4 (AdminLTE 3) modal via jQuery
            window.jQuery && window.jQuery(`#${modalId}`).modal('show');
        }

        function closeModal() {
            window.jQuery && window.jQuery(`#${modalId}`).modal('hide');
        }

        document.addEventListener('click', async function (e) {
            const trigger = e.target.closest('[data-modal-url]');
            if (!trigger) return;

            const url = trigger.getAttribute('data-modal-url');
            if (!url) return;

            e.preventDefault();

            const title = trigger.getAttribute('data-modal-title') || trigger.textContent?.trim() || 'Details';
            showModal();

            try {
                await loadIntoModal(url, title);
            } catch (err) {
                const $body = document.getElementById(bodyId);
                if ($body) {
                    $body.innerHTML = `
                        <div class="alert alert-danger mb-0">
                            <div class="font-weight-bold">Could not load popup.</div>
                            <div class="small">${(err && err.message) ? err.message : 'Unknown error'}</div>
                        </div>
                    `;
                }
            }
        });

        // Allow modal content to close itself
        document.addEventListener('click', function (e) {
            const closeBtn = e.target.closest('[data-modal-close]');
            if (!closeBtn) return;
            e.preventDefault();
            closeModal();
        });
    })();
</script>
@stack('scripts')
