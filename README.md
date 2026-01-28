# URL Shortener 🔗

A modern URL shortening service with QR code generation and comprehensive analytics, built with Laravel 12.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.3-blue)
![Chart.js](https://img.shields.io/badge/Chart.js-4.x-orange)
![QR Code](https://img.shields.io/badge/QR_Code-Enabled-green)

## 🚀 Features

- 🔗 **URL Shortening** - Convert long URLs into short, shareable links
- 🎨 **Custom Short Codes** - Choose your own memorable codes or use auto-generated ones
- 📱 **QR Code Generation** - Automatic QR code for each shortened URL
- 📊 **Analytics Dashboard** - Track clicks, browsers, platforms, and traffic sources
- 📈 **Visual Reports** - Charts showing clicks over time and browser distribution
- ⏰ **Link Expiration** - Set expiration dates for time-sensitive links
- 🔄 **Active/Inactive Toggle** - Enable or disable links without deleting
- 🔐 **User Authentication** - Secure user accounts with Laravel Breeze
- 📋 **Click History** - View detailed logs of all clicks with timestamps
- 🎯 **Responsive Design** - Beautiful UI with Tailwind CSS

## 🛠️ Tech Stack

- **Framework:** Laravel 12
- **Authentication:** Laravel Breeze
- **Database:** MySQL
- **Frontend:** Blade Templates + Tailwind CSS
- **Charts:** Chart.js
- **QR Codes:** SimpleSoftwareIO/simple-qrcode
- **PHP Version:** 8.3+

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js & NPM

### Setup Instructions

1. **Clone the repository**
```bash
   git clone https://github.com/papilamurie/url-shortener.git
   cd url-shortener
```

2. **Install dependencies**
```bash
   composer install
   npm install
```

3. **Environment setup**
```bash
   cp .env.example .env
   php artisan key:generate
```

4. **Configure database** (Edit `.env`)
```env
   DB_DATABASE=url_shortener
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
```

5. **Run migrations**
```bash
   php artisan migrate
```

6. **Build assets**
```bash
   npm run build
```

7. **Start the server**
```bash
   php artisan serve
```

Visit: http://localhost:8000



## 🎯 Usage

### Creating a Short URL

1. **Register/Login** to your account
2. Click **"Create Short URL"**
3. Enter your long URL
4. (Optional) Add a custom short code
5. (Optional) Add a title for organization
6. (Optional) Set an expiration date
7. Click **"Create"**

### Sharing Your Link

- **Copy** the short URL
- **Download** the QR code
- **Share** via social media, email, or messaging

### Tracking Analytics

- View **total clicks**
- See **clicks over time** (30-day chart)
- Analyze **browser distribution**
- Check **platform breakdown** (Windows, Mac, Mobile)
- Review **recent click history**

## 📁 Project Structure
```
url-shortener/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── UrlController.php
│   │   └── RedirectController.php
│   └── Models/
│       ├── Url.php
│       └── Click.php
├── database/
│   └── migrations/
│       ├── create_urls_table.php
│       └── create_clicks_table.php
└── resources/views/
    ├── dashboard.blade.php
    └── urls/
        ├── index.blade.php
        ├── create.blade.php
        ├── show.blade.php
        └── edit.blade.php
```

## 🔐 Security Features

- **User Authentication** - Required for creating and managing URLs
- **Authorization** - Users can only access their own URLs
- **CSRF Protection** - All forms protected
- **SQL Injection Prevention** - Eloquent ORM
- **Unique Constraints** - Prevents duplicate short codes
- **Password Hashing** - bcrypt encryption

## 📊 Analytics Tracked

- **Total Clicks** - Lifetime click count
- **Click Timestamps** - When each click occurred
- **Browser Detection** - Chrome, Firefox, Safari, etc.
- **Platform Detection** - Windows, Mac, Linux, iOS, Android
- **Referrer Tracking** - Where traffic came from
- **IP Addresses** - Visitor IPs (for security)

## 🚧 Future Enhancements

- [ ] Bulk URL shortening
- [ ] Link preview before redirect
- [ ] Geographic location tracking
- [ ] API for programmatic access
- [ ] Custom domains
- [ ] Link password protection
- [ ] CSV export of analytics
- [ ] Team collaboration features

## 📄 License

Open-source software licensed under the [MIT license](LICENSE).

## 👤 Author

**Your Name**
- GitHub: [@papilamurie](https://github.com/papilamurie)
- Portfolio: [Your Portfolio URL]

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Chart.js](https://www.chartjs.org) - Data Visualization
- [SimpleSoftwareIO/simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode) - QR Code Generation

---

⭐ If you found this project helpful, please give it a star!

## 🔗 Live Demo

Try it out: [Your Deployed URL]

## 📧 Contact

For questions or feedback, open an issue or contact me at your.email@example.com
