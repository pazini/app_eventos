<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'ProEventPay' || trim($slot) === appName())
<img src="{{ appUrl() }}/{{ appLogo() }}" class="logo" alt="{{ appName() }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
