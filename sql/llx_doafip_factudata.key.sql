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


-- BEGIN MODULEBUILDER INDEXES
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_rowid (rowid);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ref (ref);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_fk_soc (fk_soc);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_fk_project (fk_project);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_status (status);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ResResultado (ResResultado);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ResCodAutorizacion (ResCodAutorizacion);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ResEmisionTipo (ResEmisionTipo);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ResFchVto (ResFchVto);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ResFchProceso (ResFchProceso);
ALTER TABLE llx_doafip_factudata ADD INDEX idx_doafip_factudata_ResBarCode (ResBarCode);
-- END MODULEBUILDER INDEXES

--ALTER TABLE llx_doafip_factudata ADD UNIQUE INDEX uk_doafip_factudata_fieldxy(fieldx, fieldy);

--ALTER TABLE llx_doafip_factudata ADD CONSTRAINT llx_doafip_factudata_fk_field FOREIGN KEY (fk_field) REFERENCES llx_doafip_myotherobject(rowid);

