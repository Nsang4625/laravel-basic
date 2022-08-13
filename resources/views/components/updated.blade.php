
<p class="text-muted">
    {{ empty(trim($slot)) ?? 'Added': $slot}} {{ $date->diffForHumans() }}
    by {{ $name }}
</p>