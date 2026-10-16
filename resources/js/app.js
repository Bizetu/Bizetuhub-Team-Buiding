import './bootstrap';

const menuToggle = document.querySelector('#menu-toggle');
const mobileNavigation = document.querySelector('#mobile-navigation');

if (menuToggle instanceof HTMLButtonElement && mobileNavigation instanceof HTMLElement) {
    menuToggle.addEventListener('click', () => {
        const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!expanded));
        mobileNavigation.classList.toggle('hidden', expanded);
        mobileNavigation.classList.toggle('block', !expanded);
    });

    mobileNavigation.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            menuToggle.setAttribute('aria-expanded', 'false');
            mobileNavigation.classList.add('hidden');
            mobileNavigation.classList.remove('block');
        });
    });
}

const homeSlider = document.querySelector('[data-home-slider]');

if (homeSlider instanceof HTMLElement) {
    const slides = Array.from(homeSlider.querySelectorAll('[data-home-slide]'));
    const indicators = Array.from(homeSlider.querySelectorAll('[data-slide-to]'));
    const currentIndicator = homeSlider.querySelector('[data-slide-current]');
    const muteButton = homeSlider.querySelector('[data-slide-mute]');
    const togglePlayButton = homeSlider.querySelector('[data-slide-toggle-play]');

    if (slides.length > 1) {
        let currentIndex = 0;
        let autoplayTimer;
        let slideshowPaused = false;

        const showSlide = (index) => {
            currentIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                const active = slideIndex === currentIndex;
                slide.classList.toggle('hidden', !active);
                slide.setAttribute('aria-hidden', String(!active));
                slide.classList.toggle('home-slide-enter', active);

                const video = slide.querySelector('video');
                if (video instanceof HTMLVideoElement) {
                    if (active && !slideshowPaused && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        video.play().catch(() => {});
                    } else {
                        video.pause();
                    }
                }
            });

            const activeVideo = slides[currentIndex].querySelector('video');
            if (muteButton instanceof HTMLButtonElement) {
                muteButton.disabled = !(activeVideo instanceof HTMLVideoElement);
                muteButton.setAttribute('aria-label', activeVideo instanceof HTMLVideoElement && !activeVideo.muted ? 'Mute animation' : 'Unmute animation');
                muteButton.querySelector('[data-icon-muted]')?.classList.toggle('hidden', activeVideo instanceof HTMLVideoElement && !activeVideo.muted);
                muteButton.querySelector('[data-icon-unmuted]')?.classList.toggle('hidden', !(activeVideo instanceof HTMLVideoElement && !activeVideo.muted));
            }

            indicators.forEach((indicator, indicatorIndex) => {
                const active = indicatorIndex === currentIndex;
                indicator.setAttribute('aria-pressed', String(active));
                indicator.classList.toggle('w-8', active);
                indicator.classList.toggle('bg-amber-400', active);
                indicator.classList.toggle('w-2.5', !active);
                indicator.classList.toggle('bg-transparent', !active);
            });

            if (currentIndicator instanceof HTMLElement) {
                currentIndicator.textContent = String(currentIndex + 1).padStart(2, '0');
            }
        };

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showSlide(index));
        });

        homeSlider.querySelector('[data-slide-previous]')?.addEventListener('click', () => {
            showSlide(currentIndex - 1);
        });
        homeSlider.querySelector('[data-slide-next]')?.addEventListener('click', () => {
            showSlide(currentIndex + 1);
        });

        muteButton?.addEventListener('click', () => {
            const video = slides[currentIndex].querySelector('video');
            if (video instanceof HTMLVideoElement) {
                video.muted = !video.muted;
                showSlide(currentIndex);
            }
        });

        togglePlayButton?.addEventListener('click', () => {
            slideshowPaused = !slideshowPaused;
            togglePlayButton.setAttribute('aria-pressed', String(slideshowPaused));
            togglePlayButton.setAttribute('aria-label', slideshowPaused ? 'Resume slideshow' : 'Pause slideshow');
            togglePlayButton.querySelector('[data-icon-pause]')?.classList.toggle('hidden', slideshowPaused);
            togglePlayButton.querySelector('[data-icon-play]')?.classList.toggle('hidden', !slideshowPaused);
            showSlide(currentIndex);
            if (slideshowPaused) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        const stopAutoplay = () => {
            window.clearInterval(autoplayTimer);
        };
        const startAutoplay = () => {
            stopAutoplay();

            if (
                homeSlider.dataset.autoplay === 'true'
                && !slideshowPaused
                && !window.matchMedia('(prefers-reduced-motion: reduce)').matches
                && document.visibilityState === 'visible'
            ) {
                autoplayTimer = window.setInterval(() => showSlide(currentIndex + 1), 6000);
            }
        };

        homeSlider.addEventListener('mouseenter', stopAutoplay);
        homeSlider.addEventListener('mouseleave', startAutoplay);
        homeSlider.addEventListener('focusin', stopAutoplay);
        homeSlider.addEventListener('focusout', (event) => {
            if (!homeSlider.contains(event.relatedTarget)) {
                startAutoplay();
            }
        });
        document.addEventListener('visibilitychange', startAutoplay);

        showSlide(0);
        startAutoplay();
    } else {
        const video = slides[0]?.querySelector('video');
        if (video instanceof HTMLVideoElement && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            video.play().catch(() => {});
        }
    }
}

