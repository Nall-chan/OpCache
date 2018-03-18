[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Modul%20Version-1.00-blue.svg)]()
[![Version](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)  
![Version](https://img.shields.io/badge/Symcon%20Version-5.0%20%3E-green.svg)
[![StyleCI](https://styleci.io/repos/125710396/shield?style=flat)](https://styleci.io/repos/125710396)  

# OpCache Info-Website  
Bereitstellung der Stati des OpCache von PHP als Website.  

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Software-Installation](#3-software-installation) 
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebHook](#6-webhook)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz) 
8. [Anhang](#8-anhang)  
9. [Lizenz](#9-lizenz)

## 1. Funktionsumfang

 - Bereitstellung der Stati des Cache über externer Informations-Module als Webhook.  

## 2. Voraussetzungen

 - IPS 5.0

## 3. Software-Installation

 Dieses Modul ist Bestandteil der IPSOpCache-Library.

**IPS 5.0:**  
   Bei privater Nutzung: Über das 'Module-Control' in IPS folgende URL hinzufügen.  
    `git://github.com/Nall-chan/IPSOpCache.git`  

   **Bei kommerzieller Nutzung (z.B. als Errichter oder Integrator) wenden Sie sich bitte an den Autor.**  

**Hinweis:**
  Eine eventuell vorhandene Firewall auf dem Host-System des IPS-Servers, muss so konfiguriert werden dass der Port 4196 TCP ankommend freigegeben ist.  

## 4. Einrichten der Instanzen in IP-Symcon

Das Modul ist im Dialog 'Instanz hinzufügen' unter dem Hersteller 'Nall-chan' oder dem Schnellfilter 'OpCache' zufinden.  
![Instanz hinzufügen](../imgs/addInstance.png)  

In dem sich öffnenden Konfigurationsformular wird die externe Library ausgewählt, welche über den Webhook erreichbar ist.  

**Konfigurationsseite:**  
![Instanz hinzufügen](../imgs/settingSite.png)  

| Eigenschaft   | Typ     | Standardwert               | Funktion                               |
| :-----------: | :-----: | :------------------------: | :------------------------------------: |
| SubmodulePath | string  | opcache-status/opcache.php | PHP-Script für die Ausgabe per Webhook |


## 5. Statusvariablen und Profile

Werden nicht erzeugt.  

## 6. WebHook

Über den Webhook http://<IP>:<PORT>/hook/Opcache wird das eingestellte Ausgabemodul ausgeführt und im Browser dargestellt.  
Die direkte Darstellung im WebFront ist über z.B. eine String-Variable mit Profil HTML und IFrame möglich.  
Oder als 'Externe Seite' im WebFront-Konfigurator.  

## 7. PHP-Befehlsreferenz

Keine Befehle vorhanden.

## 8. Anhang

**Changlog:**  

Version 1.0:  
 - Erstes offizielles Release  

## 9. Lizenz

  IPS-Modul:  
  [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)  
