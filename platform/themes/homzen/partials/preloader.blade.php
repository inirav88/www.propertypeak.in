<div class="preload preload-container">
    @if ($icon = theme_option('preloader_icon'))
        <div class="preloader-icon-wrapper">
            {!! RvMedia::image($icon, __('Preloader icon'), attributes: ['class' => 'preloader-icon'], lazy: false) !!}
        </div>
    @else
        <div class="boxes">
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    @endif
</div>

<script>
    (() => {
        const hidePreloader = () => {
            document.querySelectorAll('.preload').forEach((el) => {
                el.style.opacity = '0'
                el.style.pointerEvents = 'none'

                window.setTimeout(() => el.remove(), 300)
            })
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            window.setTimeout(hidePreloader, 800)
        }

        window.addEventListener('load', hidePreloader, { once: true })

        // Safety fallback: never keep users blocked by a stuck preloader.
        window.setTimeout(hidePreloader, 5000)
    })()
</script>
