<xbk:tmpl name="content"><xbk:css name="superadmin_modules" />
{TEASER}
{INFO}

<xbk:comment>
Список модулей - начало
</xbk:comment>

<table cellspacing="0" cellpadding="5" border="0" width="100%">
<tr style="background-color: #EEEEEE">
<td align="left" class="superadmin-modules-tbl-title">
{LANG_SUPERADMIN_MODULES_NAME}
</td>
<td align="left" class="superadmin-modules-tbl-title">
{LANG_SUPERADMIN_MODULES_TYPE}
</td>
<!--
<td align="left" class="superadmin-modules-tbl-title">
{LANG_SUPERADMIN_MODULES_VERSION}
</td>
<td align="left" class="superadmin-modules-tbl-title">
{LANG_SUPERADMIN_MODULES_XBK_VERSION}
</td>
-->
<td align="left" class="superadmin-modules-tbl-title">
{LANG_SUPERADMIN_MODULES_STATUS}
</td>
<td align="left" class="superadmin-modules-tbl-title">
{LANG_SUPERADMIN_MODULES_ACTION}
</td>
</tr>

<xbk:tmpl name="modules" type="OddEven">

<xbk:sub condition="__odd">
<tr style="background-color: #F9F9F9">
<td align="left" valign="top" class="superadmin-modules-tbl-td">
<a href="{INFO_LINK}">{NAME}</a>
</td>
<td align="left" valign="top" class="superadmin-modules-tbl-td">
{TYPE}
</td>
<!--
<td align="left" valign="top" class="superadmin-modules-tbl-td">
{VERSION}
</td>
<td align="left" valign="top" class="superadmin-modules-tbl-td">
{XBK_VERSION}
</td>
-->
<td align="left" valign="top" class="superadmin-modules-tbl-td">
<a href="{STATUS_LINK}">{LANG_SUPERADMIN_MODULES_STATUS}</a>
</td>
<td align="left" valign="top" class="superadmin-modules-tbl-td">
<a href="{ACTION_LINK}">{LANG_SUPERADMIN_MODULES_ACTION}</a>
</td>
</tr>
</xbk:sub>

<xbk:sub condition="__even">
<tr style="background-color: #F5F5F5">
<td align="left" valign="top" class="superadmin-modules-tbl-td">
<a href="{INFO_LINK}">{NAME}</a>
</td>
<td align="left" valign="top" class="superadmin-modules-tbl-td">
{TYPE}
</td>
<!--
<td align="left" valign="top" class="superadmin-modules-tbl-td">
{VERSION}
</td>
<td align="left" valign="top" class="superadmin-modules-tbl-td">
{XBK_VERSION}
</td>
-->
<td align="left" valign="top" class="superadmin-modules-tbl-td">
<a href="{STATUS_LINK}">{LANG_SUPERADMIN_MODULES_STATUS}</a>
</td>
<td align="left" valign="top" class="superadmin-modules-tbl-td">
<a href="{ACTION_LINK}">{LANG_SUPERADMIN_MODULES_ACTION}</a>
</td>
</tr>
</xbk:sub>

</xbk:tmpl>

</table>

<xbk:comment>
Список модулей - конец
</xbk:comment>


</xbk:tmpl>