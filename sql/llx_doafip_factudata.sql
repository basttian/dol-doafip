-- Copyright (C) ---Put here your own copyright and developer email---
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see https://www.gnu.org/licenses/.


CREATE TABLE llx_doafip_factudata(
	-- BEGIN MODULEBUILDER FIELDS
	rowid integer AUTO_INCREMENT PRIMARY KEY NOT NULL, 
	ref varchar(128) NOT NULL, 
	fk_soc integer, 
	fk_project integer, 
	note_public text, 
	note_private text, 
	date_creation datetime NOT NULL, 
	tms timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
	fk_user_creat integer, 
	fk_user_modif integer, 
	last_main_doc varchar(255), 
	import_key varchar(14), 
	model_pdf varchar(255), 
	status smallint NOT NULL, 
	CantReg integer, 
	PtoVta integer, 
	CbteTipo integer, 
	Concepto integer, 
	DocTipo integer, 
	DocNro integer, 
	CbteDesde integer, 
	CbteHasta integer, 
	CbteFch varchar(255), 
	ImpTotal double(24,2), 
	ImpTotConc double(24,2), 
	ImpNeto double(24,2), 
	ImpOpEx double(24,2), 
	ImpIVA double(24,2), 
	ImpTrib double(24,2), 
	FchServDesde varchar(255), 
	FchServHasta varchar(255), 
	FchVtoPago varchar(255), 
	MonId varchar(128), 
	MonCotiz integer, 
	CbtesAsocTipo integer, 
	CbtesAsocPtoVta integer, 
	CbtesAsocNro integer, 
	CbtesAsocCuit varchar(11), 
	TributosId integer, 
	TributosDesc varchar(80), 
	TributosBaseImp double(24,2), 
	TributosAlic double(24,2), 
	TributosImporte double(24,2), 
	IvaId text, 
	IvaBaseImp text, 
	IvaImporte text, 
	OpcionalesId integer, 
	OpcionalesValor varchar(255), 
	CompradoresDocTipo integer, 
	CompradoresDocNro varchar(80), 
	CompradoresPorcentaje double(24,2), 
	ResResultado varchar(128), 
	ResCodAutorizacion varchar(255), 
	ResEmisionTipo varchar(128), 
	ResFchVto varchar(128), 
	ResFchProceso varchar(128), 
	ResBarCode varchar(255), 
	fk_facture integer NOT NULL
	-- END MODULEBUILDER FIELDS
) ENGINE=innodb;
