<?php
$cliente = 'Comune di Isernia';
?>
<div>
	<h2>Dati Amministratore</h2>
<?php
  //query sul DB per recuperare dati utente e stamparli nella dasboard
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
      echo "<br><b>Data Scadenza Documento identità</b>: ".$r["doc_exp"];
      //check su scadenza del documento
      if($r["doc_exp"] < date("Y-m-d")){
        echo "<br><br><b style='color: yellow;'>ATTENZIONE il Documento è SCADUTO!<br> Modifica i tuoi dati.</b><br>";
      }
			echo "<br><b>Indirizzo</b>: ".$r["street"]. " - " .$r["postcode"]. ", " .$r["city"];
			echo "<br><b>E-mail</b>: ".$r["usr_email"];
			echo "<br><b>Telefono</b>: ".$r["phonenumber"];
	//echo $usr_id;
?>

<!-- link a form per cambiare i dati personali tranne username e pwd richiama modifica_dati.php -->
<br><br><a class="btn btn-light btn-sm" href="modifica_dati.php?u=<?php echo $r["id"]; ?>">Modifica i tuoi dati</a>
<hr class="light">

</div>
<!-- tabella istanze inviate al comune che richiama griglia_ist_admin.php per i dati -->
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
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2" data-locale="it-IT" data-row-style="rowStyle2">
<thead>
<!--data-field="colonna DB" data-formatter="nome funzione" -->
 <tr>
            <!--th data-field="state" data-checkbox="true"></th-->
			<!--th data-field="id" data-sortable="false" data-formatter="nameFormatterRemove" data-visible="true">Rimuovi</th-->
			<th data-field="id_istanza" data-sortable="false" data-formatter="nameFormatterSend" data-visible="true">Invia</th>
            <th data-field="usr_login" data-sortable="true" data-filter-control="input" data-visible="false">Username</th>
            <th data-field="nome" data-sortable="true" data-filter-control="input" data-visible="true">Utente</th>
            <th data-field="usr_email" data-sortable="true" data-filter-control="input" data-visible="true">E-mail</th>
            <th data-field="tipo" data-sortable="true" data-filter-control="select" data-visible="true">Tipo Istanza</th>
            <th data-field="data_invio" data-sortable="true" data-filter-control="input" data-visible="true">Data Istanza</th>
            <!--th data-field="terreni" data-sortable="true" data-filter-control="select" data-visible="true">Terreni</th-->
            <th data-field="file_txt" data-sortable="false" data-formatter="nameFormatterFile0" data-visible="true">File Terreni</th>
            <th data-field="file_s" data-sortable="false" data-formatter="nameFormatterFile1" data-visible="true">Segreteria</th>
            <th data-field="file_bi" data-sortable="false" data-formatter="nameFormatterFile2" data-visible="true">Bollo Istanza</th>
            <th data-field="n_bolli" data-sortable="false" data-formatter="nameFormatterFile5" data-visible="true">N. Bolli</th>
            <th data-field="file_bc" data-sortable="false" data-formatter="nameFormatterFile3" data-visible="true">Bollo CDU</th>
            <th data-field="file_bc_integr" data-sortable="false" data-formatter="nameFormatterFile8" data-visible="true">Altri bolli CDU</th>
            <th data-field="file_cdu" data-sortable="false" data-formatter="nameFormatterFile4" data-visible="true">Documento</th>
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
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2" data-locale="it-IT" data-row-style="rowStyle">
<thead>

 <tr>
            <!--th data-field="state" data-checkbox="true"></th-->
			      <th data-field="id" data-sortable="false" data-formatter="nameFormatterRemove" data-visible="true">Rimuovi</th>
			      <!--th data-field="id_istanza" data-sortable="false" data-formatter="nameFormatterSend" data-visible="true">Invia</th-->
            <th data-field="usr_login" data-sortable="true" data-filter-control="input" data-visible="true">Utente</th>
            <th data-field="nome" data-sortable="true" data-filter-control="input" data-visible="true">Nome</th>
            <th data-field="usr_email" data-sortable="true" data-filter-control="input" data-visible="true">E-mail</th>
            <th data-field="cf" data-sortable="true" data-filter-control="input" data-visible="true">CF</th>
            <th data-field="doc_id" data-sortable="true" data-filter-control="input" data-visible="true">Documento</th>
            <th data-field="doc_exp" data-sortable="true" data-filter-control="input" data-formatter="nameFormatterFile7" data-visible="true">Scadenza</th>
            <th data-field="indirizzo" data-sortable="true" data-filter-control="input" data-visible="true">Indirizzo</th>
            <th data-field="phonenumber" data-sortable="true" data-filter-control="input" data-visible="true">Telefono</th>
            <th data-field="organization" data-sortable="true" data-filter-control="input" data-visible="true">Affiliazione</th>
            <th data-field="admin" data-sortable="false" data-formatter="nameFormatterFile6" data-visible="true">Admin</th>
        </tr>
