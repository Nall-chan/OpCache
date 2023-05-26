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
 *
 * @method void RegisterProfileFloat(string $Name, string $Icon, string $Prefix, string $Suffix, float $MinValue, float $MaxValue, float $StepSize, int $Digits)
 */
class OpCacheModule extends IPSModule
{
    use \OpCacheModule\DebugHelper;
    use \OpCacheModule\VariableProfileHelper;
    public static $VariableTyp = [
        'used_memory'               => 2,
        'free_memory'               => 2,
        'wasted_memory'             => 2,
        'current_wasted_percentage' => 2,
        'num_cached_scripts'        => 1,
        'num_cached_keys'           => 1,
        'max_cached_keys'           => 1,
        'hits'                      => 1,
        'start_time'                => 1,
        'last_restart_time'         => 1,
        'manual_restarts'           => 1,
        'misses'                    => 1,
        'opcache_hit_rate'          => 2,
        'used_memory_percentage'    => 2,
        'free_memory_percentage'    => 2,
        'total_memory'              => 2
    ];
    public static $VariableProfile = [
        'used_memory'               => 'OpCache.MB',
        'free_memory'               => 'OpCache.MB',
        'wasted_memory'             => 'OpCache.MB',
        'current_wasted_percentage' => 'OpCache.Intensity',
        'start_time'                => '~UnixTimestamp',
        'last_restart_time'         => '~UnixTimestamp',
        'opcache_hit_rate'          => 'OpCache.Intensity',
        'used_memory_percentage'    => 'OpCache.Intensity',
        'free_memory_percentage'    => 'OpCache.Intensity',
        'total_memory'              => 'OpCache.MB'
    ];

    /**
     * Interne Funktion des SDK.
     */
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyInteger('Interval', 0);
        $this->RegisterTimer('Update', 0, 'OPCACHE_Update(' . $this->InstanceID . ');');
    }

    /**
     * Interne Funktion des SDK.
     */
    public function Destroy()
    {
        parent::Destroy();
    }

    /**
     * Interne Funktion des SDK.
     */
    public function ApplyChanges()
    {
        $this->RegisterMessage(0, IPS_KERNELSTARTED);
        $this->RegisterProfileFloat('OpCache.MB', 'Database', '', ' MB', 0, 0, 0, 3);
        $this->RegisterProfileFloat('OpCache.Intensity', 'Intensity', '', ' %', 0, 0, 0, 2);

        parent::ApplyChanges();

        if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->SetInterval($this->ReadPropertyInteger('Interval'));
        } else {
            $this->SetInterval(0);
        }
        if (extension_loaded('Zend OPcache')) {
            $this->SetStatus(IS_ACTIVE);
            $this->Update();
        } else {
            $this->SetStatus(IS_EBASE + 1);
        }
    }

    /**
     * Nachrichten aus der Nachrichtenschlange verarbeiten.
     *
     * @param int       $TimeStamp
     * @param int       $SenderID
     * @param int       $Message
     * @param array|int $Data
     */
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        switch ($Message) {
            case IPS_KERNELSTARTED:
                $this->Update();
                $this->SetInterval($this->ReadPropertyInteger('Interval'));
                $this->UnregisterMessage(0, IPS_KERNELSTARTED);
                break;
        }
    }

    public function GetConfigurationForm()
    {
        $isEnabled = @IPS_GetOption('OPcacheSupport');
        $isLoaded = extension_loaded('Zend OPcache');
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
            $Form['actions'][0] = $Warning;
            $Form['actions'][1] = $Button;
            return json_encode($Form);
        }
        if (!$isLoaded) {
            $Warning = [
                'type'  => 'Label',
                'label' => 'OPCache Support is not loaded in IPS!'
            ];
            $Warning2 = [
                'type'  => 'Label',
                'label' => 'Restart IPS to enable OPCache!'
            ];
            $Form['actions'][0] = $Warning;
            $Form['actions'][1] = $Warning2;
            return json_encode($Form);
        }
        return json_encode($Form);
    }

    //################# PUBLIC

    /**
     * IPS-Instanz Funktion OPCACHE_Update.
     *
     * @return bool True wenn Befehl erfolgreich ausgeführt wurde, sonst false.
     */
    public function Update()
    {
        if (!extension_loaded('Zend OPcache')) {
            echo $this->Translate('Zend OPCache ist not loaded.');
            $this->SetStatus(IS_EBASE + 1);
            return false;
        }
        $this->SetStatus(IS_ACTIVE);
        $status = @opcache_get_status(false);
        if (!is_array($status)) {
            echo $this->Translate('Status from Zend OPCache is not available.');
            return false;
        }
        $config = opcache_get_configuration();
        $overview = array_merge(
                $status['memory_usage'], $status['opcache_statistics'], [
                    'used_memory_percentage' => 100 * (
                    ($status['memory_usage']['used_memory'] + $status['memory_usage']['wasted_memory']) / $config['directives']['opcache.memory_consumption']),
                    'free_memory_percentage' => 100 * (
                    $status['memory_usage']['free_memory'] / $config['directives']['opcache.memory_consumption']),
                    'total_memory'           => (float) $config['directives']['opcache.memory_consumption'] / 1024 / 1024,
                    'used_memory'            => $status['memory_usage']['used_memory'] / 1024 / 1024,
                    'free_memory'            => $status['memory_usage']['free_memory'] / 1024 / 1024,
                    'wasted_memory'          => $status['memory_usage']['wasted_memory'] / 1024 / 1024,
                ]
        );
        unset($overview['oom_restarts']);
        unset($overview['hash_restarts']);
        unset($overview['blacklist_misses']);
        unset($overview['blacklist_miss_ratio']);
        foreach ($overview as $Ident => $Value) {
            $this->SetValue($Ident, $Value);
        }
        return true;
    }

    protected function SetValue($Ident, $Value)
    {
        $IpsVarType = self::$VariableTyp[$Ident];
        if (array_key_exists($Ident, self::$VariableProfile)) {
            $Profile = self::$VariableProfile[$Ident];
        } else {
            $Profile = '';
        }
        $this->MaintainVariable($Ident, $this->Translate($Ident), $IpsVarType, $Profile, 0, true);
        parent::SetValue($Ident, $Value);
    }

    private function SetInterval(int $Seconds)
    {
        $msec = $Seconds < 5 ? 0 : $Seconds * 1000;
        $this->SetTimerInterval('Update', $msec);
    }
}

/* @} */
