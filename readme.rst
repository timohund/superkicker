Testgetriebene Entwicklung einer Webapplikation mit Symfony2 mit Domain Driven Design

Die Idee
-----------

Die Fussballkenner wissen es schon lange, ich noch nicht... wenn die Saison gestartet
ist heisst es jedes Wochenende, der Ball rollt :)
Weil meine Freundin und ich leidenschaftlich gerne Tippen und anschliessend schauen wie
gut diese Tipps waren, haben wir uns im Netz nach freien Tippspielen auf die Suche gemacht.
Wir sind fündig geworden, aber keines fanden wir wirklich so gut, dass der Spassfaktor groß war.

Da ich als Nerd weiss wie es besser geht :) war die Idee ein solches Tippspiel zu erschaffen.
Nun, dachte ich mir, keine so komplexe Aufgabe und eigentlich ein perfektes Beispiel
ein solches Programmierprojekt vom Anfang bis zu Ende, von der Idee bis zur qualitativ hochwertigen
Umsetzung zu dokumentieren.

Gearbeitet werden soll, natürlich nach den neusten Methoden und mit den besten Frameworks.

Das Ziel ist also:

"Ein einfaches Fussball Tippspiel, dass einfach zu benutzen ist und solide entwickelt"

Folgende Methoden und Frameworks sollen verwendet werden:

* Symfony2 als PHP Framework
* Doctrine zur Speicherung der Daten
* PHPUnit für Unit und funktionale Tests
* Domain Driven Design zum finden des Domain Models
* Userstorys und Priorisierung/Umsetzung nach Scrum (Wobei das bei einem Onemann Projekt
nur bei der Priorisierung hilft)


Tag 1 "Finden des Domain Models mit DomainDrivenDesign":
-----------

Beim Domain Driven Design versuchen wir die Objekte in unserem Spiel
so zu modelieren, dass sie möglichst nah an der Realität sind.
Wir versuchen zunächst unser Domainmodel so einfach wie möglich zu halten
und haben folgende Objekte identifiziert:

1. SoccerClub: Ein SoccerClub ist ein Verein der in der einfachsten Variante
nur einen Namen hat.

2. Match: Ein Match, also ein Spiel findet an einem Datum zwischen einem Heimatverein und einem Gastverein
statt. Es gibt einen Score für den Heimat und Gastverein.

3. Tip: Ein Tip gehört zu einen Match und einem Benutzer. Es beinhaltet einen getippten
Score für die Heim und Gastmannschaft

4. User: Ein User kann sich einloggen und Tipps abgeben.


Tag 2 "Definition der ersten Userstory und erster Tests":
-----------

Beim finden der Userstories konzentrieren wir uns zunächst auf die wichtigsten Funktionen
unserer Anwendung:

1. Es müssen Vereine angelegt werden können.
2. Der Administrator muss Spiele anlegen können
3. Der Benutzer muss Tippen können.

Welche dieser Funktionen hat nur die höchste Priorität und den größten Wert?
In einem ersten Wurf könnte Damit leben, wenn die Vereine nicht pflegbar sondern
nur statisch sind. Sie ändern sich ja nicht so oft. Es bleibt das anlegen von Spielen
und die Abgabe eines Tipps. Den größten Wert schafft dabei die Abgabe des Tipps,
da sie die Kernfunktionalität ist.

Die erste Userstory lautet also:

Titel: Als Benutzer möchte ich einen Tipp für ein Spiel abgeben können um
am Spiel teilzunehmen.

Details:

Als ein Benutzer ohne Login kann ich einen Tipp für ein Spiel abgeben.
Die Spiele und die Vereine sind zunächst fix in der Datenbank angelegt und nicht editierbar
um den Aufwand zunächst in Grenzen zu halten.

Testkriterien:

* Der Benutzer kann ein Spiel auswählen (Datenquelle ist die Datenbank mit festen Datensätzen)
* Der Benutzer kann einen Tipp abgeben und speichern.
* Der Benutzer kann den Tipp unter "Meine Tipps" sehen aber nicht editieren.

Tag 3 "Entwicklungumgebung und Symfony2 Bundle einrichten"
-----------

Wie zum Anfang erwähnt, möchten wir das Symfony2 Framework verwenden. Um den Start zu erleichtern
verwenden wir ein modifiziertes "symfony-standard" Paket.

https://github.com/timoschmidt/symfony-standard.git

Dieses Paket enthält zusätzlich:

* PHPQA Tools (phpunit, phpmd, phpcpd)
* Build.xml für Deploymentpakete
* Travis CI Vorbereitung
* Mechanissmus um Settings während des Deployments zu migrieren

Zum Start können wir mit einer Kopie des Pakets beginnen.

Folgende Schritte sind notwendig:

::

	git clone https://github.com/timoschmidt/symfony-standard.git tippspiel

	cd tippspiel/build
	ant -Dversion=1

	php app/console generate:bundle

	git remote add myname yourrepositoryurl
	gut pull
	git add src/yourvendorname
	git push myname master
::


Tag 4 "Dependency Injection mit Symfony2"
-----------

Um eine Softwarestruktur zu haben, die sich gut automatisiert testen lässt sollten
wir von Begin an die Komponenten so entwickeln, dass Abhängigkeiten einer
Klasse von aussen mittels "DependencyInjection" übergeben werden.

Wir möchten DependecyInjection mittels YAML Konfiguriation verwenden. Dazu sind folgende
Schritte notwendig:

1. Innerhalb des Bundles muss im "DependencyInjection" Namespace eine "<BundleName>Extesion"
Klasse liegen, die die YAML Konfiguration inkludiert (<BundleName> ist der Name des Bundles OHNE
das Bundle Suffix bei FooBundle als FooExtension)

