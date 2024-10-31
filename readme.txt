=== Sellsy ===
Contributors: sellsy, eewee
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: CRM, Sellsy, form, contact
Requires at least: 5.6
Tested up to: 6.1
Stable tag: 2.3.3
Requires PHP: 7.4

Créer des formulaires lié à votre compte Sellsy (prospect, opportunité, support ticket).

== Description ==

#### Formulaire de prospection

Le formulaire de prospection vous permet d'enregistrer les données du formulaires dans Sellsy.
Vous pouvez ainsi créer un prospect et/ou une opportunité dans Sellsy lors de la soumission du formulaire.

#### Formulaire de Support

Le formulaire de prospection vous permet d'enregistrer les données du formulaires dans Sellsy.
Vous pouvez ainsi créer un ticket dans Sellsy lors de la soumission du formulaire.

#### Tracking

Vous pouvez activer/désactiver la gestion du tracking sur votre site Wordpress.
Ainsi, si vous activez ce dernier, les différentes pages consultés par le visiteur seront enregistrée, puis lors de la soumission du formulaire de prospection/opportunité elles seront enregistrées sur Sellsy (cela vous permet de suivre les consultations réalisées par le visiteur).

#### Clearbit

Vous pouvez ajouter un token Clearbit (de type Enrichment API).
Pour cela vous devez disposer d'un compte Clearbit Enrichment API, puis ajouter votre token Clearbit sur le plugin Wordpress.
Ainsi, lors de la soumission d'un formulaire de ce plugin, certaines données trouvées à partir de l'email indiqué seront enregistrées sur Sellsy (sur un commentaire du Prospect).

#### ReCaptcha

* Vous pouvez actriver/désactiver le captcha pour sécuriser la soumission de vos formulaires.
* reCAPTCHA ([Google](https://policies.google.com/?hl=en))

#### Champs personnalisés actuellement supportés

* Texte simple (text)
* Texte riche (textarea)
* Liste de choix (select)
* Valeur nuemrique (text)
* Adresse email (email)
* Adresse web (url)
* Date (text)
* Time (text)
* Oui / Non (boolean)
* Montant avec devise (text)
* Champ numerique avec unite (text)
* Bouton radio (radio)
* Choix multiple (checkbox)
* Produit / service (select)
* Collaborateur (select)

== Installation ==

1. Download the plugin Zip archive.
1. Upload plugin folder to your '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Settings : add your key API Sellsy.

== FAQ ==

1. [FAQ](https://faq.sellsy.fr/aide/categorie/167)

== Screenshots ==

1. Configuration générale.
1. Configuration formulaire de contact.
1. Configuration formulaire de support/ticket.
1. Rendu du formulaire de contact.
1. Rendu du formulaire de support/ticket.

== Changelog ==

= 2.3.3 =

* Allow emails to @msn.com.

= 2.3.2 =

* Adjustment required fields.

= 2.3.1 =

* Update Requires version and tested.

= 2.3.0 =

* Fields required by admin.
* Add domain authorize, me.com
* Form contact, change wording.

= 2.2.9 =

* Update for php8.0.

= 2.2.8 =

* Update reCaptcha.

= 2.2.7 =

* CF - text simple, text rich with empty value. And adjustment reCaptcha.

= 2.2.6 =

* Blacklist email adjustment.

= 2.2.5 =

* Update readme.

= 2.2.4 =

* Add reCaptcha v3.

= 2.2.3 =

* CF date adjustment format date.

= 2.2.2 =

* Form clean.

= 2.2.1 =

* Fix on CF checkbox.

= 2.2 =

* Version stable (all CF).

= 2.1.32 =

* FO - add CF staff
* BO - add CF staff

= 2.1.31 =

* FO - add CF item
* BO - add CF item

= 2.1.30 =

* FO - add CF checkbox
* BO - add CF checkbox

= 2.1.29 =

* FO - add CF radio
* BO - add CF radio

= 2.1.28 =

* FO - add CF unit
* BO - add CF unit

= 2.1.27 =

* FO - add CF amount
* BO - add CF amount

= 2.1.26 =

* FO - add CF boolean
* BO - add CF boolean

= 2.1.25 =

* FO - add CF time
* BO - add CF time

= 2.1.24 =

* FO - add CF date
* BO - add CF date

= 2.1.23 =

* FO - add CF address url
* BO - add CF address url

= 2.1.22 =

* FO - add CF address email
* BO - add CF address email

= 2.1.21 =

* FO - add CF numeric
* BO - add CF numeric

= 2.1.20 =

* BO - authorize email hotmail.co.uk, live.co.uk, live.com
* BO - doublon email (min/maj)

= 2.1.19 =

* BO - form notification + add subject prefix
* BO - db - table contact_form, field contact_form_setting_notification_email_enable, contact_form_setting_notification_email_prefix_enable, contact_form_setting_notification_email_prefix_value

= 2.1.18 =

* FO - wording error message

= 2.1.17 =

* FO - anchor on form error

= 2.1.16 =

* FO - add address to template email

= 2.1.15 =

* BO - wording required field
* BO - encode textarea

= 2.1.14 =

* FO - form position notification after submit

= 2.1.13 =

* FO - form id multiple

= 2.1.12 =

* BO - fields required + sql contact_form

= 2.1.11 =

* BO - fix update sql

= 2.1.10 =

* FO - CF required clean notice

= 2.1.9 =

* FO - CF required

= 2.1.8 =

* BO - checkbox GDPR
* BO - db - table contact_form, field contact_form_condition_accept

= 2.1.7 =

* BO - opp change linkedtype
* FO - address client

= 2.1.6 =

* FO - add address
* BO - add address
* BO - edit table contact_form

= 2.1.5 =

* BO - add potential

= 2.1.4 =

* FO - gutenberg shortcode

= 2.1.3 =

* FO - add email on prospect
* FO - add a contact on an existing prospect
* FO - move position success/error message

= 2.1.2 =

* FO - add email on prospect
* FO - add a contact on an existing prospect
* FO - js submit (double date before > single data now)

= 2.1.1 =

* BO - delete email subject (date/heure)
* BO - add email replyTo with form email FO

= 2.1.0 =

* FO - check double third (add email)
* BO - notice CF

= 2.0.0 =

* FO - clearbit API > submit form > add clearbit data in comment
* FO - trashmail clean
* BO - add clearbit API (Enrichment)

= 1.9.3 =

* FO - delete email in trashmail list
* FO - change size cookie tracking and utm.

= 1.9.2 =

* FO - trashmail check

= 1.9.1 =

* BO - db charset

= 1.9 =

* BO - new installation system

= 1.8 =

* BO/FO - notice clean + reCaptcha v2

= 1.7 =

* BO - change url API

= 1.6 =

* FO - duplication

= 1.5 =

* FO - phone, lang browser

= 1.4 =

* change gitignore
* add opportunity url in email
* limit size cookie (x2)

= 1.3 =

* BO - add cf select
* FO - add responsible contact
* FO - placeholer opacity (phone / mobile)

= 1.2 =

* Update : notification email and Reply-To with email form
* FO - Phone field
* FO - utm_source (use get or cookie or config)
* BO - Change wording button, marketing
* BO - Duplicate form contact

= 1.1 =

* Update.

= 1.0 =

* 1st version plugin Sellsy.
