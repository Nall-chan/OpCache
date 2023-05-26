[![SDK](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Modul%20Version-2.02-blue.svg)]()
![Version](https://img.shields.io/badge/Symcon%20Version-7.0%20%3E-green.svg)  
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Check Style](https://github.com/Nall-chan/OpCache/workflows/Check%20Style/badge.svg)](https://github.com/Nall-chan/OpCache/actions) 
[![Run Tests](https://github.com/Nall-chan/OpCache/workflows/Run%20Tests/badge.svg)](https://github.com/Nall-chan/OpCache/actions)  
[![Spenden](https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donate_SM.gif)](../README.md#6-spenden)  

# OpCache Information  <!-- omit in toc -->  
Darstellung des Status von dem OpCache der PHP Laufzeitumgebung.  

## Dokumentation <!-- omit in toc -->

**Inhaltsverzeichnis**

- [1. Funktionsumfang](#1-funktionsumfang)
- [2. Voraussetzungen](#2-voraussetzungen)
- [3. Software-Installation](#3-software-installation)
- [4. Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
- [5. Statusvariablen und Profile](#5-statusvariablen-und-profile)
- [6. WebFront](#6-webfront)
- [7. PHP-Befehlsreferenz](#7-php-befehlsreferenz)
- [8. Anhang](#8-anhang)
- [9. Lizenz](#9-lizenz)

## 1. Funktionsumfang

 - Darstellung des Status von dem OpCache der PHP Laufzeitumgebung als Variablen innerhalb von Symcon.

## 2. Voraussetzungen

 - IPS 5.1

## 3. Software-Installation

 Dieses Modul ist Bestandteil der [OpCache](../README.md#3-software-installation) Library.  

## 4. Einrichten der Instanzen in IP-Symcon

Das Modul ist im Dialog 'Instanz hinzufügen' unter dem Hersteller 'Nall-chan' oder dem Schnellfilter 'OpCache' zu finden.  
![Instanz hinzufügen](../imgs/addInstance.png)  

In dem sich öffnenden Konfigurationsformular muss noch ein Intervall in Sekunden für die Aktualisierung eingetragen werden.  
Ist der OpCache nicht aktiv, werden nach dem übernehmen der Einstellungen keine Variablen erzeugt.  

**Konfigurationsseite:**  
![Instanz hinzufügen](../imgs/settingInfo.png)  

| Eigenschaft |   Typ   | Standardwert |          Funktion          |
| :---------: | :-----: | :----------: | :------------------------: |
|  Interval   | integer |      0       | Aktualisierung in Sekunden |


## 5. Statusvariablen und Profile

![Instanz hinzufügen](../imgs/logTree.png)  

Folgende Statusvariablen werden automatisch angelegt.  

|           Name            |   Typ   |           Ident           |      Hinweis       |      Profil       |
| :-----------------------: | :-----: | :-----------------------: | :----------------: | :---------------: |
|       Trefferquote        |  float  |     opcache_hit_rate      | Relativ in Prozent | OpCache.Intensity |
|          Treffer          | integer |           hits            |                    |                   |
|         Verfehlt          | integer |          misses           |                    |                   |
|  Anzahl gecachte Scripte  | integer |    num_cached_scripts     |                    |                   |
|      max. Schlüssel       | integer |      max_cached_keys      |                    |                   |
| Anzahl gecachte Schlüssel | integer |      num_cached_keys      |                    |                   |
|      Startzeitpunkt       | integer |        start_time         |   Unixtimestamp    |  ~UnixTimestamp   |
|       letzter Reset       | integer |     last_restart_time     |   Unixtimestamp    |  ~UnixTimestamp   |
|      Manuelle Resets      | integer |      manual_restarts      |                    |                   |
|      Speicher gesamt      |  float  |       total_memory        |   Absolut in MB    |    OpCache.MB     |
|       Speicher frei       |  float  |        free_memory        |   Absolut in MB    |    OpCache.MB     |
|       Speicher frei       |  float  |  free_memory_percentage   | Relativ in Prozent | OpCache.Intensity |
|     Speicher benutzt      |  float  |        used_memory        |   Absolut in MB    |    OpCache.MB     |
|     Speicher benutzt      |  float  |  used_memory_percentage   | Relativ in Prozent | OpCache.Intensity |
|   Speicher verschwendet   |  float  |       wasted_memory       |   Absolut in MB    |    OpCache.MB     |
|   Speicher verschwendet   |  float  | current_wasted_percentage | Relativ in Prozent | OpCache.Intensity |

**Profile**:

|       Name        |  Typ  |
| :---------------: | :---: |
|    OpCache.MB     | float |
| OpCache.Intensity | float |

## 6. WebFront

Die direkte Darstellung im WebFront ist möglich.  
![WebFront Beispiel](../imgs/wfInfo.png)  


## 7. PHP-Befehlsreferenz

```php
bool OPCACHE_Update(int $InstanzID);
```
Aktualisiert die Statusvariablen.  
Bei Erfolg wird `true` zurück gegeben.  
Im Fehlerfall wird eine Warnung erzeugt und `false`zurück gegeben.  


## 8. Anhang

**Changelog:**  

Version 2.0:  
 - Release für IPS 5.1 und den Module-Store   

Version 1.0:  
 - Erstes offizielles Release  

## 9. Lizenz

  IPS-Modul:  
  [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)  
