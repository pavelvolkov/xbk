<xbk:tmpl name="question"><xbk:css name="question" />
<div class="xbk-question-error-wrapper">
<form method="Post" action="{ACTION}" class="xbk-question-error-form">
<table border="0" cellspacing="0" cellpadding="10" width="100%" class="xbk-question-error-tbl">
<tr>
<td width="0%" align="center" valign="top" rowspan="2">
<img src="{PATH_TO_IMG}icons/error.png" />
</td>
<td width="100%" align="left" valign="top" class="xbk-question-error-text">
{TEXT}
</td>
</tr>
<tr>
<td align="left" valign="top">

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<xbk:tmpl name="submit">
<td class="xbk-question-error-submit-td">
<input type="submit" name="{NAME}" value="{VALUE}" class="xbk-question-error-submit" />
</td>
</xbk:tmpl>
</tr>
</table>

</td>
</tr>
</table>
</form>
</div>
</xbk:tmpl>