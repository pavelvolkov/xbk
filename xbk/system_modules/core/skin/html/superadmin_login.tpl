<xbk:tmpl name="body"><xbk:css name="superadmin" order="1" /><table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
<td>

<table border="0" cellspacing="0" cellpadding="5" align="center" valign="center" style="border: 1px solid #555555" width="300">
<tr style="background-color: #EEFFDD">
<td style="border-bottom: 1px solid #555555">{LANG_AUTH}</td>
</tr>
<tr style="background-color: #FFFFFF">
<td height="200" align="center" valign="center">
{ERROR}
<form action="{ACTION}" method="Post">
<table border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td align="right">
        {LANG_LOGIN}
        </td>
        <td align="left">
        <input type="text" name="login" value="" class="input" />
        </td>
    </tr>
    <tr>
        <td align="right">
        {LANG_PASS}
        </td>
        <td align="left">
        <input type="password" name="pass" value="" class="input" />
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
        <input type="submit" name="submit" value="{LANG_SUBMIT}" />
        </td>
    </tr>
</table>

</form>
</td>
</tr>
</table>

</td>
</tr>
</table></xbk:tmpl>