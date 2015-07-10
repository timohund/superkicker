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
-----------

Für das testen der Applikation verwende ich das Framework codeception.
Es bietet die Möglichkeit schnell und einfach tests zu implementieren.

Hierbei kann codeception unit, functional und acceptance tests generieren und
diese können "behaviour driven" entwickelt werden.

Um einen einfach Akzeptanz test zu implementieren gehen wir wie folgt vor:

1. codeception als require-dev dependency in der composer.json hinzufügen:

::

    "require-dev": {
	   "codeception/codeception": "*"
    }
::

2. codeception bootstrappen:

::

	php bin/codecept bootstrap
::

3. Einen Akzeptanztest generieren

::

	php bin/codecept generate:cept acceptance CreateMatch
::


4. Codeception konfiguration anpassen:

In der Datei "/tests/acceptance/acceptance.suite.yml" muss zumindest die url
angepasst werden über die die Applikation erreichbar ist.

::

	class_name: WebGuy
		modules:
    		enabled:
        	- PhpBrowser
        	- WebHelper
    	config:
        	PhpBrowser:
            	url: 'YOURURL'
::

5. Den Test (tests/acceptance/CreateMatchCept.php) mit Leben füllen:

::

	<?php
		$I = new WebGuy($scenario);
		$I->wantTo('Die Willkommensseite sehen und einen Tip anlegen');
		$I->canSeeInTitle('Willkommen im Superkicker Tippspiel');
		$I->seeLink('Tipps','/tipp/edit');
		$I->click('#tipps');
		$I->canSeeInTitle('Tipp abgeben');
		$I->canSeeElement("form");
		$I->canSee("Deine Tipps");
		$I->canSee("BVB");
		$I->fillField('#match_1_home',1);
		$I->fillField('#match_1_guest',2);
		$I->fillField('#match_2_home',3);
		$I->fillField('#match_2_guest',4);
		$I->submitForm('#tippCreate',
			array(
				'#foo' => 'bar',
				'match_1_home' => 1,
				'match_1_guest' => 2,
				'match_2_home' => 3,
				'match_2_guest' => 4
			)
		);
		$I->canSee('Deine Tipps wurden gespeichert');
::

Das obere Beispiel zeigt, wie einfach es ist einen Akzeptanztest für einen einfach UseCase
zu implementieren.


Tag 7. "Codeceptiontests erweitern"
-----------

Am vorherigen Tag haben wir einen einfachen Akzeptanztest mit codeception implementiert.
Beim ausführen komplexer Akzeptanztests ist es jedoch erforderlich, die Anwendung vor
dem Start in einen bestimmten Zustand zu versetzen und nach dem test einen definierten Endzustand
zu prüfen.

Ein etwas komplexerer test könnte in schematisch wie folgt aussehen:

1. Das System leeren
2. Zwei Vereine anlegen
3. Für diese zwei Vereine ein Match anlegen
4. Für dieses Match einen Tipp abgeben
5. Prüfen ob der Tipp gespeichert wurde

Der komplizierteste Teil ist der erste. Da der Akzeptanztest das System von aussen testet.
Wir müssen also entweder über das Userinterface ein reset erlauben oder bevor der Test
startet auf dem System den Zustand zurücksetzen.

Codeception bietet für die zweite Variante die Möglichkeit einen Datenbank Dump einzuspielen.
Da wir Doctrine verwenden einen keinen Dump pflegen möchten benötigen wir einen anderen
Weg. Die Idee wäre ist mittels Codeception per ssh folgende Kommandos zu starten:

::

	php /webroot/app/console doctrine:database:drop --force
    php /webroot/app/console doctrine:database:create
	php /webroot/app/console doctrine:schema:create
::

Am flexibelst ist es sshHost, sshUser, sshPassword und die Kommandos zum bootstrappen frei konfigurieren zu könnte.

Im Verzeichniss "tests/acceptance" wird neben der *Cept.php Datei, die den Test enthält
eine WebGuy.php implementiert. Diese kann genutzt werden um Codeception zu erweitern mit
funktionalitäten, die im Framework vermisst werden.

