


<h1>Pr&eacute;sence des &eacute;l&egrave;ves en CSC4002</h1>

<table border="1">
	<thead>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Groupe</th>
		
		
		
		
		<?php
		$countSeq = array();
		foreach($sequences as $seq) {
			$countSeq[$seq->getId()] = 0;
			echo '<th>'.$seq.'</th>';
		}
		?>
		<th>Total Etudiant</th>
	</thead>
	<tbody>



	<?php

	foreach($etudiants as $key => $etudiant){
		if($etudiant->getTwin() == 'a' ){
			echo '<tr bgcolor="#CCCC99">';
		}else{
			echo '<tr>';
		}
		echo '<td>'.$etudiant->getLastname().'</td>';
		echo '<td>'.$etudiant->getFirstname().'</td>';
		echo '<td align="center">'.$groupes[$etudiant->getGid()].'</td>';
		$uid = $etudiant->getId();
		$count = 0;
		foreach($sequences as $seq){
			$seqid = $seq->getId();
			if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
				echo '<td align="center">X</td>';
				$countSeq[$seqid]++;
				$count++;
			}else {
				echo '<td>&nbsp;</td>';
			}
		}
		echo '<td>'.$count.'</td>';

		echo '</tr>';
	}
	?>
		<tr>
			<td colspan="3">Présence par séquence sur : <?php echo count($etudiants); ?> </td>
			
			
			
			
	<?php
		foreach($countSeq as $count) {
			echo '<td>'.$count.'</td>';
		}
	?>
	</tr>
	</tbody>

</table>

<br>
<a href="<?php echo url_for('listepresence/index') ?>">retour</a>
