<xbk:tmpl name="question"><xbk:css name="question" />
<div class="xbk-question-question-wrapper">
<form method="Post" action="{ACTION}" class="xbk-question-question-form">
<table border="0" cellspacing="0" cellpadding="10" width="100%" class="xbk-question-question-tbl">
<tr>
<td width="0%" align="center" valign="top" rowspan="2">
<img src="{PATH_TO_IMG}icons/question.png" />
</td>
<td width="100%" align="left" valign="top" class="xbk-question-question-text">
{TEXT}
</td>
</tr>
<tr>
<td align="left" valign="top">

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<xbk:tmpl name="submit">
<td class="xbk-question-question-submit-td">
<input type="submit" name="{NAME}" value="{VALUE}" class="xbk-question-question-submit" />
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