<?php

/**
 *	\file       doafip/doafipindex.php
 *	\ingroup    doafip
 *	\brief      Home page of doafip Select
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--; $j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) {
	$res = @include "../main.inc.php";
}
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once 'lib/doafip.lib.php';
require_once 'class/factudata.class.php';


// Load translation files required by the page
$langs->loadLangs(array("doafip@doafip"));
$action = GETPOST('action', 'aZ09');
// Security check
//if (!$user->rights->doafip->myobject->read) {
// 	accessforbidden();
//}

$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0) {
    $action = '';
    $socid = $user->socid;
}

/*
 * Actions
*/

$facid = GETPOST('facid', 'int');
$afip = new Afip(array('CUIT' => $conf->global->MAIN_INFO_SIREN ));

$invoice = new Facture($db);
$invoice->fetch($facid);
$lines = $invoice->lines;

$client = new Societe($db);
$client->fetch($invoice->socid);

$factobj = new FactuData($db);
$voucherNumberFromFactureExtrafield = $invoice->array_options['options_voucher_number'];

if ($voucherNumberFromFactureExtrafield===NULL) {
	$last_voucher = $afip->ElectronicBilling->GetLastVoucher((int)$conf->global->DOAFIP_MYPARAM0,6);//obtenemos la ultima factura tipo B
    $valfac = $last_voucher + 1;
	$voucher_info = $afip->ElectronicBilling->GetVoucherInfo($valfac,(int)$conf->global->DOAFIP_MYPARAM0,6);
}

/**
 * 
 * @param int $id de factura
 * @return array[] alicuotas totalizadas
 */
function AlicuotasIVA($f) {
    global $db;
    $factobj = new FactuData($db);
    $invoice = new Facture($db);
    $invoice->fetch($f);
    $lines = $invoice->lines;
    $num = count($lines);
    for ($i = 0; $i < $num; $i++) {
            $aliCuota[$i] = array(
                'Id' 	  => $factobj->tipoIva($lines[$i]->tva_tx), // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                'BaseImp' => $lines[$i]->total_ht, // Base imponible
                'Importe' => $lines[$i]->total_tva,  // Importe
            );
       
        
    }; 
    

//return $aliCuota;
//exit;
    $result = array();
    foreach($aliCuota as $l) {
     $repeat=false;
     for($i=0;$i<count($result);$i++)
     {
         if($result[$i]['Id'] == $l['Id'])
        {
            $result[$i]['Id'] = $l['Id'];
            $result[$i]['BaseImp']  += $l['BaseImp'];
            $result[$i]['Importe'] += $l['Importe'];
            $repeat=true;
            break;
        } 
           
     }  
         if($repeat==false){
             $result[] = array('Id'=>$l['Id'],'BaseImp'=> $l['BaseImp'] ,'Importe' => $l['Importe']);  
         }

    }
    
    return $result;
    //exit;
};


try {  
		///////
        if ($action == 'typeA' && !empty($facid)  ) {
           echo "A ".$facid;   
        };
        ///////
        if ($action == 'typeB' && !empty($facid) && !empty($user->rights->doafip->factudata->write)) {
            //echo "B ".$facid;

                $data = array(
                    'CantReg' 	   => 1,  // Cantidad de comprobantes a registrar
					'PtoVta' 	   => (int)$conf->global->DOAFIP_MYPARAM0,  // Punto de venta in global conf
                    'CbteTipo' 	   => 6,  // Tipo de comprobante (ver tipos disponibles)
                    'Concepto' 	   => 3,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                    'DocTipo' 	   => empty($client->idprof1)?99:80, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles 86 CUIL 80 CUIT)
                    'DocNro' 	   => empty($client->idprof1)?0:$factobj->docSoc($invoice->socid),  // Número de documento del comprador (0 consumidor final)
                    'CbteDesde'    => $valfac,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                    'CbteHasta'    => $valfac,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                    'CbteFch' 	   => intval(date('Ymd')),//dol_print_date($invoice->date_creation,"%Y%m%d"), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                    'ImpTotal' 	   => $invoice->total_ttc, // Importe total del comprobante
                    'ImpTotConc'   => 0,   // Importe neto no gravado
                    'ImpNeto' 	   => $invoice->total_ht, // Importe neto gravado
                    'ImpOpEx' 	   => 0,   // Importe exento de IVA
                    'ImpIVA' 	   => $invoice->total_tva,  //Importe total de IVA
                    'ImpTrib' 	   => 0,   //Importe total de tributos
                    'FchServDesde' => dol_print_date($invoice->date_creation,"%Y%m%d"), // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                    'FchServHasta' => dol_print_date($invoice->date_creation,"%Y%m%d"), // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                    'FchVtoPago'   => intval(date('Ymd')), // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                    'MonId' 	   => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
                    'MonCotiz' 	   => 1,     // Cotización de la moneda usada (1 para pesos argentinos)
                    'Iva'          => AlicuotasIVA($facid), // (Opcional) Alícuotas asociadas al comprobante);
                );
                
            //print_r($data);
            //exit;
          
            //send data afip server
            $res = $afip->ElectronicBilling->CreateVoucher($data);
			$voucher_info = $afip->ElectronicBilling->GetVoucherInfo($valfac,(int)$conf->global->DOAFIP_MYPARAM0,6);
			if (!empty($res['CAE'])) {
				//Guardar en tabla doafip
            	$factobj->createFactureAfip($user, $data,(int)$invoice->socid,(int)$facid);
			};
            //Datos para crear el codigo de barra. Se agradece al autor del metodo verificadorBase10
            $cuit = str_replace("-","",$factobj->cuilit);
            $tipoCompro = str_pad($data['CbteTipo'], 2, "0", STR_PAD_LEFT);
            $puntoVenta = str_pad($data['PtoVta'], 4, "0", STR_PAD_LEFT);
            $cae = $res['CAE'];
            $fechaVtoCae = str_replace("-","",$res['CAEFchVto']);
            $codigo = $cuit.$tipoCompro.$puntoVenta.$cae.$fechaVtoCae;
            $codigoBar = $factobj->verificadorBase10($codigo);
			if (!empty($res['CAE'])) {
				// Agrego el valor del voucher al campo extra de factura
				$factobj->addExtrafieldsFactureData($facid,$valfac);
        		// Actualizo el registro con la devolucion desde la afip  -> Cod.Autorizacion, Fcha Vto, CAE etc..
            	$factobj->updateFactureAfip($user, $valfac ,$voucher_info->Resultado,$voucher_info->CodAutorizacion,$voucher_info->EmisionTipo,$voucher_info->FchVto,$voucher_info->FchProceso,$codigoBar);
            };
            echo json_encode(array('success' => 1));
            
        };
        //////
        if ($action == 'typeC' && !empty($facid)  ) {
            echo "C ".$facid; 
        };
} catch (Exception $e) {
    echo json_encode(array('success' => 666,'code'=>$e->getCode(),'msj'=> $e->getMessage()));
}
