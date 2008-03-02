<xbk:tmpl name="content">
<xbk:css name="superadmin" order="1" />
<table border="0" cellspacing="0" cellpadding="0" align="center" width="700" height="500" class="superadmin-maintable">
<tr>
<td class="superadmin-ttitle" height="100" valign="top" align="left">
xBk
</td>
<td class="superadmin-trightitle" valign="top" align="right">
{LANG_SUPERADMIN}<br />
<a href="{LOGOUT_LINK}">{LANG_LOGOUT}</a>
</td>
</tr>
<tr>
<td align="center" valign="top" height="30" colspan="2">

<xbk:tmpl name="menu" type="OddEven">
<xbk:sub condition="__default">
<xbk:tmpl name="menu_item" varscope="menu" type="condition" conditionVar="selected">
<xbk:sub condition="true">
<a href="{LINK}" class="superadmin-selected">{TEXT}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
</xbk:sub>
<xbk:sub condition="false">
<a href="{LINK}">{TEXT}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
</xbk:sub>
</xbk:tmpl>
</xbk:sub>
<xbk:sub condition="__last">
<xbk:tmpl name="menu_item_selected" varscope="menu" type="condition" conditionVar="selected">
<xbk:sub condition="true">
<a href="{LINK}" class="superadmin-selected">{TEXT}</a>
</xbk:sub>
<xbk:sub condition="false">
<a href="{LINK}">{TEXT}</a>
</xbk:sub>
</xbk:tmpl>
</xbk:sub>
</xbk:tmpl>

</td>
</tr>
<tr>
<td class="superadmin-content" valign="top" align="left" colspan="2">
{CONTENT}
</td>
</tr>
<tr>
<td class="superadmin-version" valign="bottom" align="right" colspan="2">
{LANG_SUPERADMIN_VERSION} {VERSION}
</td>
</tr>
</table>
</xbk:tmpl>