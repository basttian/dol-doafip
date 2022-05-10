<?php
/* Copyright (C) 2022 SuperAdmin
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

require_once  DOL_DOCUMENT_ROOT.'/custom/doafip/lib/doafip.lib.php';
require_once DOL_DOCUMENT_ROOT.'/custom/doafip/class/factudata.class.php';

/**
 * \file    doafip/class/actions_doafip.class.php
 * \ingroup doafip
 * \brief   Example hook overload.
 *
 * Put detailed description here.
 */

/**
 * Class ActionsDoafip
 */
class ActionsDoafip
{
	/**
	 * @var DoliDB Database handler.
	 */
	public $db;

	/**
	 * @var string Error code (or message)
	 */
	public $error = '';

	/**
	 * @var array Errors
	 */
	public $errors = array();


	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	public $msjbackend = "";

	/**
	 * Execute action
	 *
	 * @param	array			$parameters		Array of parameters
	 * @param	CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param	string			$action      	'add', 'update', 'view'
	 * @return	int         					<0 if KO,
	 *                           				=0 if OK but we want to process standard actions too,
	 *                            				>0 if OK and we want to replace standard actions.
	 */
	public function getNomUrl($parameters, &$object, &$action)
	{
		global $db, $langs, $conf, $user;

		$this->resprints = '
                <style>
                .block {
                  display: block;
                  width: 100%;
                  border: none;
                  background-color: var(--butactionbg);
                  padding: 14px 28px;
                  font-size: 16px;
                  cursor: pointer;
                  text-align: center;
                }
                </style>
                <div id="ModalFact" class="modal">
                <div class="modal-content">
                <div class="modal-header">
                <span class="close" onclick="CloseModal()" >&times;</span>
                <h3>'.$langs->trans('type').'</h3>
                </div>
                <div class="modal-body">
                <br>
                <button style="padding: 32px;" class="block btn-type-a">'.$langs->trans('typeA').'</button>
                <img style="display: none;" id="loader_a" src="../../custom/doafip/img/spinner.gif" height="25px">
                <p></p>
                <button style="padding: 32px;" class="block btn-type-b">'.$langs->trans('typeB').'</button>
                <img style="display: none;" id="loader_b" src="../../custom/doafip/img/spinner.gif" height="25px">
                <p></p>
                <button style="padding: 32px;" class="block btn-type-c">'.$langs->trans('typeC').'</button>
                <img style="display: none;" id="loader_c" src="../../custom/doafip/img/spinner.gif" height="25px">            
                </div>
                <div class="modal-footer"><p></p></div>
                <br>
                </div>
                </div>
                ';
		return 0;
		
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function doActions($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$error = 0; // Error counter

		/* print_r($parameters); print_r($object); echo "action: " . $action; */
		if (in_array($parameters['currentcontext'], array('invoicecard', 'globalcard'))) {	    // do something only for the context 'somecontext1' or 'somecontext2'
			// Do what you want here...
			// You can for example call global vars like $fieldstosearchall to overwrite them, or update database depending on $action and $_POST values.
		 
		    /*$facid=GETPOST('facid', 'int');
		    if ($action == 'selecttype') {
		        
		        echo '<script>';
		        echo 'console.log('.json_encode( $facid ).')';
		        echo '</script>';
		    }*/
		
		    ?>
		    
		    <script type="text/javascript">
			function CloseModal(){
				document.getElementById("ModalFact").style.display = "none";
				$("#ModalFact").hide();
			}
		    </script>
		    
		    <?php   
		}

		if (!$error) {
			$this->results = array('myreturn' => 777);
			$this->resprints = 'A text to show';
			return 0; // or return 1 to replace standard code
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}

	
	/**
	 * Overloading the doMassActions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function doMassActions($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$error = 0; // Error counter

		/* print_r($parameters); print_r($object); echo "action: " . $action; */
		if (in_array($parameters['currentcontext'], array('invoicecard', 'globalcard'))) {		// do something only for the context 'somecontext1' or 'somecontext2'
			foreach ($parameters['toselect'] as $objectid) {
				// Do action on each object id
			}
		}

		if (!$error) {
			$this->results = array('myreturn' => 999);
			$this->resprints = 'A text to show';
			return 0; // or return 1 to replace standard code
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}


	/**
	 * Overloading the addMoreMassActions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function addMoreMassActions($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$error = 0; // Error counter
		$disabled = 1;

		/* print_r($parameters); print_r($object); echo "action: " . $action; */
		if (in_array($parameters['currentcontext'], array('invoicecard', 'globalcard'))) {		// do something only for the context 'somecontext1' or 'somecontext2'
			//$this->resprints = '<option value="0"'.($disabled ? ' disabled="disabled"' : '').'>'.$langs->trans("DoafipMassAction").'</option>';
		}

		if (!$error) {
			return 0; // or return 1 to replace standard code
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}



	/**
	 * Execute action
	 *
	 * @param	array	$parameters     Array of parameters
	 * @param   Object	$object		   	Object output on PDF
	 * @param   string	$action     	'add', 'update', 'view'
	 * @return  int 		        	<0 if KO,
	 *                          		=0 if OK but we want to process standard actions too,
	 *  	                            >0 if OK and we want to replace standard actions.
	 */
	public function beforePDFCreation($parameters, &$object, &$action)
	{
		global $conf, $user, $langs;
		global $hookmanager;

		$outputlangs = $langs;

		$ret = 0; $deltemp = array();
		dol_syslog(get_class($this).'::executeHooks action='.$action);

		/* print_r($parameters); print_r($object); echo "action: " . $action; */
		if (in_array($parameters['currentcontext'], array('somecontext1', 'somecontext2'))) {		// do something only for the context 'somecontext1' or 'somecontext2'
		}

		return $ret;
	}

	/**
	 * Execute action
	 *
	 * @param	array	$parameters     Array of parameters
	 * @param   Object	$pdfhandler     PDF builder handler
	 * @param   string	$action         'add', 'update', 'view'
	 * @return  int 		            <0 if KO,
	 *                                  =0 if OK but we want to process standard actions too,
	 *                                  >0 if OK and we want to replace standard actions.
	 */
	public function afterPDFCreation($parameters, &$pdfhandler, &$action)
	{
		global $conf, $user, $langs;
		global $hookmanager;

		$outputlangs = $langs;

		$ret = 0; $deltemp = array();
		dol_syslog(get_class($this).'::executeHooks action='.$action);

		/* print_r($parameters); print_r($object); echo "action: " . $action; */
		if (in_array($parameters['currentcontext'], array('somecontext1', 'somecontext2'))) {
			// do something only for the context 'somecontext1' or 'somecontext2'
		}

		return $ret;
	}

	/**
	 * Overloading the loadDataForCustomReports function : returns data to complete the customreport tool
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function loadDataForCustomReports($parameters, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$langs->load("doafip@doafip");

		$this->results = array();

		$head = array();
		$h = 0;

		if ($parameters['tabfamily'] == 'doafip') {
			$head[$h][0] = dol_buildpath('/module/index.php', 1);
			$head[$h][1] = $langs->trans("Home");
			$head[$h][2] = 'home';
			$h++;

			$this->results['title'] = $langs->trans("Doafip");
			$this->results['picto'] = 'doafip@doafip';
		}

		$head[$h][0] = 'customreports.php?objecttype='.$parameters['objecttype'].(empty($parameters['tabfamily']) ? '' : '&tabfamily='.$parameters['tabfamily']);
		$head[$h][1] = $langs->trans("CustomReports");
		$head[$h][2] = 'customreports';

		$this->results['head'] = $head;

		return 1;
	}

	/**
	 * Overloading the restrictedArea function : check permission on an object
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int 		      			  	<0 if KO,
	 *                          				=0 if OK but we want to process standard actions too,
	 *  	                            		>0 if OK and we want to replace standard actions.
	 */
	public function restrictedArea($parameters, &$action, $hookmanager)
	{
		global $user;

		if ($parameters['features'] == 'myobject') {
			if ($user->rights->doafip->myobject->read) {
				$this->results['result'] = 1;
				return 1;
			} else {
				$this->results['result'] = 0;
				return 1;
			}
		}

		return 0;
	}

	/**
	 * Execute action completeTabsHead
	 *
	 * @param   array           $parameters     Array of parameters
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         'add', 'update', 'view'
	 * @param   Hookmanager     $hookmanager    hookmanager
	 * @return  int                             <0 if KO,
	 *                                          =0 if OK but we want to process standard actions too,
	 *                                          >0 if OK and we want to replace standard actions.
	 */
	public function completeTabsHead(&$parameters, &$object, &$action, $hookmanager)
	{
		global $langs, $conf, $user;

		if (!isset($parameters['object']->element)) {
			return 0;
		}
		if ($parameters['mode'] == 'remove') {
			// utilisé si on veut faire disparaitre des onglets.
			return 0;
		} elseif ($parameters['mode'] == 'add') {
			$langs->load('doafip@doafip');
			// utilisé si on veut ajouter des onglets.
			$counter = count($parameters['head']);
			$element = $parameters['object']->element;
			$id = $parameters['object']->id;
			// verifier le type d'onglet comme member_stats où ça ne doit pas apparaitre
			// if (in_array($element, ['societe', 'member', 'contrat', 'fichinter', 'project', 'propal', 'commande', 'facture', 'order_supplier', 'invoice_supplier'])) {
			if (in_array($element, ['context1', 'context2'])) {
				$datacount = 0;

				$parameters['head'][$counter][0] = dol_buildpath('/doafip/doafip_tab.php', 1) . '?id=' . $id . '&amp;module='.$element;
				$parameters['head'][$counter][1] = $langs->trans('DoafipTab');
				if ($datacount > 0) {
					$parameters['head'][$counter][1] .= '<span class="badge marginleftonlyshort">' . $datacount . '</span>';
				}
				$parameters['head'][$counter][2] = 'doafipemails';
				$counter++;
			}
			if ($counter > 0 && (int) DOL_VERSION < 14) {
				$this->results = $parameters['head'];
				// return 1 to replace standard code
				return 1;
			} else {
				// en V14 et + $parameters['head'] est modifiable par référence
				return 0;
			}
		}
	}

	/* Add here any other hooked methods... */
	
	public function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager)
	{
	    global $db, $conf, $user, $langs;
	    $error = 0; // Error counter

	    /* print_r($parameters); print_r($object); echo "action: " . $action; */
	    if (in_array($parameters['currentcontext'], array('invoicecard', 'globalcard'))) {	    // do something only for the context 'somecontext1' or 'somecontext2'
	        // Do what you want here...
	        // You can for example call global vars like $fieldstosearchall to overwrite them, or update database depending on $action and $_POST values.
	       
	        /*print '<a class="butAction" 
                      href="'.$_SERVER["PHP_SELF"].'?facid='.$object->id.'&action=selecttype " 
                      title="'.$langs->trans("textoboton").'">'.$langs->trans('btnfacturar').'</a>';*/
	        print '<div id="txtresult">';
	        print '</div>';
	        print '<br>';
	        
	       $factuData = new FactuData($db);
	        
	       print '<img style="display: none;" id="loader_button" src="../../custom/doafip/img/spinner.gif">';
	       if ($factuData->record_exists($object->id)) {
	           print '<span class="butAction btn-print" title="'.$langs->transcountrynoentities("Print","AR").'" ><span class="fa fa-print" style="color: white"></span> AFIP</span>';
	           $idFactudata = $factuData->searchIdAfipFromIdFacture($object->id); 
	       }
	       if (!$factuData->record_exists($object->id)) {
	           print '<span class="butAction btn-modal" title="'.$langs->trans("textoboton").'" >'.$langs->trans('btnfacturar').'</span>';
	       }      

	       ?>
	        				
<script type="text/javascript">
$( document ).ready(function() {
  
	         $('.btn-modal').on('click',function(event){
	        	event.preventDefault();
	        	this.blur();
				//$(".modal-body").html(id);
	        	$("#ModalFact").show();
			});

			$('.btn-type-a').on('click',function(event){
				event.preventDefault();
				$('.btn-type-a').blur();
				$('.btn-type-a').addClass('butActionRefused classfortooltip');
				$('.btn-type-a').attr('disabled', 'disabled');
	        	$('#loader_a').show();
				var facid = <?php echo $object->id; ?>;
				$.ajax({
			        type: 'POST',
			        url: '<?php echo DOL_URL_ROOT;?>/custom/doafip/doafipmodal.php?action=typeA',
			        data:{facid: facid},
			        success: function(data) {
			        	$('#loader_a').hide();
			        	$("#ModalFact").hide();
			        },
			        error:function(err){
			          alert("error"+JSON.stringify(err));
			        }
				 });
				 return false;
			});
			$('.btn-type-b').on('click',function(event){
	        	event.preventDefault();
	        	$('.btn-type-b').blur();
				$('.btn-type-b').addClass('butActionRefused classfortooltip');
				$('.btn-type-b').attr('disabled', 'disabled');
	        	$('#loader_b').show();
				var facid = <?php echo $object->id; ?>;
				$.ajax({
			        type: 'POST',
			        url: '<?php echo DOL_URL_ROOT;?>/custom/doafip/doafipmodal.php?action=typeB',
			        data:{facid: facid},
			        success: function(response) {
			        	$('#loader_b').hide();
			        	$("#ModalFact").hide();
			        
			        	try {
    		        		var jsonData = JSON.parse(response);
    						
    		        		switch (jsonData.success) {
    						case 1:
    							    $('#loader_button').show(); 
    								$('#txtresult').html("Factura realizada con exito.").addClass( "success1" );
    								setTimeout(window.location.reload(true), 1000);
    							break;
    						case 0:
    								$('#txtresult').html("Factura ya emitida.").addClass( "error0" );
    							break;
    						case 666:
		        					$('#txtresult').html("ERROR "+jsonData.msj).addClass("error0 highlight");
		        				break;
    						default:
    							break;
    						}	
						} catch (e) {
							$('#txtresult').html("ERROR (666) send me an angel. ").addClass("error0 highlight");
						}
			            
			        },
			        error:function(err){
			          alert("error"+JSON.stringify(err));
			        }
				 });
			});
			$('.btn-type-c').on('click',function(event){
	        	event.preventDefault();
	        	$('.btn-type-c').blur();
				$('.btn-type-c').addClass('butActionRefused classfortooltip');
				$('.btn-type-c').attr('disabled', 'disabled');
	        	$('#loader_c').show();
				var facid = <?php echo $object->id; ?>;
				$.ajax({
			        type: 'POST',
			        url: '<?php echo DOL_URL_ROOT;?>/custom/doafip/doafipmodal.php?action=typeC',
			        data:{facid: facid},
			        success: function(data) {
			        	$('#loader_c').hide();
			        	$("#ModalFact").hide();
			        },
			        error:function(err){
			          alert("error"+JSON.stringify(err));
			        }
				 });
			});

			function openWindows(url){
				var params = "location=no,toolbar=no,menubar=no,width=1200,height=1200,left=100,top=100";
        		var newWindow = window.open('<?php echo DOL_URL_ROOT;?>/document.php?modulepart=doafip&attachment=1&file='+url,'Afip', params);
			    return newWindow;
			}

			$('.btn-print').on('click',function(event){
	        	event.preventDefault();
	        	this.blur();
	        	var factuDataId = <?php echo empty($idFactudata) ? 0 :  $idFactudata ;?>;
                var facid = <?php echo $object->id; ?>;
	        	//console.log(factuDataId);
	        	$.ajax({
		        type: 'POST',
		        //url: '<?php echo DOL_URL_ROOT;?>/custom/doafip/doafippdf.php?action=printfactur',
                url: '<?php echo DOL_URL_ROOT;?>/custom/doafip/factudata_print.php?action=printfactur',
		        data:{factuDataId: factuDataId, facid: facid},
		        success: function(data){
		        		var urlDown = JSON.parse(data);
		        		openWindows(urlDown.url);
                        console.log(data);
			        	},
			        error:function(err){
				       alert("error"+JSON.stringify(err));
				    }
		        });
	     	});

});
</script>
	        <?php 
	        
	    }
	    
	    if (!$error) {
	        //$this->results = array('myreturn' => 999);
	        //$this->resprints = 'A text to show';
	        try {
	            $factobj = new FactuData($db);
	            $afip = new Afip(array('CUIT' => $factobj->cuilit ));
	            $server_status = $afip->ElectronicBilling->GetServerStatus();
	            if ($server_status->AppServer == 'OK' && $server_status->DbServer == 'OK' && $server_status->AuthServer == 'OK') {
	                if (!empty($factobj->cuilit)) {
	                   //setEventMessages($langs->trans('msjAfipServerStatus'),null,'mesgs');
	                }else{
	                   setEventMessages($langs->trans('msjErrorServerStatusData'),null,'errors');
	                }
	            }
	        } catch (Exception $e) {
	            ?>
    	         	<script type="text/javascript">
    	         	$('.btn-modal').addClass('butActionRefused classfortooltip');
        			$('.btn-modal').attr('disabled', 'disabled');
    	         	$('.btn-modal').on('click',function(event){
    		        	event.preventDefault();
    		        	$("#ModalFact").hide();
    	         	});
    	         	</script>
	         	<?php
	            //print_r($e);
	            setEventMessages($langs->trans('msjErrorServerStatusConn'),null,'errors');
	        }
	        return 0; // or return 1 to replace standard code
	    } else {
	        $this->errors[] = 'Error message';
	        return -1;
	    }
	}
	
	
	
}
