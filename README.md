# getLaw-Contao-API


## Beschreibung

Mit dieser Erweiterung kannst Du die Rechtstexte der Legal-Tech-Plattform [www.getLaw.de](https://www.getLaw.de) automatisiert in Deine Website und Deinen Shop einbinden.
Die Erweiterung ruft die Rechtstexte automatisch alle 24 Stunden von [www.getLaw.de](https://www.getLaw.de) ab. Falls gewünscht, kannst Du den automatischen Abruf durch Setzen eines Häkchens deaktivieren. Ein manueller Abruf der Rechtstexte von [www.getLaw.de](https://www.getLaw.de) ist jederzeit über den Button in der Liste der Inhaltselemente möglich.
Zur Verfügung stehen derzeit folgende Rechtstexte für Websites und Shops: Impressum, Datenschutzerklärung, allgemeine Geschäftsbedingungen und Widerrufsbelehrung.


## Autor

__getLaw:__ <info@getLaw.de>


## Voraussetzungen

- php: >=7.3
- contao/core-bundle: >=4.4


## Installation

Die Erweiterung wird über den Contao Manager installiert. Suchen Sie dort nach der Erweiterung mit der folgenden Bezeichnung: __getlaw/getlaw-contao-api__

Wenn Sie die Erweiterung gefunden haben, klicken Sie auf "Hinzufügen" und installieren diese.



## Einrichtung

Nach der Installation der Erweiterung steht Ihnen im Backend von Contao ein neues Inhaltselement mit der Bezeichnung "getLaw - Text" zur Verfügung. Fügen Sie dieses an der gewünschten Stelle ein. Tragen Sie dann in das Feld "API-Schlüssel" den individuellen API-Schlüssel Ihres Textes ein.

Der Text wird nun erstmalig geladen. Die Erweiterung ruft den Text danach automatisch alle 24 Stunden von [www.getLaw.de](https://www.getLaw.de) ab. Falls gewünscht, können Sie den automatischen Abruf durch Setzen eines Häkchens deaktivieren. Ein manueller Abruf des Textes von [www.getLaw.de](https://www.getLaw.de) ist jederzeit über den Button in der Liste der Inhaltselemente möglich.
