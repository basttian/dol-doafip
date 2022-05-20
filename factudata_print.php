<?php

/**
 *	\file       doafip/doafippdf.php
 *	\ingroup    doafip
 *	\brief      PDF
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
require_once  DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
require_once ('core/modules/printing/pdf_afip.modules.php');

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

$factuDataId = GETPOST('factuDataId','int');
$facid = GETPOST('facid','int');
global $db;
$doafip = new FactuData($db);
$doafip->fetch($factuDataId);



/*
 * Actions
*/

try {
    if ($action == 'printfactur' && !empty($factuDataId)  ) {
        
        $invoice = new Facture($db);
        $invoice->fetch($facid);
        $printing = new pdf_afip($db);
        $printing->write_file($invoice , $langs->loadLangs(array('bills', 'companies', 'compta', 'products', 'banks', 'main', 'withdrawals')) , '', 0, 0, 0, $factuDataId);
    }
    
    if($action == 'geturltkposbtn' && !empty($facid)){
    	$invoice = new Facture($db);
    	$invoice->fetch($facid);
    	$factuData = new FactuData($db);
    	$idFactudata = $factuData->searchIdAfipFromIdFacture($facid);

    	$printing = new pdf_afip($db);
    	$printing->write_file($invoice , $langs->loadLangs(array('bills', 'companies', 'compta', 'products', 'banks', 'main', 'withdrawals')) , '', 0, 0, 0, $idFactudata);
    }
    
} catch (Exception $e) {
    print_r($e);
}









































