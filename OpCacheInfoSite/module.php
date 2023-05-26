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
 * @version       2.02
 */
require_once __DIR__ . '/../libs/OpCacheTraits.php';  // diverse Klassen

/**
 * OpCacheModule ist die Klasse für die Darstellung von Informationen des PHP OpCache in IPS.
 * Erweitert ipsmodule.
 *
 * @method bool SendDebug(string $Message, mixed $Data, int $Format)
 * @method void RegisterHook(string $WebHook)
 * @method void UnregisterHook(string $WebHook)
 */
class OpCacheInfoSite extends IPSModuleStrict
{
    use \OpCacheModule\WebhookHelper;
    use \OpCacheModule\VariableProfileHelper;
    use \OpCacheModule\DebugHelper;

    /**
     * Interne Funktion des SDK.
     */
    public function Create(): void
    {
        parent::Create();
        $this->RegisterPropertyString('SubmodulePath', 'opcache-status/opcache.php');
    }

    /**
     * Interne Funktion des SDK.
     */
    public function Destroy(): void
    {
        if (!IPS_InstanceExists($this->InstanceID)) {
            $this->UnregisterHook('/hook/Opcache' . $this->InstanceID);
        }
        parent::Destroy();
    }

    /**
     * Interne Funktion des SDK.
     */
    public function ApplyChanges(): void
    {
        parent::ApplyChanges();

        if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->RegisterHook('/hook/Opcache' . $this->InstanceID);
        }
    }

    public function GetConfigurationForm(): string
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
            array_unshift($Form['actions'], $Warning);
            array_unshift($Form['actions'], $Button);
        } else {
            $Button = [
                'onClick' => 'echo "/hook/Opcache' . $this->InstanceID . '";',
                'label'   => 'Open Webhook',
                'type'    => 'Button',
                'link'    => true];
            array_unshift($Form['actions'], $Button);
        }
        return json_encode($Form);
    }

    protected function ProcessHookdata(): void
    {
        $path = $this->ReadPropertyString('SubmodulePath');
        include __DIR__ . '/../libs/' . $path;
    }
}

/* @} */
