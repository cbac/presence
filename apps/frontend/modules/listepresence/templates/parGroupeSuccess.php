


<h1>
	Présence des élèves groupe
	<?php echo $listGroups ?>
	<br /> àla seance
	<?php echo $listSeqs ?>
	CSC4002
</h1>

<table border="1">
	<thead>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Groupe</th>

		<?php
		foreach($gids as $key => $gid) {
			echo '<input type="hidden" name="gids['.$key.']" value="'.$gid.'" />';
		}
		$countSeq = array();
		foreach($seqids as $key => $seqid) {
			$countSeq[$seqid] = 0;
			echo '<input type="hidden" name="seqids['.$key.']" value="'.$seqid.'" />';
			echo '<th>'.$sequences[$seqid].'</th>';
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
			foreach($seqids as $seqid){
				if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
						echo '<td align="center">X</td>';
						$count++;
						$countSeq[$seqid]++;
				}else {
						echo '<td>&nbsp;</td>';
				}
			}
			echo '<td>'.$count.'</td>';
			echo '</tr>';
		}
		?>
		<tr><td colspan="3">Total par séquence</td>
		<?php foreach($countSeq as $cseq){
			echo '<td>'.$cseq.'</td>';
		}
		?>
		</tr>
		</tbody>

</table>

<br>
<a href="<?php echo url_for('listepresence/index') ?>">retour</a>
