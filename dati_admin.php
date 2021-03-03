<?php
$cliente = 'Comune di Isernia';
?>
<div>
	<h2>Dati Amministratore</h2>
<?php
  //session_start();
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

<!--div style="overflow-x:auto;">
    <table style="background-color:white;" id="log" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" data-page-size="25" 
  data-url="griglia_utente.php?u=<?php echo $r["id"]; ?>" 
	data-show-export="false" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2">
<thead>

 <tr>
			      <th data-field="usr_login" data-sortable="false" data-visible="true">User</th>
			      <th data-field="firstname" data-sortable="false" data-formatter="nameFormatterSend" data-visible="true">Nome</th>
            <th data-field="lastname" data-sortable="false" data-filter-control="select" data-visible="true">Cognome</th>
            <th data-field="cf" data-sortable="false" data-visible="true">Codice Fiscale</th>
            <th data-field="doc_id" data-sortable="false" data-visible="true">Documento identità</th>
            <th data-field="indirizzo" data-sortable="false" data-visible="true">Indirizzo</th>
            <th data-field="usr_email" data-sortable="false" data-visible="true">E-mail</th>
            <th data-field="phonenumber" data-sortable="false" data-visible="true">Telefono</th>
            <th data-field="id" data-sortable="false" data-formatter="nameFormatterEdit" data-visible="true">Modifica</th>
        </tr>
</thead>

</table>

</div-->	
<br><br><a class="btn btn-light btn-sm" href="modifica_dati.php?u=<?php echo $r["id"]; ?>">Modifica i tuoi dati</a>
<hr class="light">
<!--div>
	<a class="btn btn-light btn-xl" href="form_istanza_cdu.php?u=<?php echo $r["id"]; ?>&user=<?php echo $r["usr_login"]; ?>">Richiedi CDU</a>
</div>
<hr class="light"-->


</div>
<!-- tabella istanze inviate al comune -->
<div>
    <h2> <i class="fas fa-copy" style="color:white;"></i> Istanze CDU del <?php echo $cliente; ?></h2>
	<!--div id="toolbar2">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div-->
	<div style="overflow-x:auto;">
    <table style="background-color:white;" id="log" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" data-page-size="25" 
  data-url="griglia_ist_admin.php" 
	data-show-export="false" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2" data-locale="it-IT">
<thead>

 <tr>
            <!--th data-field="state" data-checkbox="true"></th-->
			<!--th data-field="id" data-sortable="false" data-formatter="nameFormatterRemove" data-visible="true">Rimuovi</th-->
			<th data-field="id_istanza" data-sortable="false" data-formatter="nameFormatterSend" data-visible="true">Invia</th>
            <th data-field="usr_login" data-sortable="true" data-filter-control="select" data-visible="true">Utente</th>
            <th data-field="usr_email" data-sortable="true" data-filter-control="select" data-visible="true">E-mail</th>
            <th data-field="data_istanza" data-sortable="true" data-filter-control="select" data-visible="true">Data Istanza</th>
            <!--th data-field="terreni" data-sortable="true" data-filter-control="select" data-visible="true">Terreni</th-->
            <th data-field="file_txt" data-sortable="false" data-formatter="nameFormatterFile0" data-visible="true">File Terreni</th>
            <th data-field="file_s" data-sortable="false" data-formatter="nameFormatterFile1" data-visible="true">Segreteria</th>
            <th data-field="file_bi" data-sortable="false" data-formatter="nameFormatterFile2" data-visible="true">Bollo Istanza</th>
            <th data-field="n_bolli" data-sortable="false" data-formatter="nameFormatterFile5" data-visible="true">N. Bolli</th>
            <th data-field="file_bc" data-sortable="false" data-formatter="nameFormatterFile3" data-visible="true">Bollo CDU</th>
            <th data-field="file_cdu" data-sortable="false" data-formatter="nameFormatterFile4" data-visible="true">File CDU</th>
        </tr>
</thead>

</table>

</div>	


</div>

<!-- tabella utenti del comune -->
<hr class="light">

<div>
    <h2> <i class="fas fa-users" style="color:white;"></i> Utenti registrati al Sistema di Istanza Online del <?php echo $cliente; ?></h2>
	<!--div id="toolbar2">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div-->
	<div style="overflow-x:auto;">
    <table style="background-color:white;" id="usr" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" data-page-size="25" 
  data-url="griglia_user_admin.php" 
	data-show-export="false" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2" data-locale="it-IT">
