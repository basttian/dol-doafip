<?php
/* Copyright (C) 2004-2017  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2021  Frédéric France     <frederic.france@netlogic.fr>
 * Copyright (C) 2022 SuperAdmin
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

/**
 * \file    doafip/core/boxes/doafipwidget1.php
 * \ingroup doafip
 * \brief   Widget provided by Doafip
 *
 * Put detailed description here.
 */

require_once DOL_DOCUMENT_ROOT."/core/boxes/modules_boxes.php";


/**
 * Class to manage the box
 *
 * Warning: for the box to be detected correctly by dolibarr,
 * the filename should be the lowercase classname
 */
class doafipwidget1 extends ModeleBoxes
{
	/**
	 * @var string Alphanumeric ID. Populated by the constructor.
	 */
	public $boxcode = "doafipbox";

	/**
	 * @var string Box icon (in configuration page)
	 * Automatically calls the icon named with the corresponding "object_" prefix
	 */
	public $boximg = "doafip@doafip";

	/**
	 * @var string Box label (in configuration page)
	 */
	public $boxlabel;

	/**
	 * @var string[] Module dependencies
	 */
	public $depends = array('doafip');

	/**
	 * @var DoliDb Database handler
	 */
	public $db;

	/**
	 * @var mixed More parameters
	 */
	public $param;

	/**
	 * @var array Header informations. Usually created at runtime by loadBox().
	 */
	public $info_box_head = array();

	/**
	 * @var array Contents informations. Usually created at runtime by loadBox().
	 */
	public $info_box_contents = array();

	/**
	 * @var string 	Widget type ('graph' means the widget is a graph widget)
	 */
	public $widgettype = 'graph';

	public $num;
	
	/**
	 * Constructor
	 *
	 * @param DoliDB $db Database handler
	 * @param string $param More parameters
	 */
	public function __construct(DoliDB $db, $param = '')
	{
		global $user, $conf, $langs;
		$langs->load("boxes");
		$langs->load('doafip@doafip');

		parent::__construct($db, $param);

		$this->boxlabel = $langs->transnoentitiesnoconv("MyWidget");

		$this->param = $param;

		//$this->enabled = $conf->global->FEATURES_LEVEL > 0;         // Condition when module is enabled or not
		//$this->hidden = ! ($user->rights->doafip->myobject->read);   // Condition when module is visible by user (test on permission)
		
	}


