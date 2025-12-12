<div>
    <table class="td-left" style="border-collapse:collapse;">
        <tbody>
        <tr>
            <td><strong>DEPARTMENT CHAIR'S COMMENT:</strong></td>
            <td>  {{ $exr->t_comment ?: str_repeat('__', 20) }}</td>
        </tr>
        <tr>
            <td><strong>DEAN'S COMMENT:</strong></td>
            <td>  {{ $exr->p_comment ?: str_repeat('__', 20) }}</td>
        </tr>
        <!-- <tr>
            <td><strong>NEXT TERM BEGINS:</strong></td>
            <td>{{ date('l\, jS F\, Y', strtotime($s['term_begins'])) }}</td>
        </tr> -->
        <!-- <tr>
            <td><strong>NEXT TERM FEES:</strong></td>
            <td><del style="text-decoration-style: double">N</del>{{ $s['next_term_fees_'.strtolower($ct)] }}</td>
        </tr> -->
        </tbody>
    </table>
</div>
