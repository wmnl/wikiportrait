<?php

//@phpcs:disable Generic.Files.LineLength.TooLong

// Dutch is the source language: every key must exist in this file.
// Other languages only need to contain the keys they translate.
// Keys starting with "js." are also available in JavaScript via i18n('key').
return [
    // Locale and date patterns (ICU format, see IntlDateFormatter)
    'meta.locale' => 'nl_NL',
    'date.format' => 'd MMMM yyyy',
    'date.format_time' => "d MMMM yyyy 'om' HH:mm:ss",
    'date.format_month' => 'MMMM',

    // Common
    'common.yes' => 'Ja',
    'common.no' => 'Nee',
    'common.cancel' => 'Annuleren',
    'common.add' => 'Toevoegen',
    'common.save' => 'Opslaan',
    'common.edit' => 'Bewerken',
    'common.delete' => 'Verwijderen',
    'common.actions' => 'Acties',
    'common.title' => 'Titel',
    'common.message' => 'Bericht',
    'common.information' => 'Informatie',
    'common.back_home' => 'Terug naar de startpagina',
    'common.upload_another' => 'Nog een afbeelding uploaden',
    'common.label_title' => 'Titel:',
    'common.label_description' => 'Beschrijving:',

    // Errors
    'error.admin_only' => '<br>Deze pagina is voor admins bedoeld.<br>',
    'error.duplicate' => 'Deze afbeelding is al geüpload',
    'error.invalid_request' => 'Ongeldig verzoek.',
    'error.generic' => 'Er is iets misgegaan, probeer het opnieuw.',
    'error.csrf' => 'Je sessie is verlopen. Ga terug, vernieuw de pagina en probeer het opnieuw.',

    // Header, footer and menu
    'header.title' => "Wikiportret - Stel uw foto's ter beschikking",
    'header.description' => 'Staat er op Wikipedia een artikel zonder portretfoto? En heeft u een foto die bij een artikel zou passen? Stel dan uw foto hier ter beschikking.',
    'footer.text' => 'Wikiportret is een initiatief van <a href="http://www.wikimedia.nl/" title="Wikimedia Nederland">Wikimedia Nederland</a> &#9679; <a href="https://donations.wikimedia.nl/">Doneer aan Wikimedia</a> &#9679; <a href="{privacy_url}">Privacyverklaring</a>',
    'menu.submissions' => 'Inzendingen',
    'menu.admin' => 'Beheer',
    'menu.analysed' => '(in {month} geanalyseerd: {count})',
    'menu.logout' => 'Uitloggen',

    // Validation
    'validation.required' => 'Er is geen {field} ingevuld!',
    'validation.agree' => 'Gelieve akkoord gaan met {terms}.',
    'validation.username_taken' => 'Deze gebruikersnaam is reeds geregistreerd!',
    'validation.password_mismatch' => 'De twee ingevulde wachtwoorden komen niet overeen!',
    'validation.invalid_email' => 'Geen geldig e-mailadres ingevuld!',
    'validation.unsupported_format' => 'Dit bestandsformaat wordt niet ondersteund. Toegestane formaten: JPG, PNG, GIF, BMP.',
    'validation.no_file' => 'Er is geen bestand geselecteerd.',
    'validation.not_an_image' => 'Het bestand dat geüpload is, is geen afbeelding of dit bestandsformaat wordt niet ondersteund.',
    'validation.invalid_image' => 'Het bestand kon niet worden verwerkt. Controleer of het een geldig afbeeldingsbestand is.',
    'validation.file_too_large' => 'Het bestand is te groot. Maximum uploadgrootte is {max}.',
    'validation.source_unknown' => 'Een foto waarvan niet bekend is wie hem gemaakt heeft, kan niet worden geüpload.',

    // Field names, used in validation.required
    'field.title' => 'titel',
    'field.source' => 'auteursrechthebbende',
    'field.name' => 'naam',
    'field.username' => 'gebruikersnaam',
    'field.vrts_name' => 'VRTS-naam',
    'field.password' => 'wachtwoord',
    'field.message' => 'bericht',

    // Terms, used in validation.agree
    'terms.license' => 'de licentievoorwaarden, de privacyverklaring en het opslaan van uw IP-adres',
    'terms.storage' => 'de toestemming voor het opslaan van uw gegevens',

    // Mail (the mail bodies are in the mail/ folder)
    'mail.subject.uploaded' => '[Wikiportret] {title} is geüpload op Wikiportret',
    'mail.subject.verify' => '[Wikiportret] Uw email verifiëren',
    'mail.subject.low_resolution' => '[Wikiportret] {title} is niet geschikt: resolutie te laag',
    'mail.vrts_queue_name' => 'Wikiportret VRTS queue',

    // Commons upload
    'commons.ticket_placeholder' => 'VUL_HIER_HET_TICKET_NUMMER_IN',

    // Home page
    'index.welcome' => 'Welkom op Wikiportret!',
    'index.intro1' => 'Staat er op Wikipedia een artikel zonder portretfoto? En heeft u een foto die bij een artikel zou passen? Stel dan uw foto hier ter beschikking en de vrijwilligers van Wikipedia doen de rest.',
    'index.intro2' => "Als u veel foto's heeft, of bijvoorbeeld foto's met andere onderwerpen dan beroemdheden overweegt u dan uw foto's onder te brengen bij Wikimedia Commons, de centrale mediadatabank van Wikipedia.",
    'index.submit_button' => 'Een portretfoto insturen',

    // Uploads closed
    'closed.text' => "Helaas is het momenteel niet mogelijk om foto's te uploaden. Het is niet bekend wanneer Wikiportret weer beschikbaar is. U kunt in de tussentijd uw foto rechtstreeks uploaden naar <a href=\"https://commons.wikimedia.org/wiki/Special:UploadWizard?uselang=nl\" target=\"_blank\" rel=\"noopener nofollow\">Wikimedia Commons</a>",
    'closed.apology' => 'Onze excuses voor het ongemak<br>Team Wikiportret',

    // Upload wizard
    'wizard.title' => 'Uploadwizard',
    'wizard.q.copyright' => 'Is het hoofdonderwerp van de foto mogelijk auteursrechtelijk beschermd? (Bijvoorbeeld: hoes van cd of dvd, logo, reclamebord)',
    'wizard.q.copyright_help' => "Als u een foto maakt, heeft u zelf de auteursrechten van die foto. Als u echter een foto maakt van iets anders, waarop ook auteursrechten rusten, dan mag u uw foto toch niet vrij verspreiden. Dat kan bijvoorbeeld zo zijn bij foto's van een cd-hoes, dvd-hoes, logo, reclamebord of (film)poster. Het uploaden van uw foto betekent dan dat u de auteursrechten schendt op die cd-hoes enz.",
    'wizard.q.ownwork' => 'Heeft u de foto zelf gemaakt?',
    'wizard.q.employment' => 'Heeft u de foto gemaakt in opdracht van een ander?',
    'wizard.q.employment_help' => "Als een ander u opdracht heeft gegeven om foto('s) te maken, dan heeft die ander (ook) auteursrechten op de foto. Dat kan bijvoorbeeld uw werkgever zijn, of de organisatie of persoon waar u (freelance) een opdracht doet. Als u een foto maakt in opdracht van uw werkgever, dan heeft uw werkgever (bijna) altijd auteursrechten op de foto. Als u de foto heeft gemaakt voor een andere opdrachtgever, dan ligt het eraan wat u afgesproken heeft over de auteursrechten. Als de auteursrechten (deels) bij uw werkgever/opdrachtgever liggen, mag u de foto niet zomaar uploaden, ook al heeft u de foto zelf gemaakt.",
    'wizard.q.employpermission' => 'Geeft de opdrachtgever toestemming voor onbeperkte verspreiding, bewerking en commercieel gebruik van de foto?',
    'wizard.q.employpermission_help' => "Als u de foto in opdracht van een ander hebt gemaakt, heeft u toestemming van die ander nodig om de foto te uploaden. Dit kunt u van tevoren hebben afgesproken (u heeft dan met de opdrachtgever afgesproken dat de auteursrechten volledig bij u liggen, en niet bij de opdrachtgever), maar u kunt ook achteraf toestemming vragen. 'Onbeperkt' betekent dat anderen zonder toestemming van de opdrachtgever uw foto mogen gebruiken, zonder dat zij aan u of de opdrachtgever hoeven te melden dat ze de foto gebruiken. Wel moet de ander u en/of de opdrachtgever als auteur noemen (tenzij u en de opdrachtgever aangegeven hebben dat dat niet nodig is). 'Onbeperkt' betekent dus ook dat de foto voor een commercieel doel gebruikt kan worden, bijvoorbeeld in een tijdschrift, website of reclamefolder.",
    'wizard.q.ownpermission' => 'Geeft u toestemming voor onbeperkte verspreiding, bewerking en commercieel gebruik van de foto?',
    'wizard.q.ownpermission_help' => "'Onbeperkt' betekent dat anderen zonder uw toestemming uw foto mogen gebruiken, zonder dat zij aan u hoeven te melden dat ze de foto gebruiken. Wel moet de ander u als auteur noemen (tenzij u zelf aangegeven hebt dat dat niet nodig is). 'Onbeperkt' betekent dus ook dat uw foto voor een commercieel doel gebruikt kan worden, bijvoorbeeld in een tijdschrift, website of reclamefolder.",
    'wizard.advice' => 'Het advies van de uploadwizard is:',
    'wizard.no_upload' => 'Niet uploaden',
    'wizard.upload_done' => 'Uploaden voltooid',
    'wizard.verification_required' => 'Email verificatie vereist',
    'wizard.verification_sent' => 'Wij hebben een email naar <strong>{email}</strong> gestuurd voor verificatie.<br />Na succesvolle verificatie, is uw upload voltooid.<br />',
    'wizard.upload_failed' => 'Uploaden is niet gelukt',
    'wizard.upload_failed_text' => 'Helaas, het is niet gelukt deze afbeelding te uploaden. Wellicht zijn er technische problemen. Probeer het later nog eens.',
    'wizard.low_resolution' => 'Afbeelding niet geschikt',
    'wizard.low_resolution_text' => 'De resolutie van deze afbeelding is te laag voor Wikipedia. De afbeelding is daarom niet in behandeling genomen.<br />Wij hebben een e-mail naar <strong>{email}</strong> gestuurd met meer informatie.',
    'wizard.upload_other' => 'Andere afbeelding uploaden',
    'wizard.upload_license' => 'Upload de afbeelding onder de <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.nl" target="_blank">CC-BY-SA 4.0</a> licentie',
    'wizard.upload_button' => 'Uploaden',
    'upload.success_text' => 'De afbeelding is met succes geüpload.<br />Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.<br />Als u wilt, kunt u <a href="{track_url}">hier</a> uw inzending volgen.',

    // Upload form
    'upload.title' => 'Uploadformulier',
    'upload.choose_file' => 'Kies een bestand',
    'upload.depicted' => 'Wie staat er op de foto?',
    'upload.own_photo' => 'Heeft u deze foto zelfgemaakt?',
    'upload.own_yes' => 'Ja, ik heb deze foto zelf gemaakt',
    'upload.own_no' => 'Nee, ik heb deze foto niet zelf gemaakt',
    'upload.own_unknown' => 'Ik weet niet wie de foto gemaakt heeft',
    'upload.photographer' => 'Wat is de naam van de fotograaf?',
    'upload.source_unknown_warning' => "<strong>Deze foto kan helaas niet worden geüpload.</strong><br>Alleen de maker van een foto (de fotograaf) kan toestemming geven om de foto onder een vrije licentie te publiceren. Als niet bekend is wie de foto gemaakt heeft, kan niemand die toestemming geven en kunnen wij de foto niet op Wikipedia plaatsen. Het heeft daarom geen zin om de rest van het formulier in te vullen. Weet u wie de fotograaf is? Kies dan voor 'Nee, ik heb deze foto niet zelf gemaakt' en vul de naam van de fotograaf in.",
    'upload.source_other_info' => '<strong>De fotograaf moet toestemming geven.</strong><br>Vul hieronder de naam van de fotograaf in. Alleen de fotograaf kan toestemming geven om de foto onder een vrije licentie te publiceren. Na het uploaden ontvangt u een e-mail met een tekst die de fotograaf kan gebruiken. De fotograaf moet die toestemming vervolgens zelf, vanaf een eigen e-mailadres, naar ons mailen. Zonder deze toestemming kunnen wij de foto niet op Wikipedia plaatsen.',
    'upload.your_name' => 'Uw naam',
    'upload.your_email' => 'Uw e-mailadres',
    'upload.date' => 'Wanneer is de foto genomen?',
    'upload.optional' => '(optioneel)',
    'upload.description' => 'Omschrijving',
    'upload.license_terms' => 'Licentievoorwaarden',
    'upload.license_text' => "Door het uploaden van dit materiaal en het klikken op de knop 'Upload foto' verklaart u dat u de rechthebbende eigenaar bent van het materiaal. Door dit materiaal te uploaden geeft u toestemming voor het gebruik van het materiaal onder de condities van de door u geselecteerde licentie(s), deze condities variëren per licentie maar houden in ieder geval in dat het materiaal verspreid, bewerkt en commercieel gebruikt mag worden door eenieder. Voor de specifieke extra condities per licentie verwijzen wij u naar de bijbehorende licentieteksten. Na het vrijgeven kunt u niet meer terugkomen op deze rechten. De Wikimedia Foundation en haar chapters (waaronder de Vereniging Wikimedia Nederland) zijn op geen enkele wijze aansprakelijk voor misbruik van het materiaal of aanspraak op het materiaal door derden. De eventuele geportretteerden hebben geen bezwaar tegen publicatie onder genoemde licenties. Ook uw eventuele opdrachtgever geeft toestemming.",
    'upload.consent' => 'Toestemming',
    'upload.consent_terms' => 'Ja, ik ga akkoord met de bovenstaande licentievoorwaarden, de <a href="{privacy_url}" target="_blank">privacyverklaring</a> en het opslaan van mijn IP-adres.',
    'upload.consent_storage' => 'Ja, ik geef toestemming voor het opslaan van mijn gegevens op de servers van de Wikimedia Foundation in de Verenigde Staten, waarbij de gegevens niet worden doorgegeven op grond van het EU-US Privacy Shield of de wettelijke uitzondering die is opgenomen in artikel 77 van de Wet bescherming persoonsgegevens (Wbp). Bij doorgifte buiten de Europese Unie bestaat er een kans dat het beschermingsniveau minder hoog zal zijn dan binnen de EU.',
    'upload.submit' => 'Upload foto',
    'upload.same_person_title' => 'U hebt aangegeven dat de persoon op de foto dezelfde persoon is als de auteursrechthebbende',
    'upload.same_person_text' => 'Let erop dat alleen de fotograaf toestemming kan geven om de foto vrij te geven onder een vrije licentie.',

    // E-mail verification
    'verify.not_found' => 'Geen record gevonden!',
    'verify.already_verified' => 'Uw email is al geverifieerd',
    'verify.missing_params' => 'Geen sleutel of e-mailadres opgegeven!',
    'verify.invalid_key' => 'Ongeldige sleutel!',
    'verify.invalid_email' => 'Ongeldig e-mailadres!',
    'verify.done_title' => 'Email geverifieerd, Bedankt!',

    // Track submission
    'track.not_found' => 'Geen afbeelding gevonden!',
    'track.title' => 'Inzending volgen: {title}',
    'track.copyright_holder' => 'Auteursrechthebbende:',
    'track.uploaded_by' => 'Geupload door:',
    'track.status' => 'Status',
    'track.status_done' => 'Afgehandeld',
    'track.status_in_progress' => 'In behandeling',
    'track.status_queue' => 'In de wachtrij',
    'track.status_unknown' => 'Status onbekend',
    'track.view' => 'Bekijk',

    // 404
    'notfound.title' => 'Oh nee, deze pagina bestaat niet!',
    'notfound.text1' => 'Helaas, u bent terecht gekomen op een deel van Wikiportret dat eigenlijk niet bestaat. Het kan ook zijn dat de pagina wel bestaat, maar op dit moment niet beschikbaar is.',
    'notfound.text2' => 'Controleer of u een correcte URL in uw adresbalk heeft staan of keer terug naar de vorige pagina. Pak anders een stroopwafel en probeer het later opnieuw.',

    // Privacy statement (the text itself is in privacy.php)
    'privacy.title' => 'Privacyverklaring',

    // Login
    'login.title' => 'Inloggen',
    'login.intro' => 'Hier kunnen medewerkers van Wikiportret inloggen in het beheergedeelte.',
    'login.button' => 'Inloggen',
    'login.wrong' => 'Gebruikersnaam of wachtwoord is verkeerd',

    // Admin: tabs
    'admin.tab_users' => 'Gebruikers',
    'admin.tab_messages' => 'Berichten',
    'admin.tab_database' => 'Database',

    // Admin: users
    'user.manage_title' => 'Gebruikersbeheer',
    'user.new' => 'Nieuwe gebruiker',
    'user.add_title' => 'Gebruiker toevoegen',
    'user.edit_title' => 'Gebruiker bewerken',
    'user.not_found' => 'Gebruiker niet gevonden!',
    'user.username' => 'Gebruikersnaam',
    'user.vrts_name' => 'VRTS-naam',
    'user.password' => 'Wachtwoord',
    'user.password_again' => 'Wachtwoord nogmaals',
    'user.password_placeholder' => 'Enkel invullen als u het wachtwoord wilt wijzigen',
    'user.email' => 'E-mailadres',
    'user.admin' => 'Beheerder',
    'user.bot' => 'Bot',
    'user.api_key' => 'Api key',
    'user.activated' => 'Geactiveerd',
    'user.active' => 'Actief',

    // Admin: messages
    'message.manage_title' => 'Berichtenbeheer',
    'message.new' => 'Nieuw bericht',
    'message.add_title' => 'Bericht toevoegen',
    'message.edit_title' => 'Bericht bewerken',

    // Admin: verify e-mail address (JSON responses)
    'admin_verify.invalid_id' => 'Geen geldige id',
    'admin_verify.invalid_hash' => 'Geen geldige hash',
    'admin_verify.cannot_approve' => 'Kan mailadres niet goedkeuren.',
    'admin_verify.verified' => 'E-mailadres is geverifieerd en bevestigingsmail is verstuurd.',
    'admin_verify.send_failed' => 'Verificatiemail kon niet worden verstuurd.',
    'admin_verify.resent' => 'Verificatiemail is opnieuw verstuurd.',
    'admin_verify.already_verified' => 'Mailadres is al geverifieerd of onbekend.',
    'admin_verify.unverified' => 'Afbeelding is teruggezet naar Niet geverifieerd.',

    // Admin: database schema
    'schema.file_not_found' => 'etc/scheme.sql is niet gevonden. Zet de map etc naast de webroot, of stel SCHEMA_FILE in config.php in.',
    'schema.compared_with' => 'Vergeleken met {file}.',
    'schema.in_sync' => 'De database komt overeen met scheme.sql.',
    'schema.col_table' => 'Tabel',
    'schema.col_difference' => 'Verschil',
    'schema.apply' => 'Doorvoeren',
    'schema.apply_selected' => 'Geselecteerde wijzigingen doorvoeren',
    'schema.removes' => 'Verwijdert dit uit de database',
    'schema.now' => 'Nu:',
    'schema.applied' => 'Uitgevoerd',
    'schema.table_missing' => 'Tabel ontbreekt',
    'schema.table_extra' => 'Tabel staat niet in scheme.sql (wordt niet verwijderd)',
    'schema.column_missing' => 'Kolom `{column}` ontbreekt',
    'schema.column_differs' => 'Kolom `{column}` wijkt af',
    'schema.column_extra' => 'Kolom `{column}` staat niet in scheme.sql',
    'schema.key_missing' => '{key} ontbreekt',
    'schema.key_differs' => '{key} wijkt af',
    'schema.key_extra' => '{key} staat niet in scheme.sql',

    // Images: overview, search and tabs
    'images.submissions' => 'Inzendingen',
    'images.archive' => 'Archief',
    'images.unverified' => 'Niet geverifieerd',
    'images.col_photo' => 'Foto',
    'images.col_uploader' => 'Uploader',
    'images.col_date' => 'Datum',
    'images.col_owner' => 'Eigenaar',
    'images.unassigned' => 'Aan niemand toegewezen',
    'search.title' => 'Zoeken',
    'search.term' => 'Zoekterm',
    'search.button' => 'Zoeken',
    'search.no_term' => 'Er is geen zoekterm ingevoerd',
    'search.no_results' => 'Er zijn geen resultaten gevonden.',
    'search.placeholder' => 'Key, e-mailadres, uploader of titel',
    'search.header_placeholder' => 'Zoek op key, e-mail, uploader of titel',
    'search.col_email' => 'E-mail',
    'search.col_status' => 'Status',
    'search.status_submission' => 'Inzending',
    'search.status_unknown' => 'Onbekend',

    // Images: bulk actions
    'bulk.select_all' => 'Alles selecteren',
    'bulk.archive' => 'Archiveren',
    'bulk.archived' => '{count} afbeeldingen gearchiveerd.',
    'bulk.address_verified' => 'Mailadres is geverifieerd',
    'bulk.none_selected' => 'Geen afbeeldingen geselecteerd',
    'bulk.processed' => '{done} van {total} afbeeldingen verwerkt.',
    'bulk.failed' => 'Mislukt: {ids}.',

    // Images: canned message
    'canned.title' => 'Kant-en-klaar berichtje versturen',
    'canned.not_found' => 'Bericht of afbeelding niet gevonden!',

    // Images: single submission
    'single.not_found' => 'Foto niet gevonden!',
    'single.updated' => 'Afbeelding is bijgewerkt. <a href="overview.php">Terug naar overzicht</a>',
    'single.title' => 'Ingestuurde foto: {title}',
    'single.copyright_holder' => 'Rechthebbende:',
    'single.uploaded_by' => 'Geüpload door:',
    'single.email' => 'E-mailadres:',
    'single.not_verified' => 'Niet geverifieerd',
    'single.verify_address' => 'Verifieer adres',
    'single.resend_verification' => 'Stuur verificatiemail opnieuw',
    'single.unverify' => 'Terugzetten naar Niet geverifieerd',
    'single.resend_short' => 'Stuur mail opnieuw',
    'single.busy' => 'Bezig...',
    'single.verified' => 'Geverifieerd',
    'single.ip' => 'IP-adres:',
    'single.uploaded_on' => 'Geüpload op:',
    'single.key' => 'Key:',
    'single.what_to_do' => 'Wat doen we ermee?',
    'single.google_search' => 'Zoek naar deze afbeelding bij Google',
    'single.upload_commons' => 'Uploaden naar Commons!',
    'single.options' => 'Opties',
    'single.owner' => 'Eigenaar',
    'single.done' => 'Afgehandeld',
    'single.verify_first' => 'Nee, eerst mailadres verifiëren',
    'single.ticket' => 'VRTS-ticket:',
    'single.categories' => 'Categorieën:',
    'single.get_categories' => 'Krijg categorieën',
    'single.category_missing' => 'Categorie bestaat niet',
    'single.claim' => 'Ik neem hem',

    // Google Vision analysis
    'gv.title' => 'Google Vision Analysis',
    'gv.too_large' => 'Geen analyse (Bestand is groter dan 10MB)',
    'gv.not_activated' => 'Niet geactiveerd',
    'gv.no_results' => 'Geen resultaten',
    'gv.analyse_again' => 'Nog een keer analyseren',
    'gv.date' => 'Datum analyse:',
    'gv.best_label' => 'Beste categorie:',
    'gv.description' => 'Beschrijving:',
    'gv.matching_pages' => 'Aantal webpaginas met dezelfde afbeelding',
    'gv.similar_img' => 'Aantal vergelijkbare afbeeldingen',
    'gv.partial_img' => 'Aantal gedeeltelijke afbeeldingen',
    'gv.matching_img' => 'Aantal exacte afbeeldingen',

    // API
    'api.method_not_allowed' => 'Methode niet toegestaan',
    'api.invalid_credentials' => 'U dient correcte inloggegevens te verstrekken',
    'api.image_not_found' => 'Afbeelding niet gevonden.',
    'api.ticket_placeholder' => 'VUL_HIER_HET_TICKETNUMMER_IN',

    // Command line scripts
    'cli.only' => 'Dit script kan alleen via de command line uitgevoerd worden.',
    'cleanup.start' => 'Start cleanup verlopen bot-tokens...',
    'cleanup.done' => 'Klaar: {deleted} verlopen token(s) verwijderd in {elapsed}s.',
    'cleanup.error' => 'FOUT: {message}',

    // JavaScript
    'js.confirm_delete_message' => 'Weet u zeker dat u dit bericht wilt verwijderen?',
    'js.show' => 'tonen',
    'js.hide' => 'verbergen',
    'js.close' => 'Sluiten',
    'js.error_generic' => 'Er is iets misgegaan, probeer het opnieuw.',
    'js.bulk_selected' => '{count} geselecteerd',
    'js.bulk_confirm' => '{action} voor {count} afbeelding(en)?',
    'js.schema_confirm' => 'Geselecteerde wijzigingen doorvoeren in de database?',
];
