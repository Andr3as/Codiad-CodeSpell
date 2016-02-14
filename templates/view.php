<!--
    Copyright (c) Andr3as, distributed
    as-is and without warranty under the MIT License. 
    See http://opensource.org/licenses/MIT for more information.
    This information must remain intact.
-->
<div class="codespell">
    <label><?php i18n("Spelling mistakes"); ?></label>
    <hr>
    <table>
        <thead>
            <td style="width: 40%"><?php i18n("mistake"); ?></td>
            <td style="width: 15%"><?php i18n("line(s)"); ?></td>
            <td style="width: 5%"><?php i18n("ignore"); ?>*</td>
            <td style="width: 40%"><?php i18n("suggestions"); ?></td>
        </thead>
        <tbody></tbody>
    </table>
    *: <?php i18n("Ignore adds word to your own dictionary"); ?><br>
    
    <button onclick="codiad.modal.unload(); return false;"><?php i18n("Close"); ?></button>
    
    <script>
        var _this = codiad.CodeSpell;
        var lines, mistake, word;
        for (var i = 0; i < _this.mistakes.length; i++) {
            mistake = _this.mistakes[i];
            word    = mistake.word;
            lines   = mistake.lines.join(", ");
            $('.codespell table tbody').append("<tr data-mistake='" + JSON.stringify(mistake) + "'><td data-action=\"show\">" + word + "</td><td data-action=\"show\">" + lines + "</td><td><i class=\"icon-plus\" data-action=\"add\"></i></td><td data-word=\"" + word + "\"></td></tr>");
        }
        
        $('.codespell table [data-action="show"]').click(function(){
            var mistake = JSON.parse($($(this).parent()).attr('data-mistake'));
            codiad.CodeSpell.selectLines(mistake);
        });
        
        $('.codespell table [data-action="add"]').click(function(){
            var mistake = JSON.parse($($($(this).parent()).parent()).attr('data-mistake'));
            codiad.CodeSpell.addMistake(mistake);
            $($($(this).parent()).parent()).remove();
        });
    </script>
</div>