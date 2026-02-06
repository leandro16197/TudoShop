import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

export default function ProductDetail() {
    const { id } = useParams();

    const [product, setProduct] = useState(null);
    const [activeImage, setActiveImage] = useState(null);

    useEffect(() => {
        fetch(`/frontend/v1/productos/${id}`)
            .then(res => res.json())
            .then(data => {
                setProduct(data);
                setActiveImage(data.images?.[0] ?? null);
            });
    }, [id]);

    if (!product) {
        return <p style={{ padding: '2rem' }}>Cargando producto...</p>;
    }

    return (
        <div className="product-page">
            <div className="product-top">


                <div className="product-gallery">
                    <div className="thumbs">
                        {product.images.map((img, i) => (
                            <img
                                key={i}
                                src={img}
                                alt=""
                                className={`thumb ${activeImage === img ? 'active' : ''}`}
                                onClick={() => setActiveImage(img)}
                            />
                        ))}
                    </div>

                    <div className="main-image">
                        <img
                            src={activeImage}
                            alt={product.name}
                            onError={e => e.target.src = '/images/no-image.webp'}
                        />
                    </div>
                </div>

     
                <div className="product-info">
                    <h1>{product.name}</h1>
                    <p className="price">${product.price}</p>
                    <p className="stock">
                        Stock disponible: <strong>{product.stock}</strong>
                    </p>

                    <div className="actions">
                        <button className="btn-outline">Agregar a lista</button>
                        <button className="btn-primary">Comprar</button>
                    </div>
                </div>
            </div>

            <section className="product-section">
                <h3>Descripción</h3>
                <p>{product.description}</p>
            </section>

            {/* CARACTERÍSTICAS */}
            {product.features && (
                <section className="product-section">
                    <h3>Características</h3>

                    <div className="features">
                        {Object.entries(product.features).map(([key, value]) => (
                            <div key={key} className="feature-row">
                                <span>{key}</span>
                                <strong>{value}</strong>
                            </div>
                        ))}
                    </div>
                </section>
            )}
        </div>
    );
}
