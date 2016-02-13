<!--
    Copyright (c) Andr3as, distributed
    as-is and without warranty under the MIT License. 
    See http://opensource.org/licenses/MIT for more information.
    This information must remain intact.
-->
<label><span class="icon-doc-text big-icon"></span>CodeSpell</label>
<hr>
<label></label>
<table class="settings">
    <tr>
        <td><?php i18n("Dictionary Language"); ?></td>
        <td>
            <select class="setting" data-setting="codiad.plugin.codespell.language">
                <option value="en_US" default>English</option>
                <option value="de_DE">Deutsch</option>
                <option value="fr_FR">Français</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php i18n("Display errors after saving"); ?></td>
        <td>
            <select class="setting" data-setting="codiad.plugin.codespell.displayOnSave">
                <option value="false" default><?php i18n("No"); ?></option>
                <option value="true"><?php i18n("Yes"); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php i18n("Delete complete user dictionary?"); ?></td>
        <td>
            <button onclick="codiad.CodeSpell.deleteUserDictionary(); return false;">
                <?php i18n("Delete"); ?>
            </button>
        </td>
    </tr>
    
</table>