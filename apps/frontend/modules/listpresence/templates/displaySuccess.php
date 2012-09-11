


<h1>
	Students Attendance <br />
	<?php
	if(count($gids)){
		if(count($gids) == 1){
			echo ' group ' . $listGroups;
		}else {
			echo ' groups ' . $listGroups;
		}
	}

	if(count($seqids)){
		if(count($seqids) == 1){
			echo '<br />at sequence ' . $listSeqs;
		}else {
			echo '<br />at sequences ' . $listSeqs;
		}
	}
	?>

</h1>

<table border="1">
	<thead>
		<th>Lastname</th>
		<th>Firstname</th>
		<th>Group</th>

		<?php
		//		foreach($gids as $key => $gid) {
		//			echo '<input type="hidden" name="gids['.$key.']" value="'.$gid.'" />';
		//		}
		$countSeq = array();
		foreach($seqids as $key => $seqid) {
			$countSeq[$seqid] = 0;
			//			echo '<input type="hidden" name="seqids['.$key.']" value="'.$seqid.'" />';
			echo '<th>'.$sequences[$seqid].'</th>';
		}
		?>
		<th>Student Sum</th>
		<th>Student average grade</th>
	</thead>
	<tbody>

		<?php
		foreach($etudiants as $key => $etudiant){
			if($etudiant->getTwin() == 'a'){
				echo '<tr bgcolor="#CCCC99">';
			}else{
				echo '<tr>';
			}
			echo '<td>'.$etudiant->getLastname().'</td>';
			echo '<td>'.$etudiant->getFirstname().'</td>';
			echo '<td align="center">'.$groups[$etudiant->getGid()].'</td>';
			$uid = $etudiant->getId();
			$count = 0;
			foreach($seqids as $seqid){
				if($sequences[$seqid]->getNote()== False){
					if(isset($attendances[$uid]) && isset($attendances[$uid][$seqid])){
						echo '<td align="center">X</td>';
						$count++;
						$countSeq[$seqid]++;
					}else {
						echo '<td>&nbsp;</td>';
					}
				}else{
					if(isset($attendances[$uid]) && isset($attendances[$uid][$seqid])){
						echo '<td align="center">'.$attendances[$uid][$seqid]->getNote().'</td>';
					}else {
						echo '<td>&nbsp;</td>';
					}
				}
			}
			echo '<td>'.$count.'</td>';
			echo '</tr>';
		}
		?>
		<tr>
			<td colspan="3">Attendance count on : <?php echo count($etudiants); ?>
			</td>
			<?php foreach($countSeq as $cseq){
				echo '<td>'.$cseq.'</td>';
			}
			?>
		</tr>
	</tbody>

</table>

<br>
<a href="<?php echo url_for('listpresence/index') ?>">back to index</a>
