


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
		for($rang =0; $rang<count($seqids);$rang++){
			$arrayCount[$rang]= array();
		}
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
			$arrayCount[$count][] = $etudiant;
		}
		$etudiants= array();
		foreach($arrayCount as $subarray){
			foreach($subarray as $etudiant){
				$etudiants[] = $etudiant;
			}
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
		$countSeq = array();
		foreach($seqids as $key => $seqid) {
			$countSeq[$seqid] = 0;
			//			echo '<input type="hidden" name="seqids['.$key.']" value="'.$seqid.'" />';
			echo '<th>'.$sequences[$seqid].'</th>';
		}
		?>
		<th>Nombre de présence</th>
		<?php if($sorted == True){
			echo '<th> nb etudiants </th>';
		}
		?>
		</thead>
	<tbody>

		<?php
		if($sorted == True){
			$nbPres = 0;
		}
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
			if($sorted == True){
				if($count >= $nbPres){
					$nbPres = $count;
					echo '<td>'.count($arrayCount[$nbPres]).'</td>';
					$nbPres++;
				}else{
					echo '<td>&nbsp;</td>';
				}
			}
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