	/**
	 * Load data into info_box_contents array to show array later. Called by Dolibarr before displaying the box.
	 *
	 * @param int $max Maximum number of records to load
	 * @return void
	 */
	public function loadBox($max = 5)
	{
		global $langs;

		// Use configuration value for max lines count
		$this->max = $max;
		
		include_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
		include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
		include_once DOL_DOCUMENT_ROOT."/custom/doafip/class/factudata.class.php";
		
		$facturestatic = new Facture($this->db);
		$societestatic = new Societe($this->db);
		$doafipfacturestatic = new FactuData($this->db);
		
        
		//dol_include_once("/doafip/class/doafip.class.php");
		
		//$dataAfip = new FactuData($this->db);
		
		/*// Populate the head at runtime
		$text = $langs->trans("DoafipBoxDescription", $max);
		$this->info_box_head = array(
		    // Title text
		    'text' => $text,
		    // Add a link
		    'sublink' => 'http://example.com',
		    // Sublink icon placed after the text
		    'subpicto' => 'object_doafip@doafip',
		    // Sublink icon HTML alt text
		    'subtext' => '',
		    // Sublink HTML target
		    'target' => '',
		    // HTML class attached to the picto and link
		    'subclass' => 'center',
		    // Limit and truncate with "…" the displayed text lenght, 0 = disabled
		    'limit' => 0,
		    // Adds translated " (Graph)" to a hidden form value's input (?)
		    'graph' => false
		);*/
		
		//$text = $langs->trans("BoxTitleLast".(!empty($conf->global->MAIN_LASTBOX_ON_OBJECT_DATE) ? "" : "Modified")."CustomerBills", $max);
		$text = $langs->trans("DoafipBoxDescription", $max);
		$this->info_box_head = array(
		    'text' => $text ,
		    'limit'=> dol_strlen($text),
		);
		
		$this->db->begin();
	    $query = "SELECT df.rowid, df.ref as reference , df.status, df.fk_soc, df.fk_facture, s.nom, f.ref, ResResultado, ResEmisionTipo, ResCodAutorizacion, ResFchVto,ResFchProceso, ResBarCode FROM llx_doafip_factudata AS df INNER JOIN llx_facture AS f ON df.fk_facture = f.rowid INNER JOIN llx_societe AS s ON  df.fk_soc = s.rowid";
	    $query .= " ORDER BY df.rowid desc";
	    $query .= $this->db->plimit($max, 0);
	    
	    $result = $this->db->query($query);
	    if ($result) {
	        $this->db->commit();
	        $num = $this->db->num_rows($result);
	        $line = 0;
	        while ($line < $num) {
	            $objp = $this->db->fetch_object($result);
	            
	            $facturestatic->id = $objp->fk_soc;
	            $facturestatic->ref = $objp->ref;
	            $societestatic->id = $objp->fk_facture;
	            $societestatic->name = $objp->nom;
	            
	            $doafipfacturestatic->id = $objp->rowid;
	            $doafipfacturestatic->ref = $objp->reference;
	            $doafipfacturestatic->ResResultado = $objp->ResResultado;
	            $doafipfacturestatic->ResEmisionTipo = $objp->ResEmisionTipo;
	            $doafipfacturestatic->ResCodAutorizacion = $objp->ResCodAutorizacion;
	            $doafipfacturestatic->ResFchVto = $this->db->jdate($objp->ResFchVto);
	            $doafipfacturestatic->ResFchProceso = $objp->ResFchProceso;
	            $doafipfacturestatic->status = $objp->status;
	            
	            $this->info_box_contents[$line][] = array(
	                'td' => 'class="left" width="%30"',
	                'text' => $doafipfacturestatic->getNomUrl(1),
	                'text2' => " ".$doafipfacturestatic->ResEmisionTipo." Cod Aut: ".$doafipfacturestatic->ResCodAutorizacion." Fch Vto: ".dol_print_date($doafipfacturestatic->ResFchVto),
	                'asis' => 1,
	            );
	            $this->info_box_contents[$line][] = array(
	                'td' => 'class="left" width="%30"',
	                'text' => $societestatic->getNomUrl(1),
	                'asis' => 1,
	            );
	            $this->info_box_contents[$line][] = array(
	                'td' => 'class="left" width="%30"',
	                'text' => $facturestatic->getNomUrl(1),
	                'asis' => 1,
	            );
	            $this->info_box_contents[$line][] = array(
	                'td' => 'class="right" width="%10"',
	                'text' => $doafipfacturestatic->LibStatut($doafipfacturestatic->status, 3),
	                'asis' => 1,
	            );
	            $line++;
	        }
	        if ($num == 0) {
	            $this->info_box_contents[$line][0] = array(
	                'td' => 'class="center"',
	                'text'=>$langs->trans("NoRecordedInvoices"),
	            );
	        }
	    }

		/*
	
		// Populate the contents at runtime
		$this->info_box_contents = array(
		    
			0 => array( // First line
				0 => array( // First Column
					//  HTML properties of the TR element. Only available on the first column.
					'tr' => 'class="left"',
					// HTML properties of the TD element
					'td' => '',

					// Main text for content of cell
				    //'text' => 'First cell of first line',
					// Link on 'text' and 'logo' elements
				    //'url' => 'http://example.com',
					// Link's target HTML property
				    //'target' => '_blank',
					// Fist line logo (deprecated. Include instead logo html code into text or text2, and set asis property to true to avoid HTML cleaning)
				    //'logo' => 'monmodule@monmodule',
					// Unformatted text, added after text. Usefull to add/load javascript code
				    //'textnoformat' => '',
					// Main text for content of cell (other method)
				    //'text2' => '<p><strong>Another text</strong></p>',

					// Truncates 'text' element to the specified character length, 0 = disabled
				    //'maxlength' => 0,
					// Prevents HTML cleaning (and truncation)
				    //'asis' => false,
					// Same for 'text2'
					//'asis2' => true
				),
				1 => array( // Another column
					// No TR for n≠0
				    //'td' => '',
				    //'text' => 'Second cell',
				)
			),
		    
		    
			0 => array( // Another line
				0 => array( // TR
					'tr' => 'class="left"',
				    'text' => $objp->nom
				),
				1 => array( // TR
					'tr' => 'class="left"',
				    'text' => $objp->ref
				),
			    2 => array( // TR
			        'tr' => 'class="left"',
			        'text' => $objp->ResResultado
			    ),
			    3 => array( // TR
			        'tr' => 'class="left"',
			        'text' => $objp->ResEmisionTipo
			    ),
			    4 => array( // TR
			        'tr' => 'class="left"',
			        'text' => $objp->ResFchVto
			    ),
			    5 => array( // TR
			        'tr' => 'class="left"',
			        'text' => $objp->ResFchProceso
			    ),
			    6 => array( // TR
			        'tr' => 'class="left"',
			        'text' => $objp->ResBarCode
			    )
			),
		  
		);*/
	   
	}

	/**
	 * Method to show box. Called by Dolibarr eatch time it wants to display the box.
	 *
	 * @param array $head       Array with properties of box title
	 * @param array $contents   Array with properties of box lines
	 * @param int   $nooutput   No print, only return string
	 * @return string
	 */
	public function showBox($head = null, $contents = null, $nooutput = 0)
	{
		// You may make your own code here…
		// … or use the parent's class function using the provided head and contents templates
		return parent::showBox($this->info_box_head, $this->info_box_contents, $nooutput);
	}
}