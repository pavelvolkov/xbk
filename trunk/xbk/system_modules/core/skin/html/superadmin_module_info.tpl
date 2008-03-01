<xbk:tmpl name="content"><xbk:css name="superadmin_modules" />

{TEASER}

<div class="superadmin-modules-module-info-div">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<xbk:tmpl name="attr">
<tr>
<td align="left" valign="top" width="20%" class="superadmin-modules-module-info-td" nowrap="nowrap">
{NAME}
</td>
<td align="left" valign="top" width="80%" class="superadmin-modules-module-info-td">
{VALUE}
</td>
</tr>
</xbk:tmpl>

<tr>
<td align="left" valign="top" class="superadmin-modules-module-info-td" nowrap="nowrap">
{LANG_SUPERADMIN_MODULE_MENU}
</td>
<td align="left" valign="top" class="superadmin-modules-module-info-td">

<xbk:tmpl name="menu" type="OddEven">
<xbk:sub condition="__empty">
{LANG_SUPERADMIN_MODULE_MENU_EMPTY}
</xbk:sub>
<xbk:sub condition="__first">
<a href="{LINK|htmlspecialchars}">{TEXT}</a>
</xbk:sub>
<xbk:sub condition="__default">
&nbsp;|&nbsp;<a href="{LINK|htmlspecialchars}">{TEXT}</a>
</xbk:sub>
</xbk:tmpl>

</td>
</tr>

<tr>
<td align="left" valign="top" class="superadmin-modules-module-info-td" nowrap="nowrap">
{LANG_SUPERADMIN_MODULE_ACTION}
</td>
<td align="left" valign="top" class="superadmin-modules-module-info-td">

<xbk:tmpl name="actions" type="OddEven">
<xbk:sub condition="__default">
<a href="{LINK|htmlspecialchars}">{TEXT}</a>&nbsp;|&nbsp;
</xbk:sub>
<xbk:sub condition="__last">
<a href="{LINK|htmlspecialchars}">{TEXT}</a>
</xbk:sub>
</xbk:tmpl>

</td>
</tr>

{MIGRATION}

</table>

</div>
</xbk:tmpl>