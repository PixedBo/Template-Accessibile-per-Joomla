# Accessible Template for Joomla 5+ (Universal Model / Bootstrap Italia)

> ## ⚠️ THIS TEMPLATE IS NOT READY FOR PRODUCTION SITES
>
> This is a **WORK IN PROGRESS** project. Currently the template **does not yet pass** the checks of the Italian Department for Digital Transformation validators — see [Evaluation App for Municipalities and Schools websites, version 2.0 released](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) (in Italian).
>
> Anyone is welcome to **download, test, try and contribute**: issues, PRs and feedback are encouraged. Do not use it as the base of a real institutional website until full compliance with the official validators is reached.

## 📄 Description
This is the template aimed at integrating the **Universal Model** and the design system of **Designers Italia** natively on **Joomla 5+**.
Designed without the aid of heavy external frameworks (zero jQuery, pure vanilla CSS and native Javascript), it aims at a highly **accessible user experience (WCAG 2.1 AA/AAA)**, performant and future-proof.

The structure is based on **Bootstrap Italia 2.9.0** and leverages modern *Web Asset Manager* and native Joomla 5 namespaces.

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

## ⚙️ Backend Configuration (Template Options)
The template is designed to be "turn-key". By clicking on the template name in *Template Styles*, you'll have access to a control panel where you can customize your site without touching any code.

### 🎨 General / Branding
- **Municipality Logo:** Upload your institutional logo in SVG or PNG format.
- **Municipality Name & Subtitle:** Manage the main header texts (e.g., "Municipality of Bugliano" - "A municipality to live in").
- **Superior Entity:** Enter the name and link of the Region or parent entity (appears at the top of the page).
- **Primary Color:** Choose from 5 accessible and AgID-validated color themes. The site will automatically adapt buttons, backgrounds, hovers and icons:
  - Institutional Blue (Default)
  - Green (Municipality Model)
  - Red (School Model)
  - Teal (Healthcare/ASL Model)
  - Purple (Museum Model)

### 🧩 Header Options (Page Header)
- **Personal Area (Login):** Enable/disable the login button. You can link it to native Joomla login or a specific custom menu item.
- **Search Engine:** Enable/disable the magnifying glass icon. Natively integrated with Joomla's *Smart Search*, or redirectable to a specific page.

### 📱 Social Networks
Insert links to the entity's social channels. Icons (X, Facebook, YouTube, Telegram, WhatsApp) will automatically appear in the header and footer only if the corresponding field is filled.

---

## 📐 Available Module Positions
The template declares the following native module positions, designed to reflect the Bootstrap Italia grid:

- `selezione-lingua`: Dropdown menu for multilingual sites (Header top).
- `menu-principale`: The main navigation menu. **(N.B. Use a "Menu" module type and set the alternative layout to `comuni-menu`)**.
- `menu-secondario`: Service links (e.g., topics) placed to the right of the main menu. **(N.B. Use a "Menu" module type and set the alternative layout to `comuni-menu`)**.
- `top`: Full-width area below the header.
- `top-muted`: Area with light gray background for highlighted links or notices.
- `evidenza`: Section with dynamic background (based on primary color) for news/services slider.
- `calendario`: Area dedicated to event modules.
- `colonna-sinistra`: Sidebar for secondary navigation and page indexes (Scrollspy).
- `colonna-destra`: Sidebar for additional modules or quick actions.
- `bottom`: Full-width area above the footer.
- `footer1` and `footer2`: Columns for organizing links in the institutional footer.

---

## 💻 Advanced Customization (Custom CSS)
If you need to add custom CSS rules to override the native Bootstrap Italia or template styling, **do not modify the original files**.

Simply create a file called `custom.css` inside the template's `css/` folder (the path will be `/templates/TEMPLATE_NAME/css/custom.css`). 
The system will auto-detect and load it last, ensuring your rules have absolute priority and won't be overwritten during future template updates.

---

## 🌟 Included Overrides and Alternative Layouts
The template comes with native overrides aimed at making standard Joomla components generate HTML code aligned with the Designers Italia guidelines:

- **Articles Module (`mod_articles`):** Two specific layouts are included: a **3-column layout** and a **single layout**. The single layout is dynamic: if the module contains more than one article, the system automatically creates an accessible slideshow.
- **Menu Module (`mod_menu`):** The `comuni-menu` layout is included, essential and mandatory to correctly and accessibly structure both the Main Menu and Secondary Menu within the header.
- **Single Article (`com_content > article`):** Complete layout for services/news with automatic reading time calculation, accessible pagination, chip-style tags and native social sharing buttons.
- **Category Blog / List (`com_content > category`):** Override of `blog` and `default` with Bootstrap Italia-styled cards.
- **"Services" alternative layout (`com_content > category > servizi`):** Layout dedicated to the Municipality "Services" category, following the Comuni Model. Hero, list of service cards with `data-element="service-link"`, "Explore by topic" block with subcategories tagged `data-element="service-category-link"`. Selectable as *Alternative Layout* on any Blog or List Category menu item. Works with both views.

