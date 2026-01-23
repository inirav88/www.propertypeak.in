<style>
    .mobile-sticky-footer {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        padding: 10px 15px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 999;
        justify-content: space-between;
        gap: 10px;
    }

    .mobile-sticky-footer .btn-sticky {
        flex: 1;
        text-align: center;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-sticky.whatsapp {
        background-color: #25d366;
        color: white;
    }

    .btn-sticky.call {
        background-color: var(--primary-color, #0b2132);
        color: white;
    }

    @media (max-width: 768px) {
        .mobile-sticky-footer {
            display: flex;
        }

        /* Add padding to body to prevent overlap */
        body {
            padding-bottom: 70px;
        }
    }
</style>

<div class="mobile-sticky-footer">
    @if($model->author_mobile || theme_option('hotline'))
        <a href="tel:{{ $model->author_mobile ?: theme_option('hotline') }}" class="btn-sticky call">
            <x-core::icon name="ti ti-phone" />
            {{ __('Call Now') }}
        </a>
    @endif

    <a href="https://wa.me/{{ $model->author_mobile ?: '918320070252' }}?text={{ urlencode(__('I am interested in :name. :url', ['name' => $model->name, 'url' => $model->url])) }}"
        class="btn-sticky whatsapp" target="_blank">
        <x-core::icon name="ti ti-brand-whatsapp" />
        {{ __('WhatsApp') }}
    </a>
</div>