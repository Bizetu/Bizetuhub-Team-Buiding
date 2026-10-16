(function () {
    'use strict';

    const CONFIG = window.TwillMultiSelectFilters;
    if (!CONFIG || !Object.keys(CONFIG).length) return;

    if (/\/(create|\d+\/edit)(\/.*)?(\?.*)?$/.test(window.location.pathname)) {
        return;
    }

    /* =========================================================================
     * STYLES
     * ========================================================================= */
    if (!document.getElementById('tms-v6')) {
        document.head.insertAdjacentHTML('beforeend', `<style id="tms-v6">

            /* Hide the original Twill vselect wrapper that we replaced */
            .vselectOuter[data-tms-replaced="1"] {
                display: none !important;
                visibility: hidden !important;
                pointer-events: none !important;
                position: absolute !important;
                width: 0 !important; height: 0 !important;
                overflow: hidden !important; opacity: 0 !important;
            }
            .tms-wrap {
                position: relative;
                display: inline-flex;
                align-items: center;
                vertical-align: middle;
                margin: -10px 2px 10px;
            }
            .tms-btn {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 5px 11px;
                border: 1px solid var(--color--border, #d4d4d4);
                border-radius: 3px;
                background: var(--color--white, #ffffff);
                color: var(--color--text, #1a1a1a);
                font-size: 13px;
                font-family: inherit;
                line-height: 1.5;
                cursor: pointer;
                white-space: nowrap;
                transition: border-color .15s, background .15s, color .15s;
                user-select: none;
                min-width: 90px;
            }
            .tms-btn:hover {
                border-color: var(--color--text, #333);
                background: var(--color--light, #f5f5f5);
            }
            .tms-btn--active {
                border-color: var(--color--primary, #333);
                color: var(--color--primary, #333);
                background: var(--color--primary-xlight, #eff6ff);
            }
            .tms-caret {
                margin-left: auto;
                width: 0; height: 0;
                border-left: 4px solid transparent;
                border-right: 4px solid transparent;
                border-top: 5px solid currentColor;
                opacity: .55;
                flex-shrink: 0;
                transition: transform .15s;
            }
            .tms-btn--open .tms-caret { transform: rotate(180deg); }
            .tms-panel {
                display: none;
                flex-direction: column;
                position: absolute;
                top: calc(100% + 5px);
                left: 0;
                z-index: 99999;
                min-width: 240px;
                max-height: 380px;
                background: var(--color--white, #fff);
                border: 1px solid var(--color--border, #d4d4d4);
                border-radius: 4px;
                box-shadow: 0 8px 28px rgba(0,0,0,.13), 0 1px 5px rgba(0,0,0,.07);
                overflow: hidden;
            }
            .tms-panel--open { display: flex; }
            .tms-options {
                overflow-y: auto;
                max-height: 290px;
                padding: 4px 0;
            }
            .tms-empty {
                padding: 10px 14px;
                font-size: 13px;
                color: var(--color--text-secondary, #888);
                font-style: italic;
            }
            .tms-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 7px 13px;
                cursor: pointer;
                font-size: 13px;
                font-family: inherit;
                color: var(--color--text, #1a1a1a);
                user-select: none;
                transition: background .08s;
            }
            .tms-item:hover { background: var(--color--light, #f5f5f5); }
            .tms-item:focus-visible {
                outline: 2px solid var(--color--primary, #333);
                outline-offset: -2px;
            }
            .tms-chk {
                flex-shrink: 0;
                width: 15px; height: 15px;
                border: 1.5px solid var(--color--border, #bbb);
                border-radius: 3px;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background .12s, border-color .12s;
                pointer-events: none;
            }
            .tms-item--on .tms-chk {
                background: var(--color--primary, #333);
                border-color: var(--color--primary, #333);
            }
            .tms-chk svg {
                display: none;
                width: 9px; height: 9px;
                overflow: visible;
                pointer-events: none;
            }
            .tms-item--on .tms-chk svg { display: block; }
            .tms-chk svg polyline {
                fill: none; stroke: #fff;
                stroke-width: 2.5;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
            .tms-item__lbl { flex: 1; line-height: 1.4; }
            .tms-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 7px 13px;
                border-top: 1px solid var(--color--border, #e5e7eb);
                background: var(--color--lightest, #fafafa);
                flex-shrink: 0;
            }
            .tms-act {
                padding: 3px 14px;
                border-radius: 3px;
                font-size: 12px;
                font-family: inherit;
                cursor: pointer;
                border: 1px solid transparent;
                line-height: 1.6;
                transition: background .12s, color .12s;
            }
            .tms-act-clear {
                background: transparent;
                color: var(--color--text-secondary, #666);
                border-color: var(--color--border, #d4d4d4);
            }
            .tms-act-clear:hover { background: var(--color--light, #efefef); }
            .tms-act-apply {
                background: var(--color--primary, #333);
                color: #fff;
                border-color: var(--color--primary, #333);
            }
            .tms-act-apply:hover { background: var(--color--primary-dark, #333); }
            .filter__more .button.button--ghost {display:none !important;}

        </style>`);
    }

    function readFilter() {
        const raw = new URLSearchParams(window.location.search).get('filter');
        if (!raw) return {};
        try { return JSON.parse(raw); } catch { return {}; }
    }

    function readSelected(queryString) {
        const v = readFilter()[queryString];
        if (v === undefined || v === null || v === 'all' || v === '') return [];
        return (Array.isArray(v) ? v : [String(v)]).map(String).filter(Boolean);
    }

    /**
     * Returns true if the URL looks like a Twill listing JSON request
     * (same-origin, path starts with the CMS admin prefix).
     */
    function isTwillListingRequest(url) {
        try {
            const u = new URL(url, window.location.origin);
            if (u.origin !== window.location.origin) return false;
            const adminBase = '/' + (window.TwillAdminPath || 'cms') + '/';
            return u.pathname.startsWith(adminBase);
        } catch {
            return false;
        }
    }

    /**
     * Given a Twill listing AJAX request URL, return a new URL with any active
     * multiselect values from the current page URL merged into its filter param.
     *
     * If no multiselect filters are active the original URL is returned unchanged.
     */
    function injectMultiSelectIntoUrl(requestUrl) {
        const pageFilter = readFilter();
        const active = {};

        for (const qs of Object.keys(CONFIG)) {
            const v = pageFilter[qs];
            if (v === undefined || v === null) continue;
            const arr  = Array.isArray(v) ? v : [String(v)];
            const clean = arr.map(String).filter(x => x && x !== 'all');
            if (clean.length) {
                active[qs] = clean.length === 1 ? clean[0] : clean;
            }
        }

        if (!Object.keys(active).length) return requestUrl; // nothing to inject

        try {
            const u = new URL(requestUrl, window.location.origin);

            // Parse (or start fresh) the filter JSON already in the AJAX URL
            let f = {};
            try { f = JSON.parse(u.searchParams.get('filter') || '{}'); } catch {}

            // Merge: our multiselect values take precedence over whatever Vue sent
            Object.assign(f, active);
            u.searchParams.set('filter', JSON.stringify(f));
            return u.toString();
        } catch {
            return requestUrl; // malformed URL — leave it untouched
        }
    }

    function patchNavCount(count) {
        document.querySelectorAll('.secondarynav__number').forEach(el => {
            el.textContent = '(' + count + ')';
        });
    }

    function extractCount(json) {
        if (!json || typeof json !== 'object') return null;
        const candidates = [
            json.count,
            json.tableData?.count,
            json.data?.total,
            json.total,
            json.meta?.total,
        ];
        for (const v of candidates) {
            const n = parseInt(v, 10);
            if (!isNaN(n)) return n;
        }
        return null;
    }

    const _fetch = window.fetch;
    window.fetch = function (input, init) {
        let url = (typeof input === 'string') ? input : (input?.url ?? '');

        if (isTwillListingRequest(url)) {
            const injected = injectMultiSelectIntoUrl(url);
            if (injected !== url) {
                input = typeof input === 'string'
                    ? injected
                    : new Request(injected, input);
                url = injected;
            }
        }

        return _fetch.call(this, input, init).then(response => {
            if (!isTwillListingRequest(url)) return response;
            const ct = response.headers.get('content-type') || '';
            if (!ct.includes('application/json')) return response;

            return response.clone().json().then(json => {
                const count = extractCount(json);
                if (count !== null) patchNavCount(count);
                return response;
            }).catch(() => response);
        });
    };

    const _XHROpen = XMLHttpRequest.prototype.open;
    const _XHRSend = XMLHttpRequest.prototype.send;

    XMLHttpRequest.prototype.open = function (method, url, ...rest) {
        if (isTwillListingRequest(url)) {
            url = injectMultiSelectIntoUrl(url);
        }
        this._tmsUrl = url;
        return _XHROpen.call(this, method, url, ...rest);
    };

    XMLHttpRequest.prototype.send = function () {
        if (this._tmsUrl && isTwillListingRequest(this._tmsUrl)) {
            this.addEventListener('load', function () {
                try {
                    const ct = this.getResponseHeader('content-type') || '';
                    if (!ct.includes('application/json')) return;
                    const json = JSON.parse(this.responseText);
                    const count = extractCount(json);
                    if (count !== null) patchNavCount(count);
                } catch { /* non-JSON response — ignore */ }
            });
        }
        return _XHRSend.apply(this, arguments);
    };

    function fetchInitialCount() {
        const filterParam = new URLSearchParams(window.location.search).get('filter');
        if (!filterParam) return;

        try {
            const f = JSON.parse(filterParam);
            const hasActiveFilter = Object.keys(CONFIG).some(qs => {
                const v = f[qs];
                return v && v !== 'all' && (!Array.isArray(v) || v.length > 0);
            });
            if (!hasActiveFilter) return;
        } catch { return; }

        fetch(window.location.href, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        })
        .then(r => r.json())
        .then(json => {
            const count = extractCount(json);
            if (count !== null) patchNavCount(count);
        })
        .catch(() => {});
    }

    function applyFilter(queryString, values) {
        const f = readFilter();

        if (!values.length) {
            delete f[queryString];
        } else {
            f[queryString] = values.length === 1 ? values[0] : values;
        }

        const p = new URLSearchParams(window.location.search);
        p.set('filter', JSON.stringify(f));
        p.delete('page');
        p.delete('offset');   // FIX: also reset offset so page 1 is shown
        window.location.href = window.location.pathname + '?' + p.toString();
    }

    function makeTick() {
        const ns  = 'http://www.w3.org/2000/svg';
        const svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 10 10');
        const poly = document.createElementNS(ns, 'polyline');
        poly.setAttribute('points', '1,5.5 3.5,8 9,2');
        svg.appendChild(poly);
        return svg;
    }

    function buildDropdown(queryString, label, options, selectedValues) {
        const sel = new Set(selectedValues);

        const wrap = document.createElement('div');
        wrap.className = 'tms-wrap';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'tms-btn';
        btn.setAttribute('aria-haspopup', 'listbox');
        btn.setAttribute('aria-expanded', 'false');

        const btnText = document.createElement('span');
        const caret   = document.createElement('span');
        caret.className = 'tms-caret';
        caret.setAttribute('aria-hidden', 'true');
        btn.appendChild(btnText);
        btn.appendChild(caret);

        const panel = document.createElement('div');
        panel.className = 'tms-panel';
        panel.setAttribute('role', 'listbox');
        panel.setAttribute('aria-multiselectable', 'true');
        panel.setAttribute('aria-label', label);

        const optList = document.createElement('div');
        optList.className = 'tms-options';

        if (!options.length) {
            const empty = document.createElement('div');
            empty.className = 'tms-empty';
            empty.textContent = 'No options available';
            optList.appendChild(empty);
        } else {
            options.forEach(({ value, label: optLabel }) => {
                const v   = String(value);
                const row = document.createElement('div');
                row.className = 'tms-item' + (sel.has(v) ? ' tms-item--on' : '');
                row.dataset.val = v;
                row.setAttribute('role', 'option');
                row.setAttribute('aria-selected', String(sel.has(v)));
                row.setAttribute('tabindex', '0');

                const box = document.createElement('div');
                box.className = 'tms-chk';
                box.appendChild(makeTick());

                const lbl = document.createElement('span');
                lbl.className = 'tms-item__lbl';
                lbl.textContent = optLabel;

                row.appendChild(box);
                row.appendChild(lbl);

                const toggle = () => {
                    if (sel.has(v)) {
                        sel.delete(v);
                        row.classList.remove('tms-item--on');
                        row.setAttribute('aria-selected', 'false');
                    } else {
                        sel.add(v);
                        row.classList.add('tms-item--on');
                        row.setAttribute('aria-selected', 'true');
                    }
                    refreshBtn();
                };

                row.addEventListener('click', toggle);
                row.addEventListener('keydown', e => {
                    if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); toggle(); }
                });

                optList.appendChild(row);
            });
        }

        const footer   = document.createElement('div');
        footer.className = 'tms-footer';

        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'tms-act tms-act-clear';
        clearBtn.textContent = 'Clear';

        const applyBtn = document.createElement('button');
        applyBtn.type = 'button';
        applyBtn.className = 'tms-act tms-act-apply';
        applyBtn.textContent = 'Apply';

        footer.appendChild(clearBtn);
        footer.appendChild(applyBtn);
        panel.appendChild(optList);
        panel.appendChild(footer);
        wrap.appendChild(btn);
        wrap.appendChild(panel);

        function refreshBtn() {
            if (!sel.size) {
                btnText.textContent = label;
                btn.classList.remove('tms-btn--active');
            } else {
                const names = Array.from(
                    optList.querySelectorAll('.tms-item--on .tms-item__lbl')
                ).map(el => el.textContent);
                btnText.textContent = label + ': '
                    + (names.length <= 2 ? names.join(', ') : names.length + ' selected');
                btn.classList.add('tms-btn--active');
            }
        }

        function openPanel()  {
            panel.classList.add('tms-panel--open');
            btn.classList.add('tms-btn--open');
            btn.setAttribute('aria-expanded', 'true');
        }
        function closePanel() {
            panel.classList.remove('tms-panel--open');
            btn.classList.remove('tms-btn--open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', e => {
            e.stopPropagation();
            panel.classList.contains('tms-panel--open') ? closePanel() : openPanel();
        });
        panel.addEventListener('click', e => e.stopPropagation());
        document.addEventListener('click', closePanel);

        applyBtn.addEventListener('click', () => {
            closePanel();
            applyFilter(queryString, Array.from(sel));
        });

        clearBtn.addEventListener('click', () => {
            sel.clear();
            optList.querySelectorAll('.tms-item').forEach(r => {
                r.classList.remove('tms-item--on');
                r.setAttribute('aria-selected', 'false');
            });
            refreshBtn();
            closePanel();
            applyFilter(queryString, []);
        });

        wrap.addEventListener('keydown', e => { if (e.key === 'Escape') closePanel(); });

        refreshBtn();
        return wrap;
    }

    function findContainer(queryString) {
        const candidates = [
            `input-wrapper-${CSS.escape(queryString + 'List')}`,  // what Twill actually renders
            `input-wrapper-${CSS.escape(queryString)}`,            // fallback / future-proofing
        ];

        let inner = null;
        for (const cls of candidates) {
            const el = document.querySelector('.' + cls);
            if (el) { inner = el; break; }
        }
        if (!inner) return null;

        if (
            inner.closest('[data-behavior="form"]') ||
            inner.closest('form[action]')
        ) return null;

        return inner.closest('.vselectOuter') || inner.parentElement;
    }

    const enhanced = new WeakSet();

    function enhanceAll() {
        for (const [queryString, cfg] of Object.entries(CONFIG)) {
            const { label, options } = cfg;
            if (!label || !options?.length) continue;

            const container = findContainer(queryString);
            if (!container || enhanced.has(container)) continue;

            enhanced.add(container);

            const dropdown = buildDropdown(
                queryString, label, options, readSelected(queryString)
            );
            container.parentNode.insertBefore(dropdown, container);
            container.setAttribute('data-tms-replaced', '1');
        }
    }

    function handleCombinedFilterClick(e) {
        const btn = e.target.closest('.filter__more .button.button--ghost');
        if (!btn) return;

        const container = btn.closest('.filter__moreInner');
        if (!container) return;

        const root = btn.closest('.filter__more');
        if (!root) return;

        const text = (btn.textContent || '').trim().toLowerCase();

        // Prevent Twill from also handling it
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation?.();

        const isApply = text.includes('apply');
        const isClear = text.includes('clear');

        if (isApply) {
            // Let Vue finish updating state first (critical)
            setTimeout(() => {
                const p = new URLSearchParams(window.location.search);

                // IMPORTANT:
                // we DON'T rebuild state manually here
                // we rely on your fetch/XHR injector below
                p.delete('page');
                p.delete('offset');

                window.location.href =
                    window.location.pathname + '?' + p.toString();
            }, 0);
        }

        if (isClear) {
            setTimeout(() => {
                const p = new URLSearchParams(window.location.search);
                p.delete('filter');
                p.delete('page');
                p.delete('offset');

                window.location.href =
                    window.location.pathname + '?' + p.toString();
            }, 0);
        }
    }

    /* =========================================================================
     * BOOT
     * ========================================================================= */

    if (!window.TwillAdminPath) {
        const segments = window.location.pathname.split('/').filter(Boolean);
        window.TwillAdminPath = segments[0] || 'cms';
    }

    let debounce;
    new MutationObserver(() => {
        clearTimeout(debounce);
        debounce = setTimeout(enhanceAll, 100);
    }).observe(document.body, { childList: true, subtree: true });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            enhanceAll();
            fetchInitialCount();
            document.addEventListener('click', handleCombinedFilterClick, true);
        });
    } else {
        enhanceAll();
        fetchInitialCount();
        document.addEventListener('click', handleCombinedFilterClick, true);
    }

})();
