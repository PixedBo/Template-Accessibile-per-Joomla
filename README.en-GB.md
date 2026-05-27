# Accessible Template for Joomla 5+ (Universal Model / Bootstrap Italia)

> **The template passes the automated validation of the [App Valutazione Modelli](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) by the Italian Department for Digital Transformation** (Modello Comuni). The only two errors reported are independent of the template itself: the lack of an institutional domain (`comune.*.it`) and the absence of an accessibility statement, both of which must be provided by the municipality.
>
> The template is still **under active development and testing**: new features are in progress and there may be structural changes between versions. Anyone is welcome to **download, test and contribute**: issues, PRs and feedback are encouraged.

## 📄 Description

This is the template aimed at integrating the **Universal Model** and the design system of **Designers Italia** natively on **Joomla 5+**. The template is primarily built for **Italian Public Administration** (Municipalities, Schools, Healthcare, Museums), but it is suitable for any site that wants to follow the same high accessibility standards.

Designed without heavy external frameworks (zero jQuery, pure vanilla CSS and native JavaScript), it aims at a highly **accessible user experience (WCAG 2.1 AA/AAA)**, performant and future-proof. The structure is based on **Bootstrap Italia 2.9.0** and leverages modern *Web Asset Manager* and native Joomla 5 namespaces.

## 🛠 System Requirements

- **Joomla!**: 5.0.0 or higher
- **PHP**: 8.2.0 or higher

## 🚀 Installation

Installation follows the classic standard Joomla procedure. No coding required.