const servicesCatalogue = document.querySelector('[data-services-catalogue]');

if (servicesCatalogue instanceof HTMLElement) {
    const overview = servicesCatalogue.querySelector('[data-services-view="overview"]');
    const detail = servicesCatalogue.querySelector('[data-services-view="detail"]');
    const detailPanel = servicesCatalogue.querySelector('#service-detail-panel');
    const templates = Array.from(servicesCatalogue.querySelectorAll('template[data-service-detail-template]'));
    const categoryFilterSelect = servicesCatalogue.querySelector('[data-service-category-filter-select]');
    const categorySearchButton = servicesCatalogue.querySelector('[data-service-category-search]');
    const categoryCards = Array.from(servicesCatalogue.querySelectorAll('[data-service-card]'));
    const pagination = servicesCatalogue.querySelector('[data-service-pagination]');
    const cardsPerPage = 9;
    let currentPage = 1;

    const renderPagination = (filteredCards) => {
        if (!(pagination instanceof HTMLElement)) {
            return;
        }

        pagination.replaceChildren();
        const pageCount = Math.ceil(filteredCards.length / cardsPerPage);

        if (pageCount <= 1) {
            return;
        }

        const addPageButton = (label, page, disabled = false, current = false) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = label;
            button.disabled = disabled;
            button.setAttribute('aria-label', `Go to page ${page}`);
            if (current) {
                button.setAttribute('aria-current', 'page');
            }
            button.className = current
                ? 'rounded-none bg-amber-300 px-3 py-2 text-xs font-extrabold text-emerald-950'
                : 'rounded-none border border-amber-300/60 px-3 py-2 text-xs font-extrabold text-amber-200 transition hover:bg-amber-300/15 disabled:cursor-not-allowed disabled:opacity-40';
            button.addEventListener('click', () => {
                currentPage = page;
                renderServicePage(filteredCards);
            });
            pagination.append(button);
        };

        addPageButton('Previous', currentPage - 1, currentPage === 1);
        for (let page = 1; page <= pageCount; page += 1) {
            addPageButton(String(page), page, false, page === currentPage);
        }
        addPageButton('Next', currentPage + 1, currentPage === pageCount);
    };

    const renderServicePage = (filteredCards) => {
        const pageCount = Math.max(1, Math.ceil(filteredCards.length / cardsPerPage));
        currentPage = Math.min(currentPage, pageCount);
        const firstCard = (currentPage - 1) * cardsPerPage;
        const visibleCards = new Set(filteredCards.slice(firstCard, firstCard + cardsPerPage));

        categoryCards.forEach((card) => {
            card.hidden = !visibleCards.has(card);
        });
        renderPagination(filteredCards);
    };

    const filterServicesByCategory = (category, resetPage = true) => {
        const selectedCategory = category || 'all';
        const filteredCards = selectedCategory === 'all'
            ? categoryCards
            : categoryCards.filter((card) => card.dataset.serviceCategory === selectedCategory);

        if (resetPage) {
            currentPage = 1;
        }
        renderServicePage(filteredCards);

        if (categoryFilterSelect instanceof HTMLSelectElement) {
            categoryFilterSelect.value = selectedCategory === 'all' ? '' : selectedCategory;
        }
    };

    categorySearchButton?.addEventListener('click', () => {
        if (categoryFilterSelect instanceof HTMLSelectElement) {
            filterServicesByCategory(categoryFilterSelect.value);
        }
    });

    filterServicesByCategory('all', false);

    const showOverview = ({ pushHistory = false, focus = false } = {}) => {
        if (!(overview instanceof HTMLElement) || !(detail instanceof HTMLElement)) {
            return;
        }

        overview.hidden = false;
        detail.hidden = true;

        if (pushHistory) {
            const catalogueUrl = detail.querySelector('[data-service-back]')?.href ?? '/services';
            window.history.pushState({}, '', catalogueUrl);
        }
        if (focus) {
            overview.querySelector('h1')?.focus();
        }
    };

    const showService = (slug, { url, pushHistory = false, focus = true } = {}) => {
        if (!(overview instanceof HTMLElement) || !(detail instanceof HTMLElement) || !(detailPanel instanceof HTMLElement)) {
            return;
        }

        const template = templates.find((item) => item.dataset.serviceDetailTemplate === slug);

        if (!(template instanceof HTMLTemplateElement)) {
            return;
        }

        detailPanel.replaceChildren(template.content.cloneNode(true));
        overview.hidden = true;
        detail.hidden = false;

        if (pushHistory && url) {
            window.history.pushState({ serviceSlug: slug }, '', url);
        }
        if (focus) {
            detail.querySelector('h1')?.focus();
        }
    };

    servicesCatalogue.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const backLink = event.target.closest('[data-service-back]');
        if (backLink instanceof HTMLAnchorElement) {
            event.preventDefault();
            showOverview({ pushHistory: true, focus: true });
            return;
        }

        const serviceLink = event.target.closest('[data-service-open]');
        if (!(serviceLink instanceof HTMLAnchorElement)) {
            return;
        }

        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        event.preventDefault();
        showService(serviceLink.dataset.serviceOpen, { url: serviceLink.href, pushHistory: true });
    });

    window.addEventListener('popstate', () => {
        const path = window.location.pathname.replace(/\/+$/, '');
        const servicePrefix = '/services/';

        if (path.startsWith(servicePrefix)) {
            showService(decodeURIComponent(path.slice(servicePrefix.length)), { focus: false });
        } else {
            showOverview();
        }
    });
}

const proposalDialog = document.querySelector('[data-service-proposal-dialog]');

if (proposalDialog instanceof HTMLDialogElement) {
    const programmeName = proposalDialog.querySelector('[data-proposal-programme]');
    const bookingLink = proposalDialog.querySelector('[data-service-booking-link]');

    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const trigger = event.target.closest('[data-service-proposal]');
        if (!(trigger instanceof HTMLButtonElement)) {
            return;
        }

        if (programmeName instanceof HTMLElement) {
            programmeName.textContent = trigger.dataset.programmeTitle ?? '';
        }
        if (bookingLink instanceof HTMLAnchorElement && trigger.dataset.bookingUrl) {
            bookingLink.href = trigger.dataset.bookingUrl;
        }

        proposalDialog.showModal();
    });

    proposalDialog.querySelector('[data-service-proposal-close]')?.addEventListener('click', () => {
        proposalDialog.close();
    });

    proposalDialog.addEventListener('click', (event) => {
        if (event.target === proposalDialog) {
            proposalDialog.close();
        }
    });
}
