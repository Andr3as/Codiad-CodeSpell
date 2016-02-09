<!--
    Copyright (c) Andr3as, distributed
    as-is and without warranty under the MIT License. 
    See http://opensource.org/licenses/MIT for more information.
    This information must remain intact.
-->
<div class="codespell">
    <label>Spelling mistakes</label>
    <hr>
    <table>
        <thead>
            <td style="width: 75%">mistake</td>
            <td style="width: 25%">line(s)</td>
        </thead>
        <tbody></tbody>
    </table>
    
    <button onclick="codiad.modal.unload(); return false;">Close</button>
    
    <script>
        var _this = codiad.CodeSpell;
        var lines, mistake, word;
        for (var i = 0; i < _this.mistakes.length; i++) {
            mistake = _this.mistakes[i];
            word    = mistake.word;
            lines   = mistake.lines.join(", ");
            $('.codespell table tbody').append("<tr data-mistake='" + JSON.stringify(mistake) + "'><td>" + word + "</td><td>" + lines + "</td></tr>");
        }
        
        $('.codespell table tr[data-mistake]').click(function(){
            console.log(this, $(this).attr('data-mistake'));
            codiad.CodeSpell.selectLines(JSON.parse($(this).attr('data-mistake')));
        });
    </script>
</div>