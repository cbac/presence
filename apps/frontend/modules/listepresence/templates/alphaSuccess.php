


<h1>Présence des élèves en CSC4002</h1>

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

		echo '<tr>';
		echo '<td>'.$etudiant->getLastname().'</td>';
		echo '<td>'.$etudiant->getFirstname().'</td>';
		echo '<td align="center">'.$etudiant->getGroupe().'</td>';
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
	<td colspan="3">Présence par séquence</td>
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