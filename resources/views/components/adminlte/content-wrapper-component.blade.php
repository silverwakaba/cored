<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-{{ $col }}">
                    <h1 class="m-0">
                        @if($previous)
                            <a href="{{ $previous }}" class="btn btn-light">
                                <i class="fas fa-step-backward"></i>
                            </a>
                        @endif
                        <span>{{ $title }}</span>
                    </h1>
                </div>
                @if($breadcrumb)
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render($breadcrumb) }}
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">
                @if(session()->has('class') && session()->has('message'))
                    <div class="callout callout-{{ session()->get('class') }}">
                        <p class="m-0">{{ session()->get('message') }}</p>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </div>
    </section>
</div>