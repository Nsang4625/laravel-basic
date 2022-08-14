<div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
    <p class="text-muted">
        {{ empty(trim($slot)) ? 'Added': $slot}} {{ $date->diffForHumans() }}
        @if (isset($name))
            By {{ $name }}
        @endif
    </p>
</div>