</thead>

</table>

</div>	


</div>
<!-- Script con tutte le funzioni richiamate dalle tabelle istanze e utenti -->
<script>
// funzione sul pulsante invia cdu richiama file invia_cdu.php
function nameFormatterSend(value, row) {
	if (row.tipo == 'Visura'){
    if (row.file_cdu != null && row.terminato != 't'){
      return' <a id="myLink" type="button" class="btn btn-info" href="invia_cdu.php?idi='+row.id_istanza+'&idu='+row.usr_login+'"><i class="fas fa-play-circle"></i></a>';
    }else{
      return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
    }
  }else{
    if (row.file_cdu != null && row.terminato != 't'){
      if (row.n_bolli == 1){
        return' <a id="myLink" type="button" class="btn btn-info" href="invia_cdu.php?idi='+row.id_istanza+'&idu='+row.usr_login+'"><i class="fas fa-play-circle"></i></a>';
      }else{
        if (row.file_bc_integr != null){
          return' <a id="myLink" type="button" class="btn btn-info" href="invia_cdu.php?idi='+row.id_istanza+'&idu='+row.usr_login+'"><i class="fas fa-play-circle"></i></a>';
        }else{
          return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
        }
      }
    }else{
      return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
    }
  }
}

// funzione sul pulsante rimuovi utente richiama file remove_user.php
function nameFormatterRemove(value, row) {
	//var test_id= row.id;
	//return' <button type="button" class="btn btn-info" data-target="remove_ist.php?idu='+row.id_istanza+'"><i class="fas fa-trash-alt"></i></button>';
	  return' <a type="button" class="btn btn-info" href="remove_user.php?idu='+row.id+'"><i class="fas fa-trash-alt"></i></a>';
}

/* function nameFormatterFile6(value, row) {
  if (row.admin == 't'){
    return' <input type="checkbox" id="check" name="check" title="Select All" checked>';
  }else{
    return' <input type="checkbox" id="check" name="check" title="Select All">';
  }
} */

