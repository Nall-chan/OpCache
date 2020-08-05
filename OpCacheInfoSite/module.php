<?php

declare(strict_types=1);
/**
 * @addtogroup opcache
 * @{
 *
 * @file          module.php
 *
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2020 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 *
 * @version       2.01
 */
require_once __DIR__ . '/../libs/OpCacheTraits.php';  // diverse Klassen

/**
 * OpCacheModule ist die Klasse für die Darstellung von Informationen des PHP OpCache in IPS.
 * Erweitert ipsmodule.
 */
class OpCacheInfoSite extends IPSModule
{
    use \OpCacheModule\WebhookHelper;
    use \OpCacheModule\VariableProfileHelper;
    use \OpCacheModule\DebugHelper;

    /**
     * Interne Funktion des SDK.
     */
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString('SubmodulePath', 'opcache-status/opcache.php');
    }

    /**
     * Interne Funktion des SDK.
     */
    public function Destroy()
    {
        if (!IPS_InstanceExists($this->InstanceID)) {
            $this->UnregisterHook('/hook/Opcache' . $this->InstanceID);
        }
        parent::Destroy();
    }

    /**
     * Interne Funktion des SDK.
     */
    public function ApplyChanges()
    {
        parent::ApplyChanges();

        if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->RegisterHook('/hook/Opcache' . $this->InstanceID);
        }
    }

    public function GetConfigurationForm()
    {
        $isEnabled = @IPS_GetOption('OPcacheSupport');
        $Form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        if (!$isEnabled) {
            $Warning = [
                'type'  => 'Label',
                'label' => 'OPCache Support is not enabled in IPS!'
            ];
            $Button = [
                'label'   => 'Enable OPCache',
                'type'    => 'Button',
                'onClick' => '$result = @IPS_SetOption("OPcacheSupport",1);'
                . 'if ($result) echo "' . $this->Translate('Please restart IPS to activate OPCache!') . '";'
                . 'else echo "' . $this->Translate('This Version of IPS not support OPCache.') . '"'
            ];
            $Form['actions'][] = $Warning;
            $Form['actions'][] = $Button;
        }

        $Button = [
            'onClick' => 'echo "/hook/Opcache' . $this->InstanceID . '";',
            'label'   => 'Open Webhook',
            'type'    => 'Button',
            'link'    => true];
        $Form['actions'][] = $Button;
        return json_encode($Form);
    }

    protected function ProcessHookdata()
    {
        $path = $this->ReadPropertyString('SubmodulePath');
        include __DIR__ . '/../libs/' . $path;
    }
}

/* @} */
