=== Nexus Gaming Events ===
Contributors: quintendoes
Tags: events, gaming, community, calendar, events management, gaming events
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Een complete events management plugin voor gaming communities. Beheer en toon gaming events met custom post types, taxonomies, en Gutenberg block support.

== Description ==

Nexus Gaming Events is een krachtige plugin speciaal ontworpen voor gaming communities. Met deze plugin kun je eenvoudig gaming events beheren, tonen en organiseren binnen je WordPress website.

**Hoofdkenmerken:**

* **Custom Post Type voor Events** - Speciaal ontworpen post type voor gaming events
* **Game Taxonomie** - Categoriseer events per game type
* **Event Details** - Datum, tijd, host, locatie, en maximaal aantal spelers
* **Shortcode Support** - Toon events overal op je site met [nexus_events]
* **Gutenberg Block** - Geïntegreerde block voor de WordPress editor
* **Custom Templates** - Mooie single event pagina's
* **Admin Columns** - Overzichtelijke admin interface met custom kolommen
* **Responsive Design** - Werkt perfect op desktop, tablet en mobiel

**Perfect voor:**
* Gaming communities
* Esports organisaties
* Game servers
* Discord communities
* Twitch streamers
* Gaming forums

== Installation ==

1. Upload de `nexus-events` folder naar de `/wp-content/plugins/` directory
2. Activeer de plugin via het 'Plugins' menu in WordPress
3. Ga naar 'Events' in je WordPress admin om je eerste event aan te maken

== Frequently Asked Questions ==

= Hoe maak ik een event aan? =

Ga naar de WordPress admin, klik op 'Events' -> 'Nieuw Event' en vul de gegevens in. Je kunt datum, tijd, host, locatie en maximaal aantal spelers instellen.

= Hoe toon ik events op een pagina? =

Gebruik de shortcode `[nexus_events]` om alle toekomstige events te tonen. Je kunt ook parameters gebruiken:
- `[nexus_events limit="5"]` - Toon maximaal 5 events
- `[nexus_events category="fps"]` - Toon events van een specifieke game categorie
- `[nexus_events show_past="true"]` - Toon ook verleden events

= Werkt deze plugin met page builders? =

Ja! De plugin heeft een Gutenberg block en de shortcode werkt in elke page builder die shortcodes ondersteunt.

= Kan ik de look aanpassen? =

Ja, de plugin heeft CSS classes die je kunt overschrijven in je theme. Alle styles zijn opgebouwd met BEM naming voor makkelijke aanpassingen.

== Screenshots ==

1. Events overzicht in de WordPress admin
2. Event aanmaak formulier met alle metadata velden
3. Frontend event card display
4. Single event pagina template

== Changelog ==

= 1.0 =
* Eerste release van Nexus Gaming Events
* Custom post type voor events
* Game taxonomie
* Event metadata (datum, tijd, host, locatie, max spelers)
* Shortcode support
* Gutenberg block
* Custom templates
* Admin columns
* Responsive CSS styling

== Upgrade Notice ==

= 1.0 =
Eerste release - geen upgrade nodig.

== Arbitrary sections ==

== Shortcode Parameters ==

De `[nexus_events]` shortcode ondersteunt de volgende parameters:

- `limit` (number) - Maximaal aantal events om te tonen (default: 10)
- `category` (string) - Game category slug om te filteren
- `show_past` (string) - Toon verleden events (default: "false")
- `order` (string) - Sorteervolgorde (default: "ASC")

**Voorbeelden:**
```
[nexus_events]
[nexus_events limit="3" category="minecraft"]
[nexus_events show_past="true" order="DESC"]
```

== Custom CSS Classes ==

De plugin gebruikt de volgende CSS classes die je kunt stylen:

- `.nexus-events-container` - Hoofd container
- `.nexus-event-card` - Individuele event kaart
- `.nexus-event-image` - Event afbeelding
- `.nexus-event-content` - Content wrapper
- `.nexus-event-title` - Event titel
- `.nexus-event-date` - Datum display
- `.nexus-event-time` - Tijd display
- `.nexus-event-host` - Host informatie
- `.nexus-event-location` - Locatie informatie
- `.nexus-event-games` - Game categorieën
- `.nexus-event-excerpt` - Event samenvatting
- `.nexus-event-link` - Details link

== Template Hierarchy ==

De plugin zoekt naar templates in de volgende volgorde:
1. `single-nexus_event.php` in je theme
2. `nexus-events/single-nexus_event.php` in je theme
3. `templates/single-nexus_event.php` in de plugin

Je kunt je eigen single event template maken door een `single-nexus_event.php` bestand toe te voegen aan je theme.

== Developer Info ==

**Hooks en Filters:**
- `nexus_events_meta_fields` - Filter voor metadata velden
- `nexus_events_shortcode_args` - Filter voor shortcode parameters
- `nexus_events_before_event_card` - Action voor event card
- `nexus_events_after_event_card` - Action na event card

**Constants:**
- `NEXUS_EVENTS_VERSION` - Plugin versie
- `NEXUS_EVENTS_PLUGIN_DIR` - Plugin directory pad
- `NEXUS_EVENTS_PLUGIN_URL` - Plugin URL

== License ==

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
