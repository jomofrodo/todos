<!-- ###########################################################################  -->
<!-- Pop up layer to select a record from the Todos database -->
	    <div id="LayerRecordSelector" style="position:absolute; width:283px; height:147px; z-index:0; 
		left: 40px; top: 144px; 
		background-color: #CCCCCC; 
		layer-background-color: #CCCCCC; 
		border: 1px none #000000; 
		visibility: hidden"> 
          <table border=1 width="236" height="156">
            <tr>
      <td colspan=4> 
      Select Page: 
	<tr><td> Category: 
	<td colspan=2>
        todosSelectCats(IDX_ROOT_PAGEID,$cat_pid);
	<input type=submit value="Go">
     <tr><td>Page:
	<td colspan=2>
	<? 
	printSelect("rec_pid_slc",'tblTodos','tdURL','tdURL',$rec_pid,0,1,'',
		"AND tdType='idx3' AND tdPageID like '$cat_pid' AND tdClass='member'",
		0,'this.form.rec_pid.value=this.value;this.form.submit();'); ?>
        <td>
	<tr>
              <td colspan=3> 
                <div align="center">
                  <input type="button" name="Button" value="Done" onClick="
			MM_showHideLayers('LayerRecordSelector','','hide');
			//document.getElementById('slcClass').style.visibility='';
			">
                </div>

            </table>
	</div>

<!-- ###########################################################################  -->
    <div id="LayerUpload" style="position:absolute; width:283px; height:147px; z-index:0;
 		left: 140px; top: 164px;
 		background-color: #CCCCCC;
 		layer-background-color: #CCCCCC;
 		border: 1px none #000000;
 		visibility: hidden">
           <table border=1 width="236" height="156">
       <!--	## Upload File -->
 	<tr><td>
 	<b><input type=submit value="Upload" onclick=this.form.pAction.value=this.value></b>
 	<td colspan=3>
 		<input name="userfile" type=file size=45
 		onchange='
 				$fname = this.value;
 				this.form.ufile.value=this.value;
 			  	re = /^.*\\(\w+)$/;
 			  	re = /^(.*\\)(.*)$/;
 			  	re2 = /^(.*\/)(.*)$/;
 				$fname = $fname.replace(re,"$2");
 				#$rec_pid = this.form.rec_pid.value;
 			  	#$rec_pid = $rec_pid.replace(re2,"$1");
 				#this.form.rec_pid.value = $rec_pid + $fname;
 			'>
 	<tr>
               <td colspan=3>
                 <div align="center">
                   <input type="button" name="Button" value="Done" onClick="
                   			JM_toggleLayer('','LayerUpload');
                  	">
                 </div>
 	</table>
     </div>

     <? /* These are buttons to use with these layers
<!--  ################################################################ -->
<!--
         <!-- Div Toggle Buttons -->
         <input type="button" name="btnSelect" value="Record"
 		onClick="
 			JM_toggleLayer('','Layer1');
 			JM_toggleLayer('','slcClass');
 			//MM_showHideLayers('Layer1','','show');
 			//document.getElementById('slcClass').style.visibility='hidden';
 			">
         <input type="button" name="btnSelect" value="Upload"
 		onClick="
			JM_toggleLayer('','LayerUpload');
 			JM_toggleLayer('','slcClass');
 			//MM_showHideLayers('Layer1','','show');
 			//document.getElementById('slcClass').style.visibility='hidden';
 			">

<script language=javascript>
function checkSys(){
	//alert("javascript");
}
function setFName(e){
    // relies on presence of fields:
    //     f.ufile
    //     f.title
    //     f.rec_pid
    //
				$fname = e.value;
				//alert("fname:" + $fname);
				e.form.ufile.value=e.value;
			  	re = /^.*\\(\w+)$/;
			  	re = /^(.*\\)(.*)$/;
			  	re2 = /^(.*\/)(.*)$/;
				re3 = /\s/g;
				re4 = /\.(.*)/;
				$fname = $fname.replace(re,"$2");
				$ftitle = $fname;
				$ftitle = $ftitle.replace(re4," ($1)");
				e.form.title.value = $ftitle;
				$fname = $fname.replace(re3,"_");
				$fname = $fname.toLowerCase();
				$rec_pid = e.form.rec_pid.value;
			  	$rec_pid = $rec_pid.replace(re2,"$1");
				e.form.rec_pid.value = $rec_pid + $fname;
}
</script>
-->

    */      ?>