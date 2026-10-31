<?php

const DB_HOST = 'localhost';
const DB_USER = 'db_user';
const DB_PASS = 'db_pass';
const DB_DB = 'wikiportret';
const BASE_URL = 'http://domain.tld';
const DEBUG = false;
define('ABSPATH', str_replace("/common", "", dirname(__FILE__)));
// Querylog voor debuggen (bevat persoonsgegevens), bij voorkeur buiten de webroot
// define('DB_LOGFILE', dirname(ABSPATH) . '/private/db_logfile.txt');
const VRTS_MAIL = 'wikiportret@wikimedia.org'; // VRTS-ticketsysteem
const SENDER_MAIL = 'noreply@domain.tld'; // afzender van de mails, bij Gmail gelijk aan SMTP_USER of een 'Verzenden als'-adres
const V1_PATH = BASE_URL . '/old';
// Language of the interface and the mails, see /lang. Falls back to Dutch ('nl').
const LANGUAGE = 'nl';
// Bestanden buiten de webroot (standaard de map private/ naast public/), zie README.md
// define('PRIVATE_DIR', '/pad/naar/private');
// define('VENDOR_DIR', '/pad/naar/private/vendor');
// define('CRON_LOGFILE', '/pad/naar/private/cronlog.txt');
define('GOOGLE_CREDENTIALS', dirname(ABSPATH) . '/private/your_credentials_file.json');
const TIMEZONE = DateTimeZone::EUROPE;
const SECRET_KEY = 'secret_key';
const DOMAIN = 'domain.tld';
// SMTP (optioneel). Zolang SMTP_USER of SMTP_PASS leeg is wordt sendmail gebruikt.
// Gmail: smtp.gmail.com, poort 587, gebruiker = volledig Gmail-adres, wachtwoord = app-wachtwoord.
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USER = '';
const SMTP_PASS = '';
