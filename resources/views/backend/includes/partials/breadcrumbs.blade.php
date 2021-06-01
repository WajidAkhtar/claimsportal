<div class="c-subheader justify-content-between px-3">
  <ol class="breadcrumb border-0 m-0">
    <li class="mr-4">
      <a href="{{ url()->previous() }}" style="text-decoration: none;">
          <span>
              <i class="cil-arrow-thick-left"></i>
          </span>
      </a>
  </li>
@if (Breadcrumbs::has())
  @foreach (Breadcrumbs::current() as $crumb)
    @if ($crumb->url() && !$loop->last)
    <li class="breadcrumb-item">
      <x-utils.link :href="$crumb->url()" :text="$crumb->title()" />
    </li>
    @else
    <li class="breadcrumb-item active">
      {{ $crumb->title() }}
    </li>
    @endif
    @endforeach
    <!-- Breadcrumb Menu-->
@endif
  </ol>
</div>