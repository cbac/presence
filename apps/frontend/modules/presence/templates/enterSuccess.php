


<h1>
	Pr&eacute;sence du module
	<?php echo $moduleens->getName() ?>
	des &eacute;l&egrave;ves
	<?php
	$debug= false;
	if(count($gids)){
		if(count($gids) == 1){
			echo ' groupe ' . $listGroups;
		}else {
			echo ' groupes ' . $listGroups;
		}
	}

	if(count($seqids)){
		if(count($seqids) == 1){
			echo '<br /> &agrave; la s&eacute;ance ' . $listSeqs;
		}else {
			echo '<br />aux s&eacute;quences ' . $listSeqs;
		}
	}
	if($debug){
		foreach ($presences as $uid =>$seqids){
			foreach($seqids as $sid => $presence){
				echo $presence->__toString().'<br>';
			}
		}
	}
	?>
	<br />
</h1>

<table border="1">
	<thead>
		<tr>

			<th>Nom</th>
			<th>Prenom</th>
			<th>Groupe</th>
			<?php 
			foreach($seqids as $key => $seqid) {
				echo '<th>'.$sequences[$seqid].'</th>';
			}
			?>
		</tr>
	</thead>
		<tbody>
			<?php
			$curGid = -1; $nblines=0;
			foreach($etudiants as $etudiant){
				if(count($gids)>0){
					if($curGid != -1){
						if($etudiant->getGid() != $curGid) {
							// repeat header
							echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
							foreach($seqids as $key => $seqid) {
								echo '<th>'.$sequences[$seqid].'</th>';
							}
							echo '</tr>'	;
						}
					}
					$curGid = $etudiant->getGid();
				}else{
					$nblines++;
					if(($nblines % 30) == 0){
						echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
						foreach($seqids as $key => $seqid) {
							echo '<th>'.$sequences[$seqid].'</th>';
						}
						echo '</tr>';
					}
				}
				echo '<tr>';
				$uid = $etudiant->getId();
				echo '<td>'.$etudiant->getLastname().'</td>';
				echo '<td>'.$etudiant->getFirstname().'</td>';
				echo '<td align="center">'.$groups[$etudiant->getGid()].'</td>';

				foreach($seqids as $seqid){
					$align='"center"';
					$bgcolor='"#FFFFFF"';
					$tdcontent = '&nbsp;';
					if(isset($removed[$uid]) && isset($removed[$uid][$seqid])){
						//has been removed => orange
						$bgcolor = '"#FFFF88"';
					}else{
						if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
							if(isset($added[$uid]) && isset($added[$uid][$seqid])){
								//  has been added => green color
								$bgcolor = '"#33FF33"';
							}
							if($sequences[$seqid]->getNote()){
								// this sequence requires a mark
								$align= '"right"';
								$tdcontent = $presences[$uid][$seqid]->getNote();
							} else {
								$tdcontent = 'X';
							}
						}
					}
					echo '<td bgcolor='.$bgcolor.' align='.$align.'>'.$tdcontent.'</td>';
				}
				echo '</tr>';
			}
			?>
		</tbody>

</table>

<br />
<a href="<?php echo url_for('presence/choose') ?>">retour à la saisie</a>
<br />
<br />
<a href="<?php echo url_for('presence/index') ?>">retour au choix des groupes et séquences</a>
<br />
<br />
<a href="<?php echo url_for('listpresence/index') ?>">vers la liste des
	présences</a>
<br />
<br />
<a href="<?php echo url_for('choicemodule/index') ?>">vers le choix des modules </a>