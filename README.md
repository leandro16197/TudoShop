# 🛒 ShopTudo - E-commerce de Librería

ShopTudo es una plataforma de comercio electrónico desarrollada para la gestión y venta de artículos de librería.  
El sistema cuenta con un panel de administración completo para el control de inventario y una experiencia de usuario optimizada, incluyendo integración de pagos reales.

---

## 🚀 Características principales

### 📦 Gestión de Inventario (CRUD)
Control completo de productos, categorías y marcas, con soporte para múltiples imágenes por producto.

### 🖥️ Panel Administrativo (Dark Mode)
Interfaz moderna desarrollada con Bootstrap y jQuery, con búsqueda dinámica y filtrado avanzado mediante DataTables.

### 💳 Integración con Mercado Pago
Procesamiento de pagos seguro con actualización automática de estados mediante webhooks.

### ⚙️ Automatización de Backend
Comandos personalizados de Artisan para mantenimiento del sistema y limpieza de archivos de exportación.

### 📩 Notificaciones Inteligentes
Envío automático de correos electrónicos para pagos aprobados y notificaciones de pedidos rechazados.

### 👤 Área de Usuario
Gestión de perfil, historial de pedidos, carrito de compras y lista de favoritos (wishlist).

---

## 🛠️ Stack Tecnológico

**Backend:**  
- PHP 8.x  
- Laravel 10/11 (Eloquent ORM, Blade, arquitectura MVC)  

**Frontend:**  
- React (UI del cliente)  
- jQuery (DOM y AJAX en panel administrativo)  

**Estilos:**  
- Bootstrap 5  
- CSS3 personalizado  

**Herramientas & APIs:**  
- Mercado Pago (pasarela de pagos)  
- Ngrok (testing de webhooks en entorno local)  
- Redis (cache y notificaciones en tiempo real)  

---

## 🐳 Entorno de desarrollo

El proyecto fue desarrollado utilizando **Docker** para facilitar la configuración del entorno.

---

## 🌐 Accesos

- 🛒 **Tienda (cliente):** `/`  
- 🛠️ **Panel administrativo:** `/panel`  

---

## ⚛️ Frontend (React)

Los archivos del frontend desarrollado en React se encuentran en: `resources/js/frontend/` 
---

## 📦 Instalación rápida

```bash
git clone https://github.com/leandro16197/ShopTudo
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
