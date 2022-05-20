# DOAFIP FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)

## Features

Description of the module...
<br />
Facturar a Afip desde las facturas generadas en Dolibarr
<br />
<b>Usa libreria para conectarse a los web services de Afip </b>[ AfipSDK / afip.php ](https://github.com/AfipSDK/afip.php/blob/master/README.md)
<br />
Antes de comenzar .. <a href="https://www.afip.gob.ar/ws/WSAA/WSAA.ObtenerCertificado.pdf" target="_blank">Certificado Digital</a>
<p>Sobreescribir los archivos Key y Cert de la carpeta ..doafip/lib/afip/Afip_res</p>
<ul>
<li>Activar el modulo en Dolibarr</li>
<li>Ir a configuracion del modulo y activar solo la factura typo B</li>
<li>El modulo necesita el CUIT de configuracion del sistema Dolibar</li>
</ul>

<br />

<!--
![Screenshot doafip](img/screenshot_doafip.png?raw=true "Doafip"){imgmd}
-->

Other external modules are available on [Dolistore.com](https://www.dolistore.com).

## Translations

Translations can be completed manually by editing files into directories *langs*.

<!--
This module contains also a sample configuration for Transifex, under the hidden directory [.tx](.tx), so it is possible to manage translation using this service.

For more informations, see the [translator's documentation](https://wiki.dolibarr.org/index.php/Translator_documentation).

There is a [Transifex project](https://transifex.com/projects/p/dolibarr-module-template) for this module.
-->

<!--

## Installation

### From the ZIP file and GUI interface

- If you get the module in a zip file (like when downloading it from the market place [Dolistore](https://www.dolistore.com)), go into
menu ```Home - Setup - Modules - Deploy external module``` and upload the zip file.

Note: If this screen tell you there is no custom directory, check your setup is correct:

- In your Dolibarr installation directory, edit the ```htdocs/conf/conf.php``` file and check that following lines are not commented:

    ```php
    //$dolibarr_main_url_root_alt ...
    //$dolibarr_main_document_root_alt ...
    ```

- Uncomment them if necessary (delete the leading ```//```) and assign a sensible value according to your Dolibarr installation

    For example :

    - UNIX:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = '/var/www/Dolibarr/htdocs/custom';
        ```

    - Windows:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = 'C:/My Web Sites/Dolibarr/htdocs/custom';
        ```

### From a GIT repository

- Clone the repository in ```$dolibarr_main_document_root_alt/doafip```

```sh
cd ....../custom
git clone git@github.com:gitlogin/doafip.git doafip
```

### <a name="final_steps"></a>Final steps

From your browser:

  - Log into Dolibarr as a super-administrator
  - Go to "Setup" -> "Modules"
  - You should now be able to find and enable the module

-->

## Licenses


### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.
