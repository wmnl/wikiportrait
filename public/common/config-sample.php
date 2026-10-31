<?php

const DB_HOST = 'localhost';
const DB_USER = 'db_user';
const DB_PASS = 'db_pass';
const DB_DB = 'wikiportret';
const BASE_URL = 'http://domain.tld';
const DEBUG = false;
// Querylog voor debuggen (bevat persoonsgegevens), bij voorkeur buiten de webroot
// const DB_LOGFILE = '/var/log/wikiportret/db_logfile.txt';
define('ABSPATH', str_replace("/common", "", dirname(__FILE__)));
const VRTS_MAIL = 'wikiportret@wikimedia.org'; // VRTS-ticketsysteem
const SENDER_MAIL = 'noreply@domain.tld'; // afzender van de mails, bij Gmail gelijk aan SMTP_USER of een 'Verzenden als'-adres
const V1_PATH = BASE_URL . '/old';
// Language of the interface and the mails, see /lang. Falls back to Dutch ('nl').
const LANGUAGE = 'nl';
define('GOOGLE_CREDENTIALS', dirname(__FILE__) . '/your_credentials_file.json');
const TIMEZONE = DateTimeZone::EUROPE;
const SECRET_KEY = 'secret_key';
const DOMAIN = 'domain.tld';
// SMTP (optioneel). Zolang SMTP_USER of SMTP_PASS leeg is wordt sendmail gebruikt.
// Gmail: smtp.gmail.com, poort 587, gebruiker = volledig Gmail-adres, wachtwoord = app-wachtwoord.
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USER = '';
const SMTP_PASS = '';
