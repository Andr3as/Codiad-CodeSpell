<!--
    Copyright (c) Andr3as, distributed
    as-is and without warranty under the MIT License. 
    See http://opensource.org/licenses/MIT for more information.
    This information must remain intact.
-->
<label><?php i18n("Confirm"); ?></label>
<div class="codespell-confirm">
    <?php
        echo i18n("Are you sure to replace %{1}% with %{2}%?", array(
            1 => "&ldquo;<span class=\"bold\">" . $_GET['mistake'] . "</span>&ldquo;",
            2 => "&ldquo;<span class=\"bold\">" . $_GET['suggestion'] . "</span>&ldquo;"
        ));
    ?>
</div>
<button class="btn-left" onclick="replace(); return false;"><?php i18n("Replace All"); ?></button>
<button class="btn-right" onclick="codiad.modal.unload(); return false;"><?php i18n("Cancel"); ?></button>
<script>
    function replace() {
        var mistake = "<?php echo $_GET['mistake'] ?>";
        var suggestion = "<?php echo $_GET['suggestion'] ?>";
        
        var active = codiad.editor.getActive();
        if (active == null) {
            return false;
        }
        
        active.find(mistake, {
            backwards: false,
            wrap: true,
            caseSensitive: true,
            wholeWord: true,
            regExp: false
        });
        active.replaceAll(suggestion);
        codiad.message.success(i18n("Mistake replaced"));
        codiad.CodeSpell.check(true);
    }
</script>