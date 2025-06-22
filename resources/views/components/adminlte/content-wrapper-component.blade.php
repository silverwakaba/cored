<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-{{ $col }}">
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
                    <div class="col-6">
                        {{ Breadcrumbs::render($breadcrumb) }}
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">
                {{ $slot }}
            </div>
        </div>
    </section>
</div>