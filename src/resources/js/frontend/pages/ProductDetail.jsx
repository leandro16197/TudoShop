import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import FeaturedProductCard from '../components/ProductCard';

export default function ProductDetail() {
    const { id } = useParams();
    const navigate = useNavigate();
    const { cart,addToCart } = useCart();
    const [product, setProduct] = useState(null);
    const [activeImage, setActiveImage] = useState(null);
    const [related, setRelated] = useState([]);
    const [loadingRelated, setLoadingRelated] = useState(false);
    const [quantity, setQuantity] = useState(1);
    const [isFavorite, setIsFavorite] = useState(false);

    useEffect(() => {
        window.scrollTo(0, 0);
        setProduct(null);
        setRelated([]);
        setActiveImage(null);
        setIsFavorite(false);
        const token = sessionStorage.getItem('token');
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        fetch(`/api/frontend/v1/productos/${id}`, { headers })
            .then(res => {
                if (!res.ok) throw new Error('Error cargando producto');
                return res.json();
            })
            .then(data => {
                setProduct(data);
                setActiveImage(data.images?.[0] ?? null);
                setQuantity(1);
                setIsFavorite(!!data.is_favorite);
                
                const categoriaId = data.categorias?.[0]?.id;
                if (categoriaId) {
                    setLoadingRelated(true);
                    fetch(`/api/frontend/v1/productos/categoria/${categoriaId}`, { headers })
                        .then(res => {
                            if (!res.ok) throw new Error('Error en relacionados');
                            return res.json();
                        })
                        .then(rel => {
                            const filtrados = rel.filter(p => p.id !== data.id);
                            setRelated(filtrados);
                        })
                        .catch(err => console.error('Error relacionados:', err))
                        .finally(() => setLoadingRelated(false));
                }
            })
            .catch(err => console.error(err));
    }, [id]);


    const handleAddToCart = async (replace = false) => {
        try {
            await addToCart(product, quantity, replace); 
            
            console.log(replace ? "Cantidad fijada" : "Producto sumado al carrito");
        } catch (error) {
            if (error.message.includes("sesión") || error.message.includes("autenticado")) {
                navigate("/login");
            }
            console.error("Error al agregar:", error.message);
            throw error;
        }
    };


    const toggleFavorite = async (e) => {
        e.stopPropagation();

        const token = sessionStorage.getItem('token'); 
        if (!token) {
            navigate("/login");
            return;
        }

        try {
            const response = await fetch(`/api/frontend/v1/favorito/${id}`, {
                method: 'POST', 
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.status === 401) {
                sessionStorage.removeItem('token')
                navigate("/login");
                return;
            }

            if (response.ok) {
                const data = await response.json();
                setIsFavorite(data.is_favorite); 
            }
        } catch (error) {
            console.error("Error al gestionar favorito:", error);
        }
    };

    if (!product) {
        return (
            <div className="loading-container">
                <div className="spinner"></div>
                <p>Cargando Producto...</p>
            </div>
        );
    }

    return (
        <div className="page-container">
            <div className="product-detail-container">
                <div className="product-page product-page--compact">
                    <div className="product-top product-top--compact">
                        
                        <div className="product-gallery">
                            <div className="thumbs">
                                {product.images?.map((img, i) => (
                                    <img
                                        key={i}
                                        src={img}
                                        alt=""
                                        className={`thumb ${activeImage === img ? 'active' : ''}`}
                                        onClick={() => setActiveImage(img)}
                                    />
                                ))}
                            </div>
                            
                          <div className="main-image main-image--compact" style={{ position: 'relative' }}>
                                <img src={activeImage || '/images/no-image.webp'} alt={product.name} />
                                
                                <button onClick={toggleFavorite}className="fav-btn-container"aria-label={isFavorite ? "Quitar de favoritos" : "Agregar a favoritos"}> 
                                    <i 
                                        className={`bi ${isFavorite ? 'bi-star-fill active' : 'bi-star'} fav-icon-star`}
                                    ></i>
                                </button>
                            </div>
                        </div>

                        <div className="product-info product-info--compact">
                            <h1>{product.name}</h1>
                            <div className="price-container-detail">
                                {product.has_discount && (
                                    <span className="price-original-detail">
                                    ${Number(product.original_price).toLocaleString()}
                                    </span>
                                )}

                                <span className="price-final-detail">
                                    ${Number(product.price).toLocaleString()}
                                </span>
                            </div>
                            
                            <p className="stock-tag">
                                STOCK: {product.stock}
                            </p>

                            <div className="fila-carrito">
                                {product.stock > 0 ? (
                                    <>
                                        <div className="selector-cantidad">
                                            <button type="button" onClick={() => quantity > 1 && setQuantity(quantity - 1)}>
                                                -
                                            </button>

                                            <input type="number" value={quantity} readOnly />

                                            <button type="button" onClick={() => quantity < product.stock && setQuantity(quantity + 1)}>
                                                +
                                            </button>
                                        </div>
            
                                        <button type="button" className="btn-icono-carrito" onClick={() => handleAddToCart(false)} title="Agregar al carrito">
                                            <i className="bi bi-cart3"></i>
                                        </button>
                                    </>
                                ) : (
                                    <span className="out-of-stock-text">Agotado</span>
                                )}
                            </div>

                            <div className="info-extra">
                                <div className="info-item">
                                    <span className="icon">🚚</span>
                                    <div>
                                        <strong>Envíos a todo el país</strong>
                                        <p>Recibí tu compra en 2 a 5 días hábiles.</p>
                                    </div>
                                </div>

                                <div className="info-item">
                                    <span className="icon">🛡</span>
                                    <div>
                                        <strong>Garantía oficial</strong>
                                        <p>12 meses contra defectos de fabricación.</p>
                                    </div>
                                </div>

                                <div className="info-item">
                                    <span className="icon">💳</span>
                                    <div>
                                        <strong>Pagos con Mercado Pago</strong>
                                        <p>Aceptamos tarjetas, débito y transferencias.</p>
                                    </div>
                                </div>
                            </div>
                            <button 
                                className="details-btn"
                                onClick={async () => {
                                    try {
                                        await handleAddToCart(true); 
                                        navigate("/checkout");
                                    } catch (err) {
                                        console.error("Error al comprar ahora:", err.message);
                                    }
                                }}
                            >
                                COMPRAR AHORA
                            </button>
                        </div>
                    </div>

                    <section className="product-section">
                        <h3>Descripción</h3>
                        <p>{product.description}</p>
                    </section>
                </div>
            </div>

            {related.length > 0 && (
                <div className="related-outer-wrapper">
                    <div className="related-container">
                        <section className="related-section">
                            <div className="related-header">
                                <h2>Productos relacionados</h2>
                            </div>

                            {loadingRelated ? (
                                <div className="loader-mini"></div>
                            ) : (
                                <div className="related-carousel-wrapper">
                                    <button
                                        className="related-carousel-btn left"
                                        onClick={() => {
                                            document.querySelector('.related-carousel-track')
                                            .scrollBy({ left: -300, behavior: 'smooth' });
                                        }}
                                    >
                                        ‹
                                    </button>

                                    <div className="related-carousel-track">
                                        {related.map(item => (
                                            <div className="related-carousel-item" key={item.id}>
                                                <FeaturedProductCard product={item} />
                                            </div>
                                        ))}
                                    </div>

                                    <button
                                        className="related-carousel-btn right"
                                        onClick={() => {
                                            document.querySelector('.related-carousel-track')
                                            .scrollBy({ left: 300, behavior: 'smooth' });
                                        }}
                                    >
                                        ›
                                    </button>
                                </div>
                            )}
                        </section>
                    </div>
                </div>
            )}
        </div>
    );
}