## 🏛️ PA Model integration (Designers Italia)
The template includes a mapping system between **Joomla categories** and the **content types** of the Comuni Model, so that the `data-element` attributes required by the Evaluation App are automatically applied to article links, wherever they appear (featured homepage, category lists, featured-article modules, etc.).

**How to configure:** from the backend, in *Template Styles → Accessible Template*, the **PA Model Categories** fieldset lets you pick which Joomla category corresponds to each content type:

| Parameter | Category | Applied `data-element` |
|---|---|---|
| `cat_services` | Municipality services | `service-link` |
| `cat_news` | News | `news-link` |
| `cat_events` | Events | `event-link` |
| `cat_documents` | Documents | `document-link` |

Resolution walks the category hierarchy: an article inside `Services > Registry office` automatically receives `service-link`.

A second fieldset **Evaluation Criteria – Municipality** lets you associate **menu items** to "functional" `data-element`s pointing to unique destinations (not article types): `management`, `all-services`, `all-topics`, `live`, `faq`, `report-inefficiency`, `accessibility-link`, `privacy-policy-link`, `news`.

The same fieldset also contains the **Enable feedback system** flag (default: NO), which toggles the "Page clarity feedback" widget described below.

Implementation lives in `helpers/ModelloPAHelper.php` (loaded via `require_once` from the overrides) and is called by the `com_content` and `mod_articles` layouts.

## 📝 "Page clarity feedback" widget (C.SI.2.5)
The template ships with the page-clarity feedback block required by the Comuni Model: 1-5 stars, conditional follow-up question (preferred aspects if rating ≥ 4, difficulties if rating ≤ 3), detail text field and a final thank-you message. All the `data-element` attributes required by the App Valutazione Modelli are emitted: `feedback`, `feedback-title`, `feedback-rate-1..5`, `feedback-rating-positive`/`negative`, `feedback-rating-question` (×2), `feedback-rating-answer` (×10), `feedback-input-text`.

The widget renders **only on single article pages** (`com_content` view `article`), full-width below `<main>`, with the template's primary color as background. The JS controller (`js/feedback-chiarezza.js`, vanilla ES6) is loaded conditionally on the same pages and only when the flag is enabled.

> ⚠️ **The widget is currently a pure demo:** the interface matches the Comuni Model but answers are **not stored anywhere yet**. A user-visible warning banner is shown above the widget. Enable the flag only if you need the HTML structure to pass the App Valutazione Modelli checks, pending integration with a feedback collection backend. Toggle it from *Template Styles → Accessible Template → Evaluation Criteria - Municipality → Enable feedback system*.

## 🧪 Compliance status
- ✅ Main menu with `data-element="main-navigation"` and mappable items.
- ✅ Login button with `data-element="personal-area-login"`.
- ✅ `service-link`, `news-link`, `event-link`, `document-link` attributes applied automatically via the category map.
- ✅ "Services" layout with `service-link` + `service-category-link`.
- ✅ Article index with `data-element="page-index"`, topic tag with `data-element="topic-element"`.
- ⚠️ "Page clarity feedback" widget (C.SI.2.5): full HTML structure with every required `data-element`, **but currently a demo placeholder** — answers are not stored yet. Toggle via the "Enable feedback system" flag in the "Evaluation Criteria – Municipality" fieldset.
- 🚧 Some `data-element`s required by the Evaluation App (e.g. detailed structures for Administration/Offices, Event, Document, Service pages) are **not complete yet**. That is why the template **does not fully pass** the Department for Digital Transformation checks — see [notice at the top of this page](#-this-template-is-not-ready-for-production-sites).

## 🤝 Contributing
The project is open to community contributions:

- Open an **issue** for bugs, compliance gaps or feature proposals.
- Send a **pull request** if you fixed something (fixes, new overrides, new layouts dedicated to Administration, Event, Document, etc. are welcome).
- Test the template by installing it in a dev environment and run the [Evaluation App for Municipalities and Schools websites](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) (in Italian) to see which criteria still fail.

## 📜 License and Credits
This template is released under **GNU GPL v3** license.  
Based on the UI/UX resources of [Designers Italia](https://designers.italia.it/) and the [Bootstrap Italia](https://italia.github.io/bootstrap-italia/) framework.
