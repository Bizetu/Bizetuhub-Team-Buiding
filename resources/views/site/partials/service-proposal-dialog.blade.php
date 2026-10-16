<dialog class="service-proposal-dialog" data-service-proposal-dialog aria-labelledby="proposal-dialog-title">
    <div class="service-proposal-dialog__panel">
        <button type="button" class="service-proposal-dialog__close" data-service-proposal-close aria-label="Close proposal request">×</button>
        <span class="service-outcome-icon service-outcome-icon--amber" aria-hidden="true">↗</span>
        <h2 id="proposal-dialog-title" class="mt-5 text-2xl font-extrabold text-white">Request a programme proposal</h2>
        <p class="mt-2 text-sm leading-6 text-slate-300">You’re interested in <strong class="text-amber-200" data-proposal-programme></strong>. Continue to the booking form to share your team’s details and preferred timing.</p>
        <a href="{{ route('book') }}" data-service-booking-link class="service-card-link btn-shimmer mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-extrabold">
            Continue to booking <span aria-hidden="true">↗</span>
        </a>
    </div>
</dialog>
