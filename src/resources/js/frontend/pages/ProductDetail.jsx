import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useCart } from '../context/CartContext'; // Importamos el hook del carrito
import FeaturedProductCard from '../components/ProductCard';
import Footer from "../components/Footer";

export default function ProductDetail() {
    const { id } = useParams();
    const navigate = useNavigate();
    const { addToCart } = useCart(); // Extraemos la función global

    const [product, setProduct] = useState(null);
    const [activeImage, setActiveImage] = useState(null);
    const [related, setRelated] = useState([]);
    const [loadingRelated, setLoadingRelated] = useState(false);
    const [quantity, setQuantity] = useState(1);

    useEffect(() => {
        window.scrollTo(0, 0);
        setProduct(null);
        setRelated([]);
        setActiveImage(null);

        fetch(`/api/frontend/v1/productos/${id}`)
            .then(res => {
                if (!res.ok) throw new Error('Error cargando producto');
                return res.json();
            })
            .then(data => {
                setProduct(data);
                setActiveImage(data.images?.[0] ?? null);
                setQuantity(1);
                
                const categoriaId = data.categorias?.[0]?.id;
                if (categoriaId) {
                    setLoadingRelated(true);
                    fetch(`/api/frontend/v1/productos/categoria/${categoriaId}`)
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

    // Función de agregar al carrito corregida
    const handleAddToCart = async () => {
        try {
            // Usamos la función del contexto que ya maneja Token, Headers y Actualización del Navbar
            await addToCart(product, quantity);
            
            // Opcional: podrías mostrar un toast o alert de éxito
            console.log("Producto agregado y carrito actualizado");
        } catch (error) {
            // Si el error es por falta de auth, el contexto suele redirigir, 
            // pero lo manejamos por si acaso:
            if (error.message.includes("sesión")) {
                navigate("/login");
            }
            console.error("Error al agregar:", error.message);
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
                            <div className="main-image main-image--compact">
                                <img src={activeImage || '/images/no-image.webp'} alt={product.name} />
                            </div>
                        </div>

                        <div className="product-info product-info--compact">
                            <h1>{product.name}</h1>
                            <p className="price">${Number(product.price).toLocaleString()}</p>
                            
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

                                        <button
                                            type="button"
                                            className="btn-icono-carrito"
                                            onClick={handleAddToCart}
                                            title="Agregar al carrito"
                                        >
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
                            <button className="details-btn">
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
                                <div className="carousel-wrapper">
                                    <button
                                        className="carousel-btn left"
                                        onClick={() => {
                                            document.querySelector('.related-carousel').scrollBy({ left: -300, behavior: 'smooth' });
                                        }}
                                    >
                                        ‹
                                    </button>

                                    <div className="related-carousel">
                                        {related.map(item => (
                                            <div className="carousel-item" key={item.id}>
                                                <FeaturedProductCard product={item} />
                                            </div>
                                        ))}
                                    </div>

                                    <button
                                        className="carousel-btn right"
                                        onClick={() => {
                                            document.querySelector('.related-carousel').scrollBy({ left: 300, behavior: 'smooth' });
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
            <Footer />
        </div>
    );
}