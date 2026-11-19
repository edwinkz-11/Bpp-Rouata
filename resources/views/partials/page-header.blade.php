<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @isset($parent)<li class="breadcrumb-item"><a href="{{ $parent[1] }}">{{ $parent[0] }}</a></li>@endisset
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        <h1>{{ $title }}</h1>
        @isset($subtitle)<p class="mb-0">{{ $subtitle }}</p>@endisset
    </div>
</section>