In diesem Projekt können sie hier die Konfiguration anschauen:

"src/Ts/Superkicker/SuperkickerBundle/DependecyInjection/SuperkickerExtension"

2. Alle Dependecies müssen in der "services.yml" definiert werden:

"src/Ts/Superkicker/SuperkickerBundle/Resources/config/services.yml"

3. Um DepedencyInjection auch im Controller zu verwenden. Muss der Controller
in der services.yml definiert sein und im Routing muss statt der Controllerklasse
der Servicekey referenziert werden:

"src/Ts/Superkicker/SuperkickerBundle/Resources/config/routing.yml"

Tag 5 "DomainDrivenDesign Struktur für Symfony2 und Doctrine"
-----------

Bei der Implementierung möchten wir die Objekte der realen Welt als Domänenobjekte
abbilden und darauf zugreifen mittels "Repositoryklassen".

Folgende Dateisystemstruktur ist erwünscht:

::

	Classes/
		Domain/
			Model/
			Repository/
::

Klassen im Domainverzeichnis sollen von Doctrine automatisch in der Datenbank gespeichert und gelesen
werden.

Dazu sind folgende Schritte notwendig:

1. Ändern des Doctrine Entity Verzeichnisses in "app/config/config.yml":

::

	doctrine:
    	orm:
        	auto_generate_proxy_classes: true
        	auto_mapping: false
        	mappings:
            	name:
                	type: annotation
                	dir: %kernel.root_dir%/../src/Ts/Superkicker/SuperkickerBundle/Domain/Model
                	prefix: Ts\Superkicker\SuperkickerBundle\Domain\Model
::

2. Anlegen der Domainklassen mit Mapping

Unsere Domainklassen müssen für Doctrine so annotiert werden, dass es in der Lage ist sie
automatisch in die Datenbank zu schreiben und zu lesen.

Wenn ein erstes Domain Model steht, kann man diese Aufgabe relativ einfach mit dem
webforge-doctrine-compiler erledigen.

Dazu muss zunächst das Domainmodel in einer json Datei compiliert werden.
Diese liegt in unserem Fall in "src/Ts/Superkicker/SuperkickerBundle/Resources/mapping/model.json".

Mit folgendem Inhalt:

::

	{
	  "namespace": "Ts\\Superkicker\\SuperkickerBundle\\Domain\\Model",
	  "entities": [
		{
		  "name": "Match",
		  "description": "Represents a match in the tip model",
		  "properties": {
			"id": { "type": "DefaultId" },
			"homeScore": { "type": "Integer", "nullable": true },
			"guestScore": { "type": "Integer", "nullable": true },
			"homeClub": { "type": "Club"},
			"guestClub": { "type": "Club"},
			"date": { "type": "DateTime", "nullable": true },
			"matchDay": { "type": "Integer", "nullable": false }
		  }
		},
		{
		  "name": "Club",
		  "description": "Represents a soccer club",
		  "properties": {
			"id": { "type": "DefaultId" },
			"name": { "type": "String" }
		  }
		},
		{
		  "name": "Tip",
		  "description": "Represents a tip for a match",
		  "properties": {
			"id": { "type": "DefaultId" },
			"match": { "type": "Match" },
			"homeScore": { "type": "Integer" },
			"guestScore": { "type": "Integer"},
			"user": { "type": "User" }
		  }
		},
		{
		  "name": "User",
		  "description": "Represents a user",
		  "properties": {
			"id": { "type": "DefaultId" },
			"name": { "type": "String" },
			"clientId": { "type": "Integer" }
		  }
		}
	  ]
	}
::

Danach muss der webforge-doctrine-compiler installiert werden.
Um nicht zuviele Anhängigkeiten im Projekt zu haben, kann er ausserhalb des
Projekts installiert werden:

::

	cd <myhomedir>/.composer/
	composer init --stability="dev"
	composer global require "webforge/doctrine-compiler":"1.0.*@dev"
::

und kann danach wie folgt genutzt werden:

::

	vendor/bin/webforge-doctrine-compiler orm:compile src/Ts/Superkicker/SuperkickerBundle/Resources/mapping/models.json src/

Für jede Klassedefinition wird eine normale PHP Klasse erzeugt und eine mit dem Prefix "Compiled...". In
dieser Compiled Klasse befindet sich die Definition des Doctrine Mappings.

Um den Compiler sinnvoll zu nutzen sollte man noch die DateTime Klasse von Webforge verwenden.
Dazu sollte folgendes Paket in der Composer.json des Projekts ergänzt werden:

::

	"webforge/common": "*",
	"webforge/doctrine": "dev-master"
::

Und in der Boot Methode des Bundles muss der eigene Doctrine Datentype registriert werden:

::

	class SuperkickerBundle extends Bundle {
		...
		public function boot() {
			$em = $this->container->get('doctrine.orm.entity_manager');
			Type::addType('WebforgeDateTime', 'Webforge\Doctrine\Types\DateTimeType');
		}
		...
	}
::

3. Konfiguration der Datenbank in Symfony

Bevor die Datenbank verwendet werden kann müssen die Parameter in der app/config/parameters.yaml konfiguriert sein.

::

	parameters:
	    database_driver: pdo_mysql
		database_host: localhost
		database_port: null
		database_name: superkicker
		database_user: <Username>
		database_password: <Passwort>
::
4. Anlegen der Datenbank mit Doctrine

Nachdem alle Settings vorhanden sind kann die Datenbank angelegt werden und das
Schema erzeugt werden.

::

	php app/console doctrine:database:create
	php app/console doctrine:schema:create
::
Wenn das Schema im Nachhinein geändert werden soll kann dass mittels doctrine:schema:update
gemacht werden.

::

	php app/console doctrine:schema:update
::

Tag 6 "Testing mit Codeception"

