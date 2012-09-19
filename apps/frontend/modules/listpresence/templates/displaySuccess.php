


<h1>
	Présence des étudiants  au module
	<?php
	echo $moduleens->getName().'<br />';
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
		<th>Nom</th>
		<th>Prénom</th>
		<th>Groupe</th>

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
		<th>Nombre de présence</th>
	</thead>
	<tbody>

		<?php
		foreach($etudiants as $key => $etudiant){

				echo '<tr>';
			
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
			<td colspan="3">Nombre total de présences sur : <?php echo count($etudiants); ?>
			</td>
			<?php foreach($countSeq as $cseq){
				echo '<td>'.$cseq.'</td>';
			}
			?>
		</tr>
	</tbody>

</table>

<br>
<a href="<?php echo url_for('listpresence/index') ?>">retour vers le choix des groupes et séquences</a>