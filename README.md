<h1 align="center">Orbis Deals</h1>

<p align="center">
	The Orbis Deals plugin extends your <a href="https://github.com/pronamic/wp-orbis">Orbis</a> environment with the option to add deals.
</p>

<p align="center">
	<a href="https://github.com/pronamic/wp-orbis-deals/releases"><img src="https://img.shields.io/github/v/release/pronamic/wp-orbis-deals" alt="Latest release"></a>
	<a href="https://www.gnu.org/licenses/gpl-2.0.html"><img src="https://img.shields.io/badge/license-GPL--2.0--or--later-blue" alt="License: GPL-2.0-or-later"></a>
	<img src="https://img.shields.io/badge/PHP-%3E%3D7.2-777bb4" alt="PHP >= 7.2">
	<img src="https://img.shields.io/badge/WordPress-%3E%3D5.2-21759b" alt="WordPress >= 5.2">
</p>

## Table of contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [REST API](#rest-api)
- [Templates](#templates)
- [Development](#development)
- [Links](#links)

## Features

- **Deals post type** (`orbis_deal`) with a public archive at `/deals/`.
- **Deal details** meta box to link a deal to an Orbis organization and set a price and status.
- **Statuses**: `pending`, `won` and `lost`. Status changes are logged as comments on the deal.
- **Deal lines**: quantity, description and amount per line, shown below the deal content.
- **Admin columns** for organization, price and status.
- **Posts 2 Posts connections** between deals and organizations, and between deals and persons.
- **Filter by status** with the `orbis_deal_status` query variable, for example `/deals/?orbis_deal_status=won`.
- **Translations**: Dutch (`nl_NL`) included.

## Requirements

- PHP 7.2 or higher
- WordPress 5.2 or higher
- [Orbis](https://wordpress.org/plugins/orbis/)
- [Posts 2 Posts](https://wordpress.org/plugins/posts-to-posts/)

## Installation

Download the latest `orbis-deals.zip` from the [releases page](https://github.com/pronamic/wp-orbis-deals/releases) and upload it via **Plugins → Add New → Upload Plugin** in WordPress.

## Usage

1. Go to **Deals → Add New** in the WordPress admin.
2. Enter a title and description.
3. In the **Deal Details** meta box, select the organization, enter the price and choose a status.
4. Publish the deal.

When you change the status to **Won** or **Lost**, a comment is added to the deal with the new status and the user who changed it.

## REST API

The plugin registers routes in the `orbis/v1` namespace:

| Method | Route                               | Description                 |
| ------ | ----------------------------------- | --------------------------- |
| `GET`  | `/wp-json/orbis/v1/deals/{post_id}` | Get a deal and its lines.   |
| `POST` | `/wp-json/orbis/v1/deals/{post_id}` | Update the lines of a deal. |

## Templates

The plugin includes templates for the deals archive and the deal details. To override the archive, add an `archive-orbis_deal.php` file to your theme. The plugin template is used only when the theme does not have one.

| Template                 | Description                         |
| ------------------------ | ----------------------------------- |
| `archive-orbis_deal.php` | Deals archive.                      |
| `deal-details.php`       | Deal details.                       |
| `deal-lines.php`         | Deal lines, appended to the content. |
| `deals-stats.php`        | Deal statistics.                    |

## Development

Clone the repository and install the dependencies:

```sh
git clone https://github.com/pronamic/wp-orbis-deals.git
cd wp-orbis-deals
composer install
npm install
```

Start a local WordPress environment with [`@wordpress/env`](https://www.npmjs.com/package/@wordpress/env). It installs Orbis, Posts 2 Posts, Pronamic Client and Query Monitor:

```sh
npx wp-env start
```

### Composer scripts

| Command                  | Description                                              |
| ------------------------ | -------------------------------------------------------- |
| `composer phpcs`         | Check the code against the Pronamic coding standards.    |
| `composer phpcbf`        | Fix coding standard violations automatically.            |
| `composer build`         | Build the plugin and create a distribution archive in `build/`. |
| `composer make-pot`      | Update the `.pot` and `.po` translation files.           |

## Links

- [Pronamic](https://www.pronamic.eu/)
- [Orbis](https://github.com/pronamic/wp-orbis)
- [Issues](https://github.com/pronamic/wp-orbis-deals/issues)

---

<p align="center">
	<a href="https://www.pronamic.eu/">Pronamic</a> · GPL-2.0-or-later
</p>
