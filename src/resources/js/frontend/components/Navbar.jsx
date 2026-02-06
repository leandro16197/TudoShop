import React, { useState, useEffect } from 'react';

export default function Navbar({ onResults }) {
    const [query, setQuery] = useState('');
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (!query.trim()) {
            onResults([]);
            return;
        }

        const timeout = setTimeout(() => {
            setLoading(true);
            fetch(`/frontend/v1/productos?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => onResults(data))
                .finally(() => setLoading(false));
        }, 300);

        return () => clearTimeout(timeout);
    }, [query]);

    return (
        <nav className="navbar">
            <div className="navbar-left">
                <a href="/home" className="navbar-brand">
                    ShopTudo
                </a>
            </div>

            <div className="navbar-center search-wrapper">
                <input
                    type="text"
                    placeholder="Buscar productos..."
                    value={query}
                    onChange={e => setQuery(e.target.value)}
                />
            </div>

            <div className="navbar-right">
                <button className="btn btn-outline-secondary">Login</button>
            </div>
        </nav>
    );
}
