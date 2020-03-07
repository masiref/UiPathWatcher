<section class="hero is-info welcome is-small">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Hello, {{ Auth::user()->name }}.
            </h1>
            <h2 class="subtitle">{{ $message ?? "Let's watch your bots!" }}</h2>
        </div>
    </div>
</section>