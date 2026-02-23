import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from "react-router-dom";
import ProductCard from './ProductCard';

export default function Navbar() {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const [hasSearched, setHasSearched] = useState(false);
    
    const [user, setUser] = useState(null);
    const [dropdownOpen, setDropdownOpen] = useState(false);
    
    const navigate = useNavigate();
    const searchRef = useRef(null);
    const dropdownRef = useRef(null);

    const checkUser = () => {
        const savedUser = localStorage.getItem("cliente");
        if (savedUser) {
            setUser(JSON.parse(savedUser));
        } else {
            setUser(null);
        }
    };
    useEffect(() => {
        checkUser();
        window.addEventListener("authChange", checkUser);
        window.addEventListener("storage", checkUser);

        return () => {
            window.removeEventListener("authChange", checkUser);
            window.removeEventListener("storage", checkUser);
        };
    }, []);

    const handleLogout = () => {
        localStorage.removeItem("token");
        localStorage.removeItem("cliente");
        setUser(null);
        setDropdownOpen(false);
        window.dispatchEvent(new Event("authChange"));
        navigate("/");
    };


    useEffect(() => {
        if (!query.trim()) {
            setResults([]);
            setLoading(false);
            setHasSearched(false);
            return;
        }

        const timeout = setTimeout(() => {
            setLoading(true);
            setHasSearched(false);

            fetch(`api/frontend/v1/productos?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => setResults(data))
                .catch(() => setResults([]))
                .finally(() => {
                    setLoading(false);
                    setHasSearched(true);
                });
        }, 300);

        return () => clearTimeout(timeout);
    }, [query]);
    useEffect(() => {
        function handleClickOutside(event) {
            if (searchRef.current && !searchRef.current.contains(event.target)) {
                setResults([]);
                setQuery('');
                setHasSearched(false);
            }
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setDropdownOpen(false);
            }
        }
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

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
                    value={query}
                    onChange={e => setQuery(e.target.value)}
                />

                {query.trim() && (
                    <div className="results-wrapper">
                        <div className="product-list-container">
                            {loading && (
                                <>
                                    {[...Array(3)].map((_, i) => (
                                        <ProductCard key={i} loading />
                                    ))}
                                </>
                            )}

                            {!loading && hasSearched && results.length === 0 && (
                                <div className="empty-search">
                                    No se encontraron productos
                                </div>
                            )}

                            {!loading && results.map(product => (
                                <div key={product.id} onClick={() => setQuery('')}>
                                    <ProductCard product={product} />
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>

            <div className="navbar-right">
                {user ? (
                    <div className="user-dropdown-wrapper" ref={dropdownRef}>
                        <div 
                            className="user-nav-container trigger" 
                            onClick={() => setDropdownOpen(!dropdownOpen)}
                        >
                            <div className="user-info">
                                <span>Hola, <strong>{user.nombre.split(' ')[0]}</strong></span>
                            </div>
                            <span className={`arrow-icon ${dropdownOpen ? 'open' : ''}`}>▾</span>
                        </div>

                        {dropdownOpen && (
                            <div className="user-dropdown-menu">
                                <Link to="/perfil" className="dropdown-item" onClick={() => setDropdownOpen(false)}>
                                    👤 Mi Perfil
                                </Link>
                                <Link to="/pedidos" className="dropdown-item" onClick={() => setDropdownOpen(false)}>
                                    📦 Mis Pedidos
                                </Link>
                                <hr className="dropdown-divider" />
                                <button onClick={handleLogout} className="dropdown-item logout-item">
                                    🚪 Cerrar Sesión
                                </button>
                            </div>
                        )}
                    </div>
                ) : (
                    <Link to="/login" className="btn-login-shoptudo">
                        Iniciar Sesión
                    </Link>
                )}
            </div>
        </nav>
    );
}