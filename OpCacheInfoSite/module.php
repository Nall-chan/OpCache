<?php

/**
 * @addtogroup opcache
 * @{
 *
 * @package       OPCACHE
 * @file          module.php
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2018 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.00
 */
require_once(__DIR__ . "/libs/OpCacheTraits.php");  // diverse Klassen
require_once(__DIR__ . "/libs/OpCacheTraits.php");  // diverse Klassen

/**
 * OpCacheModule ist die Klasse für die Darstellung von Infomationen des PHP OpCache in IPS.
 * Erweitert ipsmodule
 *
 */
class OpCacheInfoSite extends IPSModule
{

    use VariableHelper,
        DebugHelper;
    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function Create()
    {
        parent::Create();
    }

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function Destroy()
    {
        parent::Destroy();
    }

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function ApplyChanges()
    {
        $this->RegisterMessage(0, IPS_KERNELSTARTED);

        parent::ApplyChanges();


        if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->RegisterHook();
        }
    }


    ################## PUBLIC
    /**
     * IPS-Instanz Funktion OPCACHE_Update.
     *
     * @access public
     * @return bool True wenn Befehl erfolgreich ausgeführt wurde, sonst false.
     */
    public function Update()
    {
        return false;
    }

}

/** @} */
