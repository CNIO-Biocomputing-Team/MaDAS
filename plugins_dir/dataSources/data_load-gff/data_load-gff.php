<div id="das_load-gff">
	<form id="das_load-gffForm" action="plugins_dir/dataSources/das_load-gff_R.php" method="post">
		<table align="center" style="margin:10px;">
			<tbody>
				<tr>
					<td class="option">DSN *</td>
					<td  class="value">
						<input name="dsn" type="text" size="53" title="<br>Please provide a DSN" class="{required:true}" />
					</td>
				</tr>
				<tr>
					<td class="option">Version *</td>
					<td  class="value">
						<input name="version" type="text" size="5" title="<br>Please provide a DSN version" class="{required:true}" />
					</td>
				</tr>
				<tr>
					<td class="option">Description *</td>
					<td  class="value">
						<textarea name="description" rows="8" cols="40" title="<br>Please provide a DSN description" class="{required:true}"></textarea>
					</td>
				</tr>
				<tr>
					<td class="option">GFF *</td>
					<td  class="value">
						<input type="file" name="gff" title="<br>You must upload the GFF" class="{required:true}" />
					</td>
				</tr>
				<tr>
					<td />
					<td class="value">
						<input type="submit" value="Upload" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
