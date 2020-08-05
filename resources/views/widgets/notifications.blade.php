<!-- Notifications Dropdown Menu -->

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ $notifications['newNoti']->count() }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">{{ __('noticenter.title') }} </span>
        <div class="dropdown-divider"></div>
        @foreach ($notifications['newNoti'] as $item)
            <a href="{{ route('home') }}" class="dropdown-item font-weight-bold">
                <img src="{{ $item->data['user']['avatar'] }}" width="32px" class="img-thumbnail img-circle" />
                {{ $item->data['data'] }}
                <span class="float-right text-muted text-sm">{{ $item->created_at->diffForHumans() }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @endforeach
        @foreach ($notifications['oldNoti'] as $item)
            <a href="{{ route('home') }}" class="dropdown-item">
                <img src="{{ $item->data['user']['avatar'] }}" width="32px" class="img-thumbnail img-circle" />
                {{ $item->data['data'] }}
                <span class="float-right text-muted text-sm">{{ $item->created_at->diffForHumans() }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @endforeach

        <a href="{{ route('home') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
</li>
