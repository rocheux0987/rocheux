
<table border="1">
{foreach name=sample_foreach from=$query_result item=row}
<tr>
	<td>{$row.id}</td>
    <td>{$row.title}</td>
    <td>{$row.description}</td>
</tr>
{/foreach}
</table>