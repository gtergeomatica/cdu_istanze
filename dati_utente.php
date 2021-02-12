
<!--div class="container"-->
<div>
	<h2>Dati utente</h2>
<?php

	$query = "SELECT * FROM utenti.utenti where  usr_login=$1";
	$result = pg_prepare($conn_isernia, "myquery0", $query);
    $result = pg_execute($conn_isernia, "myquery0", array($_SESSION['user']));
	//echo $query;
	//exit;
	//$rows = array();
	while($r = pg_fetch_assoc($result)) {
    		//$rows[] = $r;
			$usr_id=$r["id"];
			echo "<b>User</b>: ".$r["usr_login"];
			echo "<br><b>Nome e Cognome</b>: ".$r["firstname"]. " " .$r["lastname"];
			echo "<br><b>Codice Fiscale</b>: ".$r["cf"];
			echo "<br><b>Documento identità</b>: ".$r["doc_id"];
			echo "<br><b>Indirizzo</b>: ".$r["street"]. " - " .$r["postcode"]. ", " .$r["city"];
			echo "<br><b>E-mail</b>: ".$r["usr_email"];
			echo "<br><b>Telefono</b>: ".$r["phonenumber"];
	//echo $usr_id;
?>

<hr class="light">
<div>
	<a class="btn btn-light btn-xl" href="https://www.gter.it/">Richiedi CDU</a>
</div>
<hr class="light">


</div>
<div>
    <h2> <i class="fas fa-user-clock" style="color:white;"></i> Istanze CDU di <?php echo $r["usr_login"]; ?></h2>
	<!--div id="toolbar2">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div-->
	<div style="overflow-x:auto;">
    <table style="background-color:white;" id="log" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" data-page-size="50" 
  data-url="griglia_richieste.php?u=<?php echo $r["id"]; ?>" 
	data-show-export="false" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2">
<thead>

 <tr>
            <th data-field="state" data-checkbox="true"></th>
			<th data-field="id_istanza" data-sortable="true" data-filter-control="select" data-visible="true">Codice Istanza</th>
            <th data-field="data_istanza" data-sortable="true" data-filter-control="select" data-visible="true">Data Istanza</th>
            <th data-field="terreni" data-sortable="true" data-filter-control="select" data-visible="true">Terreni</th>
            <!--th data-field="mappale" data-sortable="true" data-filter-control="input" data-visible="true">Mappale</th>
            <th data-field="log_repository" data-sortable="true" data-filter-control="select" data-visible="true">Repository</th>
            <th data-field="log_project" data-sortable="true" data-filter-control="select" data-visible="true">Progetti</th-->

        </tr>
</thead>

</table>

</div>	


</div>	
<?php
}
?>