<thead>

 <tr>
            <!--th data-field="state" data-checkbox="true"></th-->
			<!--th data-field="id" data-sortable="false" data-formatter="nameFormatterRemove" data-visible="true">Rimuovi</th-->
			      <!--th data-field="id_istanza" data-sortable="false" data-formatter="nameFormatterSend" data-visible="true">Invia</th-->
            <th data-field="usr_login" data-sortable="true" data-filter-control="select" data-visible="true">Utente</th>
            <th data-field="nome" data-sortable="true" data-filter-control="select" data-visible="true">Nome</th>
            <th data-field="usr_email" data-sortable="true" data-filter-control="select" data-visible="true">E-mail</th>
            <th data-field="cf" data-sortable="true" data-filter-control="select" data-visible="true">CF</th>
            <th data-field="doc_id" data-sortable="true" data-filter-control="select" data-visible="true">Documento</th>
            <th data-field="indirizzo" data-sortable="true" data-filter-control="select" data-visible="true">Indirizzo</th>
            <th data-field="phonenumber" data-sortable="true" data-filter-control="select" data-visible="true">Telefono</th>
            <th data-field="organization" data-sortable="true" data-filter-control="select" data-visible="true">Affiliazione</th>
            <th data-field="admin" data-sortable="false" data-formatter="nameFormatterFile6" data-visible="true">Admin</th>
        </tr>
</thead>

</table>

</div>	


</div>

<script>
function nameFormatterSend(value, row) {
	//var test_id= row.id;
  if (row.file_cdu != null && row.terminato != 't'){
    if (row.n_bolli != null && row.file_bc != null){
      return' <a id="myLink" type="button" class="btn btn-info" href="invia_cdu.php?idi='+row.id_istanza+'&idu='+row.usr_login+'"><i class="fas fa-play-circle"></i></a>';
    }else if(row.n_bolli == null && row.file_bc == null){
      return' <a id="myLink" type="button" class="btn btn-info" href="invia_cdu.php?idi='+row.id_istanza+'&idu='+row.usr_login+'"><i class="fas fa-play-circle"></i></a>';
    }
  }else{
    return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
  }

}

/* function nameFormatterRemove(value, row) {
	//var test_id= row.id;
	//return' <button type="button" class="btn btn-info" data-target="remove_ist.php?idu='+row.id_istanza+'"><i class="fas fa-trash-alt"></i></button>';
	  return' <a type="button" class="btn btn-info" href="remove_user.php?idu='+row.id+'"><i class="fas fa-trash-alt"></i></a>';
} */

/* function nameFormatterFile6(value, row) {
  if (row.admin == 't'){
    return' <input type="checkbox" id="check" name="check" title="Select All" checked>';
  }else{
    return' <input type="checkbox" id="check" name="check" title="Select All">';
  }
} */

function nameFormatterFile6(value, row) {
  if (row.admin == 't'){
    return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAd'+row.id+'" title="Rendi amministratore"><i class="fas fa-user-shield"></i></button>\
        <div class="modal fade" id="myModalAd'+row.id+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelAd'+row.id+'">Rimuovi Amministratore</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="rm_admin.php?idu='+row.id+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Vuoi davvero rimuovere l\'utente <b>'+row.usr_login+'</b> da amministratore?<br><br>\
          <input type="hidden" name="userAd" id="userAd'+row.id+'" value="<?php echo $_SESSION['user']; ?>">\
          <!--input type="text" name="numeroBolli" id="numeroBolli'+row.id_istanza+'"><br><br-->\
          <input type="submit" value="Si" name="submitadmin">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>' ;
  }else{
    return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAd'+row.id+'" title="Rendi amministratore"><i class="fas fa-user"></i></button>\
        <div class="modal fade" id="myModalAd'+row.id+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelAd'+row.id+'">Rendi Amministratore</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="admin.php?idu='+row.id+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Vuoi davvero rendere l\'utente <b>'+row.usr_login+'</b> amministratore?<br><br>\
          <input type="hidden" name="userAd" id="userAd'+row.id+'" value="<?php echo $_SESSION['user']; ?>">\
          <!--input type="text" name="numeroBolli" id="numeroBolli'+row.id_istanza+'"><br><br-->\
          <input type="submit" value="Si" name="submitadmin">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>' ;
  }
}

