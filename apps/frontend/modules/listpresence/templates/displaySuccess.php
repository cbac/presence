


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
			echo '<br />à la séquence ' . $listSeqs;
		}else {
			echo '<br />aux séquences ' . $listSeqs;
		}
	}
	if($sorted == True){
		// create an array of array where index is the presence count
		// then recreate the array of students
		$arrayCount = array();
		foreach($etudiants as $key => $etudiant){

			$uid = $etudiant->getId();
			$count = 0;
			foreach($seqids as $seqid){
				if($sequences[$seqid]->getNote()== False){
					if(isset($attendances[$uid]) && isset($attendances[$uid][$seqid])){
						$count++;
					}
				}
			}
			if(array_key_exists($count, $arrayCount)){
				$arrayCount[$count][] = $etudiant;
			} else {
				$arrayCount[$count] = array();
			}
		}
		$etudiants= array();
		foreach($arrayCount as $subarray){
			foreach($subarray as $etudiant){
				$etudiants[] = $etudiant;
			}
		}
	}
/*	function cmp($a, $b)
	{
		$res = strcmp($a[0], $b[0]);
		if($res == 0) {
			$res = strcmp($a[1],$b[1]);
		}
		return $res;
	}
	// sort the content of each sub array alphabetically
	foreach($arrayCount as $subarray){
		sor
	}
	*/
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
<a href="<?php echo url_for('listpresence/index') ?>">retour au choix des groupes et séquences</a>
<br />
<a href="<?php echo url_for('choicemodule/index') ?>">retour au choix du module </a>
