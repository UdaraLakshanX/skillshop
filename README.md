# Skillshop - E-Commerce Web Application 🛍️

> A full-featured, multi-role PHP web application for buying and selling skills and services online.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Usage](#usage)
- [User Roles](#user-roles)
- [Key Features by Role](#key-features-by-role)
- [File Documentation](#file-documentation)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## 🎯 Overview

**Skillshop** is a comprehensive e-commerce platform designed to connect skill buyers with service sellers. The platform facilitates the exchange of skills and services with a robust backend system, secure user authentication, and role-based access control.

Built with PHP, this application provides:
- A seamless marketplace experience
- Separate dashboards for buyers, sellers, and administrators
- Advanced search and filtering capabilities
- Secure transaction management
- User profile management and watchlist functionality

---

## ✨ Features

### Core Features
- ✅ **Multi-Role Authentication System** - Admin, Buyer, and Seller roles with secure login
- ✅ **Dynamic Dashboard** - Role-specific dashboards with relevant data and controls
- ✅ **Product Management** - Create, edit, and manage skill offerings
- ✅ **Advanced Search** - Comprehensive product search with filtering options
- ✅ **User Profiles** - Customizable user profiles with preferences
- ✅ **Watchlist** - Save favorite products for later reference
- ✅ **Invoice Generation** - Automated invoice creation for transactions
- ✅ **Admin Panel** - Complete administrative control over users and products

### Security Features
- Secure user authentication
- Session management
- Role-based access control (RBAC)
- Password protection

### User Experience
- Responsive header and footer components
- Intuitive navigation
- Search functionality (basic and advanced)
- Product viewing and management
- Transaction history

---

## 🛠️ Technology Stack

| Component | Technology |
|-----------|------------|
| **Backend** | PHP 99.3% |
| **Markup** | Hack 0.7% |
| **Database** | (Implied MySQL/Database) |
| **Server** | Apache/PHP-enabled server |
| **Frontend** | HTML, CSS, JavaScript (embedded in PHP) |

---

## 📁 Project Structure

```
skillshop/
├── index.php                          # Main entry point
├── home.php                           # Home page
├── header.php                         # Shared header component
├── footer.php                         # Shared footer component
│
├── Authentication & Admin
│   ├── admin-login.php               # Admin login page
│   ├── admin-dashboard.php           # Admin dashboard
│   ├── admin-users.php               # User management
│   └── admin-products.php            # Product management (admin)
│
├── Buyer Features
│   ├── buyer-dashboard.php           # Buyer dashboard
│   ├── search-products.php           # Basic product search
│   ├── advance-search-products.php   # Advanced search with filters
│   ├── product-view.php              # Product details page
│   ├── watchlist.php                 # Saved products list
│   └── invoice.php                   # Invoice viewing
│
├── Seller Features
│   ├── seller-dashboard.php          # Seller dashboard
│   ├── product-register.php          # Add new products
│   └── product-edit.php              # Edit existing products
│
└── User Management
    └── user-profile.php              # User profile page
```

---

## 🚀 Installation

### Prerequisites
- PHP 7.0 or higher
- MySQL/MariaDB database
- Apache web server with PHP support
- Web browser

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/UdaraLakshanX/skillshop.git
   cd skillshop
   ```

2. **Set up the database**
   - Create a MySQL database for the application
   - Import the database schema (if provided)
   - Configure database credentials in your PHP files

3. **Configure the application**
   - Update database connection parameters in relevant PHP files
   - Ensure proper permissions for file uploads if applicable

4. **Deploy to web server**
   - Copy all files to your web server's document root
   - Example: `/var/www/html/skillshop/` or `C:\xampp\htdocs\skillshop\`

5. **Access the application**
   - Open your browser and navigate to `http://localhost/skillshop/`

---

## 💻 Usage

### For Administrators
1. Navigate to admin login page
2. Enter admin credentials
3. Access user and product management from the admin dashboard

### For Sellers
1. Register or log in as a seller
2. Access seller dashboard
3. Register new products/skills
4. Edit existing product listings
5. Monitor sales from the dashboard

### For Buyers
1. Register or log in as a buyer
2. Browse products using search or advanced filters
3. View product details
4. Add interesting items to watchlist
5. Access buyer dashboard for purchase history
6. View invoices for completed transactions

---

## 👥 User Roles

| Role | Access Level | Key Responsibilities |
|------|-------------|---------------------|
| **Admin** | Full System Access | Manage users, approve products, monitor platform |
| **Seller** | Content Creator | Create/edit products, view sales, manage inventory |
| **Buyer** | End User | Search products, make purchases, manage wishlist |

---

## 📊 Key Features by Role

### Admin Dashboard
- User management interface
- Product approval and management
- Platform statistics and overview
- User account controls

### Seller Dashboard
- Product listings overview
- Sales performance metrics
- Product management tools
- Inventory tracking

### Buyer Dashboard
- Purchase history
- Saved items (watchlist)
- Order tracking
- Profile management

---

## 📄 File Documentation

### Core Pages
| File | Purpose |
|------|---------|
| `index.php` | Application entry point and main routing |
| `home.php` | Landing page and featured products |
| `header.php` | Navigation bar and top-level UI |
| `footer.php` | Footer content and site-wide links |

### Authentication
| File | Purpose |
|------|---------|
| `admin-login.php` | Admin authentication interface |

### Admin Panel (Admin Role)
| File | Purpose |
|------|---------|
| `admin-dashboard.php` | Main admin control center |
| `admin-users.php` | User account management |
| `admin-products.php` | Product approval and management |

### Buyer Features (Buyer Role)
| File | Purpose |
|------|---------|
| `buyer-dashboard.php` | Buyer's main dashboard with statistics |
| `search-products.php` | Basic product search functionality |
| `advance-search-products.php` | Advanced filters and search options |
| `product-view.php` | Detailed product information page |
| `watchlist.php` | Saved/bookmarked products |
| `invoice.php` | Transaction invoice display |

### Seller Features (Seller Role)
| File | Purpose |
|------|---------|
| `seller-dashboard.php` | Seller's main dashboard and analytics |
| `product-register.php` | Add new product listings |
| `product-edit.php` | Modify existing product details |

### User Management
| File | Purpose |
|------|---------|
| `user-profile.php` | User account settings and profile info |

---

## 🔐 Security Considerations

- Implement prepared statements to prevent SQL injection
- Validate all user inputs on both client and server sides
- Use password hashing for user credentials
- Implement CSRF protection tokens
- Sanitize output to prevent XSS attacks
- Implement proper session management
- Use HTTPS for secure communication

---

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is currently unlicensed. Please contact the author for licensing information.

---

## 📧 Contact

**Author:** UdaraLakshanX  
**GitHub:** [@UdaraLakshanX](https://github.com/UdaraLakshanX)  
**Repository:** [skillshop](https://github.com/UdaraLakshanX/skillshop)

---

## 📌 Roadmap

- [ ] Payment gateway integration
- [ ] Real-time notifications
- [ ] Review and rating system
- [ ] Chat/messaging feature
- [ ] Mobile app version
- [ ] API documentation
- [ ] Unit tests
- [ ] Performance optimization

---

## 🙏 Acknowledgments

- Built with PHP
- Designed for the skills marketplace community
- Community contributions welcome

---

**Last Updated:** September 2026  
**Version:** 1.0.0
