<?php

declare(strict_types=1);
/**
 * @addtogroup opcache
 * @{
 *
 * @file          module.php
 *
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2018 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 *
 * @version       1.00
 */
require_once __DIR__ . '/../libs/OpCacheTraits.php';  // diverse Klassen

/**
 * OpCacheModule ist die Klasse für die Darstellung von Infomationen des PHP OpCache in IPS.
 * Erweitert ipsmodule.
 */
class OpCacheModule extends IPSModule
{
    use VariableHelper,
        DebugHelper,
        VariableProfile;

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

        parent::ApplyChanges();

//        $this->RegisterProfileIntegerEx("MS35.Program", "Gear", "", "", array(
//            array(1, 'Farbwechsel 1', '', -1),
//            array(2, 'Farbwechsel 2', '', -1),
//            array(3, 'Farbwechsel 3', '', -1),
//            array(4, 'Gewitter', '', -1),
//            array(5, 'Kaminfeuer', '', -1),
//            array(6, 'Sonnenauf- & untergang', '', -1),
//            array(7, 'Farbblitze', '', -1),
//            array(8, 'User 1', '', -1),
//            array(9, 'User 2', '', -1)
//        ));
//
//        $this->RegisterProfileIntegerEx("MS35.PrgStatus", "Bulb", "", "", array(
//            array(1, 'Play', '', -1),
//            array(2, 'Pause', '', -1),
//            array(3, 'Stop', '', -1)
//        ));
//
//        $this->RegisterProfileIntegerEx("MS35.Speed", "Intensity", "", "", array(
//            array(0, 'normal', '', -1),
//            array(1, '1/2', '', -1),
//            array(2, '1/4', '', -1),
//            array(3, '1/8', '', -1),
//            array(4, '1/16', '', -1),
//            array(5, '1/32', '', -1),
//            array(6, '1/64', '', -1),
//            array(7, '1/128', '', -1)
//        ));
//
//        $this->RegisterProfileIntegerEx("MS35.Brightness", "Sun", "", "", array(
//            array(1, 'normal', '', -1),
//            array(2, '1/2', '', -1),
//            array(3, '1/3', '', -1)
//        ));
//        $this->RegisterVariableBoolean("STATE", "STATE", "~Switch", 1);
//        $this->RegisterVariableInteger("Color", "Color", "~HexColor", 2);
//        $this->RegisterVariableInteger("Program", "Program", "MS35.Program", 3);
//        $this->RegisterVariableInteger("Play", "Play", "MS35.PrgStatus", 4);
//        $this->RegisterVariableInteger("Speed", "Speed", "MS35.Speed", 5);
//        $this->RegisterVariableInteger("Brightness", "Brightness", "MS35.Brightness", 6);

        if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->Update();
            $sec = $this->ReadPropertyInteger('Interval');
            $msec = $sec < 5 ? 0 : $sec * 1000;
            $this->SetTimerInterval('Update', $msec);
        } else {
            $this->SetTimerInterval('Update', 0);
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
                $sec = $this->ReadPropertyInteger('Interval');
                $msec = $sec < 5 ? 0 : $sec * 1000;
                $this->SetTimerInterval('Update', $msec);
                break;
        }
    }

    //################# PUBLIC

    /**
     * IPS-Instanz Funktion OPCACHE_Update.
     *
     * @return bool True wenn Befehl erfolgreich ausgeführt wurde, sonst false.
     */
    public function Update()
    {
        return false;
    }
}

/* @} */
