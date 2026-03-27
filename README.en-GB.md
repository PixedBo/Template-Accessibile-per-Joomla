# Accessible Template for Joomla 5+ (Universal Model / Bootstrap Italia)

## 📄 Description
This is the definitive template for integrating the **Universal Model** and the design system of **Designers Italia** natively on **Joomla 5+**. 
Designed without the aid of heavy external frameworks (zero jQuery, pure vanilla CSS and native Javascript), it guarantees a highly **accessible user experience (WCAG 2.1 AA/AAA)**, extremely performant and future-proof.

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
The template comes with powerful native overrides to ensure that standard Joomla components generate HTML code compliant with the Designers Italia guidelines:

- **Articles Module (`mod_articles`):** Two specific layouts are included: a **3-column layout** and a **single layout**. The single layout is dynamic: if the module contains more than one article, the system automatically creates an accessible slideshow.
- **Menu Module (`mod_menu`):** The `comuni-menu` layout is included, essential and mandatory to correctly and accessibly structure both the Main Menu and Secondary Menu within the header.
- **Single Article (`com_content > article`):** Complete layout for services/news with automatic reading time calculation, accessible pagination, chip-style tags and native social sharing buttons.

## 📜 License and Credits
This template is released under **GNU GPL v3** license.  
Based on the UI/UX resources of [Designers Italia](https://designers.italia.it/) and the [Bootstrap Italia](https://italia.github.io/bootstrap-italia/) framework.