Der folgende Code ermöglicht es die Settings der "acceptance.suite.yml" auszulesen und die Kommandos per ssh auszuführen:

::

    /**
     * @return array
     * @throws Exception
     * @throws \Codeception\Exception\Configuration
     */
    protected function getSuiteSettings() {
        return Codeception\Configuration::suiteSettings('acceptance',Codeception\Configuration::config());
    }

    /**
     * @return void
     */
    public function resetSystem() {
        $setting = $this->getSuiteSettings();
        $bootstrapSettings = $setting["modules"]["config"]["SshBootstrap"];
        $connection = ssh2_connect($bootstrapSettings['host']);
        ssh2_auth_password($connection, $bootstrapSettings['user'], $bootstrapSettings['password']);

        foreach($bootstrapSettings['commands'] as $command) {
            ssh2_exec($connection, $command);
            sleep(5);
        }
    }
::

Nun kann die acceptance.suite.yml wie folgt erweitert werden:

::

	class_name: WebGuy
	modules:
		enabled:
			- PhpBrowser
			- WebHelper
		config:
			PhpBrowser:
				url: 'http://webhost/app_dev.php/'
			SshBootstrap:
				host: 'webhost'
				user:   'vagrant'
				password: 'vagrant'
				commands:
					- 'php /webroot/app/console doctrine:database:drop --force'
					- 'php /webroot/app/console doctrine:database:create'
					- 'php /webroot/app/console doctrine:schema:create'
::


Tag 8. "Die Templateengine Twig in Symfony2"
-----------

Die bevorzugte Templateengine für Symfony2 ist Twig. Twig bietet eine Fülle an Features, die
die schnelle Umsetzung von Views unterstützen

* Schleifen
* IfElse
* ViewHelper für einfache Viewlogik (trim, round, ### TODO)
* ...

Standardgemäss sind die Templates eines Bundle in "<BundleRoot>/views/Resources/views/" gespeichert.
Für unser Bundle sind sie also in "src/Ts/Superkicker/SuperkickerBundle/Resources/views" gespeichert.

Pro Controller gibt es ein Unterverzeichnis und pro Action kann es in diesem ein Template geben
(muss es aber nicht:)).

Ein Template wird aus dem Controller gerendert und es können Variablen an das Template übergeben werden:

::

	return $this->templating->renderResponse(
		'SuperkickerBundle:Match:edit.html.twig',
		array(
			'allMatches' => $allMatches,
			'matchId' => $match,
			'allClubs' => $allClubs
		)
	);
::

Jenachdem wie man Symfony2 konfiguriert (DependencyInjection, Annotationen, ...) erfolgt das Rendern
des Templates wie hier explizit oder implizit am Ende der Methode vom Framework.

Tag 9 "Templatevererbung mit Twig in Symfony2"
-----------

Um Blöcke in einem Template wiederverwenden zu können bietet Twig die Möglichkeit
von einem Basistemplate zu erben. Mittels "extends" kann dieses inkludiert werden.

::

	{% extends "base.html.twig" %}
::

Das Basis Template liegt bei Symfony per Konvention in "app/Resources/views/base.html.twig".
Wichtig hierbei ist, dass es standardgemäß  NICHT im Bundle selber liegt.

Ein solches Basistemplate definiert Bereich in Form von Blöcken. Diese
Blöcke können dann im konkreten Template überschrieben werden.

Das folgende Beispiel zeigt ein einfaches Basistemplate in "app/Resources/views/base.html.twig":

::

	<!DOCTYPE html>
	<html>
		<head>
			<link rel="stylesheet" href="style.css"/>
			<title>{% block title %}{% endblock %}</title>
		</head>
			<div id="navigation">
				{% block menu %}
					<ul class="nav navbar-nav">
						<li><a href="{{ path('ts_superkicker_index') }}"
							id="home">Home</a>
						</li>
					</ul>
				{% endblock %}
			</div>
			<div id="content">{% block content %}{% endblock %}</div>
		</body>
	</html>
