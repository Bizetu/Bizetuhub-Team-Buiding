# Bizetu Hub

A Laravel and Twill CMS platform for team-building services, events, enquiries and booking requests.

## Requirements

- PHP 8.5 (`^8.5` in Composer)
- Composer
- Node.js and npm
- MySQL with the PDO MySQL PHP extension

## Local setup

```sh
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

The project defaults to MySQL database `bizetuteam` on `127.0.0.1:3306` with user `root` and a blank password. Make sure the MySQL server is running and the database exists before migrating. Update the `.env` credentials if your local MySQL account uses a password. Booking and enquiry notifications are queued to `SITE_NOTIFICATION_EMAIL`; set that address, configure SMTP in `.env`, and run a queue worker (`php artisan queue:work`) for delivery. If the recipient is unset, the request is still saved and a warning is written to the application log. The company name, contact information and sample services/events are starter content and should be replaced with approved business details.

## Administration

The Twill admin is mounted at `/admin`. Available modules:

- Pages: use the Website page selector to manage the Homepage and fixed-page sections with ordered content, image, card-grid, FAQ-list and call-to-action blocks. Add cards and FAQ items within their repeater-style nested block editors. Pages without blocks keep their original layout; adding blocks switches that page to the block layout. Contact and booking pages keep their functional forms when blocks are added
- Homepage sliders: add, publish, reorder and image slides shown in the homepage banner; manage the remaining homepage sections from the Homepage entry in Pages
- Services and events: manage public programme and event listings; quick-create drafts may start with only a title and permalink. Services support an uploaded card/detail image, badge, typical duration and group size. Add a service excerpt before publishing a service, and an event description, venue and start date before publishing an event. Published services marked “Feature on the homepage” appear in the homepage practical-programmes section
- Website menu: manage published header and footer links, including mobile navigation
- Gallery: upload and publish accessible, captioned images
- Bookings: review requests and update status (`New`, `Under Review`, `Confirmed`, `Completed`, `Cancelled`) or add internal notes
- Enquiries: review contact requests and add internal notes
- Testimonials: manage ratings and homepage features
- Insights: publish articles and resources
- Site settings: change the public company/contact details and social links

The media library uses Twill's local image service by default so uploads work on PHP installations without GD. This serves original images without Glide transformations. To enable resizing and crop transformations, install/enable PHP GD and set `MEDIA_LIBRARY_IMAGE_SERVICE="A17\\Twill\\Services\\MediaLibrary\\Glide"`.

On an existing installation, run `php artisan db:seed --class=PageContentSeeder` once to add the fixed-page entries to the Pages module.

The visible Twill module list is configured in `config/twill-navigation.php`. `config/twill.php` is the project-level location for Twill configuration overrides; package defaults are used for values not overridden there.

After migrating, create the first administrator interactively with `php artisan twill:superadmin`. Do not seed a default administrator account or deploy with starter credentials.

## Public routes

- `/` — homepage
- `/services` and `/services/{slug}` — programme catalogue and details
- `/events` and `/events/{slug}` — upcoming/past events and event registration
- `/industries` — programmes by industry
- `/gallery` — published session photos
- `/packages` — corporate package formats and proposals
- `/testimonials` — client stories
- `/faqs` — booking and programme questions
- `/book` — private session and public-event booking requests
- `/about` — company story
- `/contact` — enquiry form
- `/insights` and `/insights/{slug}` — articles and resources
- `/pages/{slug}` — published CMS pages
- `/privacy` and `/terms` — policy pages (starter copy requires legal review)

Booking requests are stored as **New** and are not confirmed until an administrator changes their status. Contact and booking submissions are rate-limited.

## Checks

```sh
php artisan test
npm run build
```
