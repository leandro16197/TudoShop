import React, { useState, useEffect, useRef } from 'react';
import { Link } from "react-router-dom";
import ProductCard from './ProductCard';

export default function Navbar() {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);

    const searchRef = useRef(null); // Ref del contenedor

    useEffect(() => {
        if (!query.trim()) {
            setResults([]);
            setLoading(false);
            return;
        }

        const timeout = setTimeout(() => {
            setLoading(true);

            fetch(`/frontend/v1/productos?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => setResults(data))
                .catch(() => setResults([]))
                .finally(() => setLoading(false));
        }, 300);

        return () => clearTimeout(timeout);
    }, [query]);

    // Detectar click fuera
    useEffect(() => {
        function handleClickOutside(event) {
            if (searchRef.current && !searchRef.current.contains(event.target)) {
                setResults([]);
            }
        }

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [searchRef]);

    return (
        <nav className="navbar">
            <div className="navbar-left">
                <Link to="/" className="navbar-brand">ShopTudo</Link>
            </div>

            <div className="navbar-center search-wrapper" ref={searchRef}>
                <input
                    type="text"
                    placeholder="Buscar productos..."
                    value={query}
                    onChange={e => setQuery(e.target.value)}
                />

                {(results.length > 0 || loading) && (
                    <div className="results-wrapper">
                        <div className="product-list-container">
                            {loading && <ProductCard loading={true} />}
                            {!loading && results.length === 0 && <p>No se encontraron productos</p>}
                            {!loading && results.map(product => (
                                <ProductCard key={product.id} product={product} />
                            ))}
                        </div>
                    </div>
                )}
            </div>

            <div className="navbar-right">
                <button className="btn btn-outline-secondary">Login</button>
            </div>
        </nav>
    );
}
