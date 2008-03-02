<xbk:tmpl name="content">
<tr>
<td align="left" valign="top" class="superadmin-modules-module-info-td" colspan="2">

<table border="0" cellspacing="0" cellpadding="0" align="left" width="100%" class="superadmin-modules-module-migration-tbl">
<tr>
<td align="left" valign="center" style="padding-right: 10px" class="superadmin-modules-module-migration-td" width="0%" nowrap="nowrap">
<a name="migration"></a>{LANG_SUPERADMIN_MODULE_MIGRATIONS}
</td>

<td align="left" valign="center" width="100%" class="superadmin-modules-module-migration-td">

<table border="0" cellspacing="0" cellpadding="0" align="left">
<tr>

<xbk:tmpl name="migrations">
<xbk:tmpl name="migrations_comdition" type="condition" varscope="migrations" conditionVar="type">
<xbk:sub condition="current">
<td align="left" valign="center" class="superadmin-modules-module-migration-current">
{NUM}
</td>
<td width="5">
</td>
</xbk:sub>
<xbk:sub condition="required">
<td align="left" valign="center" class="superadmin-modules-module-migration-required" onMouseOver="this.style.border='1px solid #55AA44'" onMouseOut="this.style.border='1px solid #FFEE00'" onClick=JavaScript:window.open("{LINK}","_self")>
<a href="{LINK}" class="superadmin-modules-module-migration-required-a">{NUM}</a>
</td>
<td width="5">
</td>
</xbk:sub>
<xbk:sub condition="normal">
<td align="left" valign="center" class="superadmin-modules-module-migration-normal">
{NUM}
</td>
<td width="5">
</td>
</xbk:sub>
<xbk:sub condition="outside">
<td align="left" valign="center" class="superadmin-modules-module-migration-outside">
<a href="{LINK}">{NUM}</a>
</td>
<td width="5">
</td>
</xbk:sub>
</xbk:tmpl>
</xbk:tmpl>

<td align="left" valign="center" class="superadmin-modules-module-migration-td" nowrap="nowrap">
:: <a href="{RENEW_LINK}">{LANG_SUPERADMIN_MODULE_MIGRATIONS_ACTION_RENEW}</a>
</td>

</tr>
</table>

</td>
</tr>
</table>

</td>
</tr>
</xbk:tmpl>