function nameFormatterFile0(value, row) {
  if (row.file_txt != null){
    return' <span><a href="../isernia_upload/mappali_cdu/'+ row.file_txt.split("/").pop() +'">Vedi file</a></span><br><a class="btn btn-primary" href="./download_txt.php?f='+ row.file_txt.split("/").pop() +'"><i class="fas fa-file-download"></i></a>';
  }else{
    return' <span>'+ row.file_txt +'</span>';
  }
}

function nameFormatterFile1(value, row) {
  return' <span><a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'">Vedi file</a></span>';
}

function nameFormatterFile2(value, row) {
  return' <span><a href="../isernia_upload/bollo_istanza/'+ row.file_bi.split("/").pop() +'">Vedi file</a></span>';
}

function nameFormatterFile3(value, row) {
  if (row.file_bc != null){
    return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'">Vedi file</a></span>';
  }else{
    return' <span>'+ row.file_bc +'</span>';
  }
}

function nameFormatterFile5(value, row) {
    if (row.n_bolli == null){
      return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalNb'+row.id_istanza+'" title="Inserisci numero bolli per CDU"><i class="fas fa-edit"></i></button>\
        <div class="modal fade" id="myModalNb'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelNb'+row.id_istanza+'">N. Bolli per CDU</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="numero_bolli.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Indica numero Bolli da pagare per CDU:<br><br>\
          <input type="hidden" name="userNb" id="userNb'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
          <input type="text" name="numeroBolli" id="numeroBolli'+row.id_istanza+'"><br><br>\
          <input type="submit" value="Invia" name="submitnum">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>' ;
    } else{
      if (row.terminato != 't'){
        return' <span>'+ row.n_bolli +'</span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalNb'+row.id_istanza+'" title="Modifica numero bolli per CDU"><i class="fas fa-edit"></i></button>\
          <div class="modal fade" id="myModalNb'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" id="exampleModalLabelNb'+row.id_istanza+'">N. Bolli per CDU</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
          <form action="numero_bolli.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
          <div class="form-group">\
          Indica numero Bolli da pagare per CDU:<br><br>\
            <input type="hidden" name="userNb" id="userNb'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
            <input type="text" name="numeroBolli" id="numeroBolli'+row.id_istanza+'"><br><br>\
            <input type="submit" value="Invia" name="submitnum">\
            </div>\
          </form>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div>\
          </div>\
        </div>\
      </div>' ;
      }else{
        return' <span>'+ row.n_bolli +'</span>';
      }
    }
}

function nameFormatterFile4(value, row) {
    if (row.file_cdu == null){
      return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalCdu'+row.id_istanza+'" title="Carica file CDU"><i class="fas fa-file-upload"></i></button>\
        <div class="modal fade" id="myModalCdu'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelCdu'+row.id_istanza+'">File CDU</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="upload_cdu.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Seleziona il file del CDU:<br><br>\
          <input type="hidden" name="userCdu" id="userCdu'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
          <input type="file" name="fileToUploadCdu" id="fileToUploadCdu'+row.id_istanza+'"><br><br>\
          <input type="submit" value="Carica file" name="submitfile">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>' ;
    } else{
      if (row.terminato != 't'){
        return' <span><a href="../isernia_upload/cdu/'+ row.file_cdu.split("/").pop() +'">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalCdu'+row.id_istanza+'" title="Modifica file CDU"><i class="fas fa-file-upload"></i></button>\
          <div class="modal fade" id="myModalCdu'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" id="exampleModalLabelCdu'+row.id_istanza+'">File CDU</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
          <form action="upload_cdu.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
          <div class="form-group">\
            Seleziona il file del CDU:<br><br>\
            <input type="hidden" name="userCdu" id="userCdu'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
            <input type="file" name="fileToUploadCdu" id="fileToUploadCdu'+row.id_istanza+'"><br><br>\
            <input type="submit" value="Carica file" name="submitfile">\
            </div>\
          </form>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div>\
          </div>\
        </div>\
      </div>' ;
      }else{
        return' <span><a href="../isernia_upload/cdu/'+ row.file_cdu.split("/").pop() +'">Vedi file</a></span>';
      }
    }
}
</script>
<?php
}
?>