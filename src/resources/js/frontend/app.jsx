import React, { useState } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

import Navbar from './components/Navbar';
import ProductCard from './components/ProductCard';
import HeroBanner from './components/HeroBanner';
import ProductDetail from './pages/ProductDetail';

import '../../css/app.css';

function Home() {
    const [results, setResults] = useState([]);

    return (
        <>
            <Navbar onResults={setResults} />

            {results.length > 0 && (
                <div className="results-wrapper">
                    <div className="product-list-container">
                        {results.map(product => (
                            <ProductCard
                                key={product.id}
                                product={product}
                            />
                        ))}
                    </div>
                </div>
            )}

            <HeroBanner />
        </>
    );
}

export default function App() {
    const [results, setResults] = useState([]);

    return (
        <BrowserRouter>
            <Navbar onResults={setResults} />

            <main className="app-content">
                <Routes>
                    <Route
                        path="/home"
                        element={
                            <>
                                {results.length > 0 && (
                                    <div className="results-wrapper">
                                        <div className="product-list-container">
                                            {results.map(product => (
                                                <ProductCard
                                                    key={product.id}
                                                    product={product}
                                                />
                                            ))}
                                        </div>
                                    </div>
                                )}

                                <HeroBanner />
                            </>
                        }
                    />

                    <Route
                        path="/productos/:id"
                        element={<ProductDetail />}
                    />
                </Routes>
            </main>
        </BrowserRouter>
    );
}

