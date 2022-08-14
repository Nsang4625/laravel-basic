<div>
    <!-- It is never too late to be what you might have been. - George Eliot -->
    @if (!isset($show) || $show)
        <span class="badge badge-{{ $type ?? 'success' }}">
            {{ $slot }}
        </span>
    @endif
</div>