1. Go to the [Releases](https://github.com/PixedBo/Template-Accessibile-per-Joomla/releases) page of this repository.
2. Download the latest version of the installation package (e.g., `tpl_accessibile_vX.X.X.zip`).
3. Log in to your Joomla site backend.
4. Navigate to **System** > **Install** > **Extensions**.
5. Drag the downloaded `.zip` file into the upload area.
6. Go to **System** > **Template Styles (Site)** and set "Accessible Template" as default (by clicking the star).

---

## ⚡ Quickstart

A pre-configured Akeeba backup package (`.jpa`) is available to get a fully working example site up and running without starting from scratch.

- **[📦 Download Quickstart package](doc/quickstart/quickstart-JoomlaPA-Akeeba.jpa)**
- **[📖 Installation instructions](doc/quickstart/istruzioni.md)** *(currently in Italian only)*

---

## ⚙️ Configuration

The template is "turn-key": everything is configured from the **Template Styles → Accessible Template** panel in the Joomla backend, without touching any code.

Main options include logo, entity name, primary color (5 accessible color themes), header, social networks, footer and "Contatta" contact block.

> **Note:** The full configuration documentation is currently available in Italian only.  
> **→ Full guide (IT):** [`doc/it/configurazione-joomla.md`](doc/it/configurazione-joomla.md)  
> **→ Colors and themes (IT):** [`doc/it/colori-e-temi.md`](doc/it/colori-e-temi.md)

---

## 📐 Module Positions

The template declares the following native module positions:

`selezione-lingua` · `menu-principale` · `menu-secondario` · `percorso` · `top` · `top-muted` · `evidenza` · `calendario` · `colonna-sinistra` · `colonna-destra` · `bottom` · `bottom2` · `footer1` · `footer2`

Menu modules must use the `comuni-menu` alternative layout.

**→ Full position guide (IT):** [`doc/it/moduli.md`](doc/it/moduli.md)

---

## 💻 Custom CSS

To add custom CSS rules without modifying the original files, create the file `/templates/tpl_accessibile/css/custom.css`. The system auto-detects and loads it last, giving your rules absolute priority.

---

## 🌟 Included Overrides and Alternative Layouts

The template includes overrides for the main Joomla components (articles, menus, breadcrumbs, categories) and specialized alternative layouts for the Comuni Model:

| Layout | Description |
|--------|-------------|
| `com_content/article/default` | Standard article with reading time, scrollspy, social sharing |
| `com_content/article/note-legali` | Legal Notes page (C.SI.3.4) with mandatory CC-BY 4.0 section |
| `com_content/article/scheda-servizio` | Municipal Service Sheet (C.SI.1.3) with JSON-LD and feedback widget |
| `com_content/category/notizie` | News category with featured articles at the top |
| `com_content/category/servizi` | Services category with search and subcategories |
| `com_content/category/amministrazione` | Administration category with "In evidenza" featured section |
| `com_content/category/vivere` | "Vivere il Comune" page with events and places |
| `mod_articles/evidenza-singolo` | Article module: single or automatic accessible slideshow |
| `mod_articles/evidenza-tre-colonne` | Article module: 3-column card layout |
| `mod_menu/comuni-menu` | Mandatory menu layout for Bootstrap Italia header |
| `mod_breadcrumbs/default` | Breadcrumbs with schema.org microdata and `data-element` |
| `com_dpcalendar/calendar/vivere` | *(DPCalendar)* "Vivere il Comune" page with events and locations from DPCalendar |
| `com_dpcalendar/list/eventi` | *(DPCalendar)* Events list with featured carousel, filter and accessible pagination |
| `com_dpcalendar/event/default` | *(DPCalendar)* Single event sheet with TOC, map, booking and Vertical Calendar component |
| `mod_dpcalendar_upcoming/joomlaPA` | *(DPCalendar)* "Upcoming events" module with Bootstrap Italia card layout |

**→ Documentation (IT):** [`doc/it/`](doc/it/)  
**→ DPCalendar integration guide (IT):** [`doc/it/integrazione-DPCalendar.md`](doc/it/integrazione-DPCalendar.md)

---

## 🏛️ Comuni Model Compliance (Designers Italia)

The template **passes the automated validation** of the App Valutazione Modelli (Modello Comuni). The only two errors reported by the validator are independent of the template:

- **Institutional domain** — the validator requires a `comune.*.it` domain; this is not a template requirement.
- **Accessibility statement** — must be published by the municipality on their own site.

The template implements all required `data-element` attributes:

- Menu item → functional `data-element` mapping (tab "Valutazione Comuni")
- "Page clarity feedback" widget (C.SI.2.5/2.6) — full HTML structure with all required `data-element`s
- Service Sheet layout (C.SI.1.3) with all `data-element`s and `GovernmentService` JSON-LD
- Legal Notes page (C.SI.3.4) with non-editable CC-BY 4.0 text

**→ Full guide (IT):** [`doc/it/valutazione-modelli.md`](doc/it/valutazione-modelli.md)  
**→ Complete setup path for a Comune site (IT):** [`doc/it/indice.md`](doc/it/indice.md)

## 🧪 Compliance Status

- ✅ Main menu with `data-element="main-navigation"` and login with `data-element="personal-area-login"`
- ✅ Breadcrumbs with `data-element="breadcrumb"` and schema.org microdata
- ✅ Services layout with `service-link` + `service-category-link`
- ✅ Legal Notes page (C.SI.3.4) with `data-element="legal-notes"` and CC-BY 4.0 license verbatim
- ✅ Service Sheet layout (C.SI.1.3) with all `data-element`s and JSON-LD
- ✅ News, Administration, Vivere il Comune category layouts
- ✅ Feedback widget (C.SI.2.5/2.6) — full HTML structure with all required `data-element`s
- ✅ DPCalendar integration: event sheet with Vertical Calendar, map and booking; events list with featured carousel and filter
- 🚧 Specialized layouts for Ufficio, Documento, Luogo, Evento (native article), Persona, Notizia (single article) not yet implemented

---

## 📸 Screenshots

**18 out of 20 criteria passed:**

![Comuni Model test passed](doc/img/test-comuni-passato.jpg)

**The two failing criteria (institutional domain and accessibility statement):**

![Failing criteria](doc/img/criteri-non-superati.jpg)

---

---

## 🤝 Contributing

The project is open to community contributions:

- Open an **issue** for bugs, compliance gaps or feature proposals.
- Send a **pull request** if you fixed something (fixes, new overrides, new dedicated layouts are welcome).
- Test the template in a dev environment and run the [Evaluation App for Municipalities and Schools](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) (Italian only) to see which criteria still fail.

## 📜 License and Credits

This template is released under **GNU GPL v3** license.  
Based on the UI/UX resources of [Designers Italia](https://designers.italia.it/) and the [Bootstrap Italia](https://italia.github.io/bootstrap-italia/) framework.
