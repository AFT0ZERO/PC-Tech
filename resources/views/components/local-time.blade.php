@if($date)
<span class="local-time" data-utc="{{ $iso8601() }}"@if($dateOnly) data-date-only @endif>{{ $fallback() }}</span>
@endif
