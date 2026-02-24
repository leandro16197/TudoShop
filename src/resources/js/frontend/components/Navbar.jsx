import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from "react-router-dom";
import { useCart } from '../context/CartContext.jsx'; 

export default function Navbar() {

    const { cart, cartCount, cartTotal, removeFromCart, updateQuantity } = useCart();
    
    const [query, setQuery] = useState('');
    const [results, setResults] = useState([]); 
    const [loading, setLoading] = useState(false);
    const [user, setUser] = useState(null);
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const [cartOpen, setCartOpen] = useState(false);
    
    const navigate = useNavigate();
    const searchRef = useRef(null);
    const dropdownRef = useRef(null);
    const cartRef = useRef(null); 

    const checkUser = () => {
        const savedUser = sessionStorage.getItem("cliente");
        setUser(savedUser ? JSON.parse(savedUser) : null);
    };

    useEffect(() => {
        checkUser();
        window.addEventListener("authChange", checkUser);
        return () => window.removeEventListener("authChange", checkUser);
    }, []);

 
    useEffect(() => {
        const delayDebounceFn = setTimeout(() => {
            if (query.trim().length > 2) {
                setLoading(true);
                fetch(`/api/frontend/v1/productos?search=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        setResults(data);
                        setLoading(false);
                    })
                    .catch(err => {
                        console.error("Error en búsqueda:", err);
                        setLoading(false);
                    });
            } else {
                setResults([]);
            }
        }, 300);
        return () => clearTimeout(delayDebounceFn);
    }, [query]);

    useEffect(() => {
        function handleClickOutside(event) {
            if (searchRef.current && !searchRef.current.contains(event.target)) setResults([]);
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) setDropdownOpen(false);
            if (cartRef.current && !cartRef.current.contains(event.target)) setCartOpen(false);
        }
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const handleLogout = () => {
        sessionStorage.removeItem("token");
        sessionStorage.removeItem("cliente");
        setUser(null);
        setDropdownOpen(false);
        window.dispatchEvent(new Event("authChange"));
        navigate("/");
    };

    return (
        <nav className="navbar">
            <div className="navbar-left">
                <Link to="/" className="navbar-brand">
                    <img src="/images/logoShopTudo.png" alt="ShopTudo Logo" className="navbar-logo" />
                </Link>
            </div>

    
            <div className="navbar-center search-wrapper" ref={searchRef}>
                <input
                    type="text"
                    placeholder="Buscar productos..."
                    className="search-input"
                    value={query}
                    onChange={e => setQuery(e.target.value)}
                />
                {(results.length > 0 || loading) && (
                    <div className="search-results-dropdown">
                        {loading ? (
                            <div className="search-loading">Buscando productos...</div>
                        ) : (
                            results.map(prod => (
                                <Link 
                                    key={prod.id} 
                                    to={`/productos/${prod.id}`} 
                                    className="search-result-item"
                                    onClick={() => { setResults([]); setQuery(''); }}
                                >
                                    <div className="res-image-container">
                                        <img src={prod.imagen} alt={prod.nombre} />
                                    </div>
                                    <div className="res-info">
                                        <p className="res-name">{prod.nombre}</p>
                                        <p className="res-price">${prod.precio}</p>
                                    </div>
                                </Link>
                            ))
                        )}
                    </div>
                )}
            </div>

            <div className="navbar-right">
       
                <div className="cart-dropdown-wrapper" ref={cartRef}>
                    <button className="btn-cart-trigger" onClick={() => setCartOpen(!cartOpen)}>
                        <i className="bi bi-cart3"></i>
                    
                        {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
                    </button>

                    {cartOpen && (
                        <div className="cart-dropdown-menu">
                            <h5 className="p-3 border-bottom m-0">Mi Carrito</h5>
                            <div className="cart-items-container">
                                {cart.length === 0 ? (
                                    <div className="p-4 text-center text-muted">El carrito está vacío</div>
                                ) : (
                                    cart.map(item => (
                                        <div key={item.id} className="cart-item-mini">
                                            <div className="cart-img-container">
                                                <img src={item.imagen} alt={item.nombre} />
                                            </div>
                                            
                                            <div className="item-details">
                                                <p className="item-name">{item.nombre}</p>
                                                <div className="item-controls-row">
                                                    <div className="quantity-selector-pill">
                                                        <button 
                                                            type="button"
                                                            onClick={(e) => {
                                                                e.preventDefault();
                                                                e.stopPropagation();
                                                                updateQuantity(item.id, item.cantidad - 1);
                                                            }}
                                                        >
                                                            -
                                                        </button>
                                                                                                                
                                                        <span>{item.cantidad}</span>
                                                        
                                                        <button 
                                                            type="button"
                                                            onClick={(e) => {
                                                                e.preventDefault();
                                                                updateQuantity(item.id, item.cantidad + 1);
                                                            }}
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                    <p className="item-price-subtotal">
                                                        ${(item.precio * item.cantidad).toLocaleString()}
                                                    </p>
                                                </div>
                                            </div>

                                            <button className="btn-remove-item" onClick={() => removeFromCart(item.id)} title="Eliminar">
                                                <i className="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    ))
                                )}
                            </div>
                            {cart.length > 0 && (
                                <div className="cart-footer p-3 border-top">
                                    <div className="d-flex justify-content-between mb-3 px-1">
                                        <span className="text-muted">Subtotal:</span>
                                        <strong className="total-price">${cartTotal}</strong>
                                    </div>
                                    <div className="d-flex justify-content-center">
                                        <Link 
                                            to="/checkout" 
                                            className="btn btn-primary btn-realizar-pago" 
                                            onClick={() => setCartOpen(false)}
                                        >
                                            Realizar Pago
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>
                    )}
                </div>

    
                {user ? (
                    <div className="user-dropdown-wrapper" ref={dropdownRef}>
                        <div className="user-nav-container trigger" onClick={() => setDropdownOpen(!dropdownOpen)}>
                            <div className="user-info">
                                <span>Hola, <strong>{user.nombre.split(' ')[0]}</strong></span>
                            </div>
                            <span className={`arrow-icon ${dropdownOpen ? 'open' : ''}`}>▾</span>
                        </div>
                        {dropdownOpen && (
                            <div className="user-dropdown-menu">
                                <Link to="/perfil" className="dropdown-item" onClick={() => setDropdownOpen(false)}>👤 Mi Perfil</Link>
                                <Link to="/pedidos" className="dropdown-item" onClick={() => setDropdownOpen(false)}>📦 Mis Pedidos</Link>
                                <hr className="dropdown-divider" />
                                <button onClick={handleLogout} className="dropdown-item logout-item">🚪 Cerrar Sesión</button>
                            </div>
                        )}
                    </div>
                ) : (
                    <Link to="/login" className="btn-login-shoptudo">Iniciar Sesión</Link>
                )}
            </div>
        </nav>
    );
}