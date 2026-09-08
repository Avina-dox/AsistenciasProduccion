@php
    $total = count($filas);
    $i = 0;
@endphp

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
    style="border:1px solid rgba(43,32,48,0.1); border-radius:12px; overflow:hidden;">
    @foreach($filas as $label => $valor)
        @php $i++; @endphp
        <tr style="background-color: {{ $loop->even ? '#FBF8F3' : '#ffffff' }};">
            <td class="stack-cell"
                style="padding:11px 16px; font-size:11px; font-weight:600; color:#6E6274; text-transform:uppercase; letter-spacing:.4px; width:38%; vertical-align:top; {{ $i < $total ? 'border-bottom:1px solid rgba(43,32,48,0.06);' : '' }}">
                {{ $label }}
            </td>
            <td class="stack-cell"
                style="padding:11px 16px; font-size:14px; color:#2B2030; font-weight:600; vertical-align:top; {{ $i < $total ? 'border-bottom:1px solid rgba(43,32,48,0.06);' : '' }}">
                {{ $valor !== null && $valor !== '' ? $valor : '—' }}
            </td>
        </tr>
    @endforeach
</table>