::

Im oberen Beispiel wird im Block "menu" eine Navigation gerendert. Diese Navigation ist bei einer
Webandwendungen auf Unterseiten oftmals identisch. Der Block "content" ist im Basistemplate leer definiert
und wird im erbenden Template überschrieben um den Inhalt der Unterseite zu definieren.

Ein konkretes Template, dass von "base.html.twig" erbt kann wir folgt aussehen:

Beispiel ("/src/Ts/Superkicker/SuperkickerBundle/Resources/views/Club/edit.html.twig"):

::

	{% extends "base.html.twig" %}

	{% block title %}Clubs editieren{% endblock %}

	{% block content %}
		{% if saved %}
			<div>Die Clubs wurden gespeichert.</div>
		{% endif %}
		<form action="{{ path('ts_superkicker_club_save') }}"
			id="clubCreate" method="post">
			{% for club in allClubs %}
				<input type="text"
					name="clubs[{{ club.id }}][name]"
					id="club_{{ club.id }}_name"
					value="{{ club.name | default() }}"/>
			{% endfor %}
			<input type="text"
				name="clubs[new][name]"
				id="club_new_name"
				value="" />

			<input type="submit" value="Speichern"/>
		</form>
	{% endblock %}
::

Das erbende Template inkludiert das Basistemplate in der ersten Zeile und überschreibt die Bereiche "content" und "title".

Tag 10. "Integration von Twitter Bootstrap in eine Symfony2 App"
-----------

Eine moderne Webanwendung wird nicht mehr nur auf normalen Computern oder Notebooks sondern
auch auf eine Fülle von mobilen Geräte benutzt. Der Benutzer erwartet es, dass er die Applikation
auf dem Tablett, dem Smartphone und wie bisher dem Notebook oder Desktopcomputer verwenden kann.

Das wissen natürlich auch die großen Contentanbieter im Netz und haben ihre Webseiten daraufhin optimiert.
Twitter hat mit dem Framework "Bootrap" einen Css und JavaScript Baukasten entwickelt, der es ermöglicht
in kurzer Zeit Webanwendungen zu entwickeln, die sich auf das Endgerät anpassen. Ein und die selbe Ausgabe
wird also vom Framework für ein kleines, mobile oder ein großes normales Gerät optimiert.

Die Integration von Bootstrap ist einfach:

1. Bootstrap Framework herunterladen und entpacken.

2. Den Inhalt von Bootstrap Css in "Resources/public/" des Symfony2 Bundles kopieren
(In unserem Fall src/Ts/Superkicker/SuperkickerBundle/Resources/public)

3. JQuery downloaden und im public Resources Verzeichnis speichern oder von extern einbinden

4. Im Basistemplate (app/Resources/views/base.html.twig) der app das JavaScript und Css von Bootstrap einbinden:

::

	<!DOCTYPE html>
	<html>
		<head>
			<link rel="stylesheet" href="style.css"/>
			<link rel="stylesheet"
				href="{{ asset('bundles/superkicker/css/bootstrap.css') }}">
		</head>
		<body id="main">
				...
			<div id="content">{% block content %}{% endblock %}</div>
			<script src="{{ asset('bundles/superkicker/js/jquery.js') }}"></script>
			<script src="{{ asset('bundles/superkicker/js/bootstrap.min.js') }}"></script>
		</body>
	</html>
::

5. Damit die Resourcen des Bundles auf dem Webserver publiziert werden muss folgendes Symfony Kommando
ausgeführt werden:

::

	php app/console assets:install
::

6. Nun können die Css Klassen von Bootstrap in den eigenen Views verwendet werden. Das folgende Beispiel zeigt einen einfachen
Submitbutton:

::

	<input type="submit" class="btn btn-default navbar-btn" value="Speichern"/>
::

Weiter Beispiele sind im Viewverzeichnis (src/Ts/Superkicker/SuperkickerBundle/Resources/views) oder in der Bootstrap
Dokumentation zu finden.

