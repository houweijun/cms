<table class="footable table table-stripped toggle-arrow-tiny table-hover" id="@yield('table-id')">
    <thead>
    {{$thead}}
    </thead>
    <tbody>
    {{$tbody}}
    </tbody>
    <tfoot>
    <tr>
        <td colspan="15">
            @yield('tfoot')
        </td>
    </tr>
    </tfoot>
</table>