// funzione sul pulsante rendi/rimuovi utente amministratore apre modal che richiama file rm_admin.php
function nameFormatterFile6(value, row) {
  if (row.admin == 't'){
    return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAd'+row.id+'" title="Rimuovi da amministratore"><i class="fas fa-user-shield"></i></button>\
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
    return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAd'+row.id+'" title="Rendi amministratore" style="background-color: #38c038; border-color: #38c038;"><i class="fas fa-user"></i></button>\
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

// funzione per visualizzare e scaricare file mappali richiama download_txt.php
function nameFormatterFile0(value, row) {
  if (row.file_txt != null){
    return' <span><a href="../isernia_upload/mappali_cdu/'+ row.file_txt.split("/").pop() +'" target="_blank">Vedi file</a></span><br><a class="btn btn-primary" href="./download_txt.php?f='+ row.file_txt.split("/").pop() +'"><i class="fas fa-file-download"></i></a>';
  }else{
    return' <span>'+ row.file_txt +'</span>';
  }
}

// funzione per visualizzare autocertificazione pagamento diritti istruttori
/* function nameFormatterFile1(value, row) {
  return' <span><a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'" target="_blank">Vedi file</a>\
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalEstremiS'+row.id_istanza+'" title="Inserisci numero bolli per CDU"><i class="fas fa-edit"></i></button>\
  <input readonly="readonly" name="estremi_s" id="estremi_s'+row.id_istanza+'" value="'+row.estremi_s+'"><br>\
  <button class="btn" onclick="copyestremi_s(\'estremi_s' + row.id_istanza + '\')"><i class="fas fa-clone"></i></button>\
  </span><script>\
  function copyestremi_s(rowid){\
  console.log(rowid);\
  var copyText = document.getElementById(rowid);\
  console.log(copyText);\
  copyText.select();\
  document.execCommand("copy");\
  alert("Estremi: " + copyText.value);\
  }</scr'+'ipt>';
} */
function nameFormatterFile1(value, row) {
  return' <span><a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'" target="_blank">Vedi file</a>\
  <button type="button" class="btn" data-toggle="modal" data-target="#estremiS'+row.id_istanza+'" title="Copia estremi pagamento"><i class="fas fa-clone"></i></button>\
        <div class="modal fade" id="estremiS'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" >Estremi pagamento diritti Istruttori</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
          <input readonly="readonly" name="estremi_s" id="estremi_s'+row.id_istanza+'" value="'+row.estremi_s+'"><br><br>\
          <button class="btn btn-primary" onclick="copyestremi(\'estremi_s' + row.id_istanza + '\')" data-dismiss="modal">Copia estremi</button>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>\
  </span>';
}

// funzione per visualizzare autocertificazione pagamento bollo istanza
function nameFormatterFile2(value, row) {
  if (row.tipo == 'Visura'){
    return' <span> - </span>';
  }else{
    return' <span><a href="../isernia_upload/bollo_istanza/'+ row.file_bi.split("/").pop() +'" target="_blank">Vedi file</a>\
    <button type="button" class="btn" data-toggle="modal" data-target="#estremiBi'+row.id_istanza+'" title="Copia identificativo bollo istanza"><i class="fas fa-clone"></i></button>\
          <div class="modal fade" id="estremiBi'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" >Numero Identificativo Bollo Istanza</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
            <input readonly="readonly" name="estremi_bi" id="estremi_bi'+row.id_istanza+'" value="'+row.estremi_bi+'"><br><br>\
            <button class="btn btn-primary" onclick="copyestremi(\'estremi_bi' + row.id_istanza + '\')" data-dismiss="modal">Copia identificativo</button>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div>\
          </div>\
        </div>\
      </div>\
    </span>';
  }
}

// funzione per visualizzare autocertificazione pagamento bollo cdu
function nameFormatterFile3(value, row) {
  if (row.tipo == 'Visura'){
    return' <span> - </span>';
  }else{
    if (row.n_bolli == 1){
      return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'" target="_blank">Vedi file</a>\
      <button type="button" class="btn" data-toggle="modal" data-target="#estremiBc'+row.id_istanza+'" title="Copia identificativo bollo CDU"><i class="fas fa-clone"></i></button>\
          <div class="modal fade" id="estremiBc'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" >Numero Identificativo Bollo CDU</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
            <input readonly="readonly" name="estremi_bc" id="estremi_bc'+row.id_istanza+'" value="'+row.estremi_bc+'"><br><br>\
            <button class="btn btn-primary" onclick="copyestremi(\'estremi_bc' + row.id_istanza + '\')" data-dismiss="modal">Copia identificativo</button>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div>\
          </div>\
        </div>\
      </div>\
      </span>';
    }else{
      if (row.file_bc != null){
        return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'" target="_blank">Vedi file</a></span>';
      }else{
        return' <span> - </span>';
      }
    }
  }
}

// funzione per visualizzare/aggiungere numero di bolli apre modal che richiama file numero_bolli.php
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

// funzione per caricare file del CDU apre modal che richiama file upload_cdu.php 
//******GESTIRE IN BASE A COLONNA TIPO, SE è VISURA VEDI VISURA ALTRIMENTI VEDI CDU COME PER DATI UTENTE**********
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
        return' <span><a href="../isernia_upload/cdu/'+ row.file_cdu.split("/").pop() +'" target="_blank">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalCdu'+row.id_istanza+'" title="Modifica file CDU"><i class="fas fa-file-upload"></i></button>\
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
        return' <span><a href="../isernia_upload/cdu/'+ row.file_cdu.split("/").pop() +'" target="_blank">Vedi file</a></span>';
      }
    }
}

// funzione per gestire notifica all'utente di doc scaduto richiama file doc_scaduto.php
function nameFormatterFile7(value, row) {
  if (row.doc_exp < new Date().toISOString().substring(0,10)){
    //var row_idx = $('table#usr tr').index();
    return'<span>SCADUTO</span><a id="sendemail" type="button" class="btn btn-info" href="doc_scaduto.php?idu='+row.id+'"><i class="fas fa-bell"></i></a>';
  }else{
    return row.doc_exp ;
  }
}

// funzione per evidenziare gli utenti con doc scaduto (viene richiamata nella tabel con data-row-style="rowStyle")
function rowStyle(row, index) {
  //console.log(row.doc_exp)
    if (row.doc_exp < new Date().toISOString().substring(0,10)) {
      return {
        css: {
          'background-color': 'yellow'
        }
      }
    }
    return {
      css: {
        'background-color': 'white'
      }
    }
}

// funzione per evidenziare le istanze terminate (viene richiamata nella tabel con data-row-style="rowStyle2")
function rowStyle2(row, index) {
  //console.log(row.doc_exp)
    if (row.terminato == 't') {
      return {
        css: {
          'background-color': 'lightgreen'
        }
      }
    }
    return {
      css: {
        'background-color': 'white'
      }
    }
}

</script>
<script>
  function copyestremi(rowid){
    console.log(rowid);
    var copyText = document.getElementById(rowid);
    console.log(copyText);
    copyText.select();
    document.execCommand("copy");
    //alert("Estremi: " + copyText.value);
  }
</script>


<?php
}
?>