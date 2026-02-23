import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import FeaturedProductCard from '../components/ProductCard';
import Footer from "../components/Footer";

export default function ProductDetail() {

    const { id } = useParams();

    const [product, setProduct] = useState(null);
    const [activeImage, setActiveImage] = useState(null);
    const [related, setRelated] = useState([]);
    const [loadingRelated, setLoadingRelated] = useState(false);

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
              <img
                src={activeImage || '/images/no-image.webp'}
                alt={product.name}
                onError={e => e.target.src = '/images/no-image.webp'}
              />
            </div>
          </div>

          <div className="product-info product-info--compact">
            <h1>{product.name}</h1>

            <p className="price">
              ${Number(product.price).toLocaleString()}
            </p>

            <p className="stock">
              Stock disponible: <strong>{product.stock}</strong>
            </p>

            <div className="actions">
              <button className="btn-outline btn-outline--compact">
                Agregar a lista
              </button>

              <button className="btn-primary btn-primary--compact">
                Comprar ahora
              </button>
            </div>
          </div>

        </div>

        <section className="product-section">
          <h3>Descripción</h3>
          <p>{product.description}</p>
        </section>

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

  </div>
);
}