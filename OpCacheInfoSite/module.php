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
require_once(__DIR__ . "/../libs/OpCacheTraits.php");  // diverse Klassen

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
        $this->RegisterPropertyString('SubmodulePath', 'opcache-status/opcache.php');
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
            $this->RegisterHook('/hook/Opcache');
        }
    }

    public function GetConfigurationForm()
    {
        $isEnabled = @IPS_GetOption('OPcacheSupport');
        $Url = $this->GetURL();
        $Form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        if (!$isEnabled) {
            $Warning = array(
                'type'  => 'Label',
                'label' => 'OPCache Support is not enabled in IPS!'
            );
            $Button = array(
                'label'   => "Enable OPCache",
                'type'    => "Button",
                'onClick' =>
                '$result = @IPS_SetOption("OPcacheSupport",1);'
                . 'if ($result) echo "' . $this->Translate('Please restart IPS to activate OPCache!') . '";'
                . 'else echo "' . $this->Translate('This Version of IPS not support OPCache.') . '"'
            );
            $Form['actions'][] = $Warning;
            $Form['actions'][] = $Button;
        }

        if ($Url != "") {
            $Button = array(
                'onClick' => $Url,
                'label'   => "Open Webhook",
                'type'    => "Button");
        } else {
            $Button = array(
                'type'  => 'Label',
                'label' => 'Webhook: <IP>:<PORT>/hook/Opcache'
            );
        }
        $Form['actions'][] = $Button;
        return json_encode($Form);
    }

    protected function GetURL()
    {
        $ids = IPS_GetInstanceListByModuleID("{9486D575-BE8C-4ED8-B5B5-20930E26DE6F}");
        if (sizeof($ids) > 0) {
            if (IPS_GetInstance($ids[0])['InstanceStatus'] == 102) {
                return CC_GetURL($ids[0]) . "/hook/Opcache";
            }
        }
        return "";
    }

    protected function RegisterHook($WebHook)
    {
        $ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}");
        if (sizeof($ids) > 0) {
            $hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true);
            $found = false;
            foreach ($hooks as $index => $hook) {
                if ($hook['Hook'] == $WebHook) {
                    if ($hook['TargetID'] == $this->InstanceID) {
                        return;
                    }
                    $hooks[$index]['TargetID'] = $this->InstanceID;
                    $found = true;
                }
            }
            if (!$found) {
                $hooks[] = Array("Hook" => $WebHook, "TargetID" => $this->InstanceID);
            }
            $this->SendDebug('hook', $hooks, 0);
            IPS_SetProperty($ids[0], "Hooks", json_encode($hooks));
            IPS_ApplyChanges($ids[0]);
        }
    }

    protected function ProcessHookdata()
    {
        $path = $this->ReadPropertyString('SubmodulePath');
        include(__DIR__ . '/../libs/' . $path);
    }

}

/** @} */
