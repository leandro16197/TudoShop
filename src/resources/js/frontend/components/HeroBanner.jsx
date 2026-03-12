import React from 'react';
export default function HeroBanner() {
  return (
    <section className="hero-banner">

      <img
        src="/images/banner.png"
        alt="Promociones"
        className="hero-image"
      />

      <div className="hero-features">

        <div className="hero-feature">
          <span className="icon">🚚</span>
          <div>
            <strong>Envíos a todo el país</strong>
            <p>Recibí tu compra en 2 a 5 días hábiles.</p>
          </div>
        </div>

        <div className="hero-feature">
          <span className="icon">🛡</span>
          <div>
            <strong>Garantía oficial</strong>
            <p>12 meses contra defectos de fabricación.</p>
          </div>
        </div>

        <div className="hero-feature">
          <span className="icon">💳</span>
          <div>
            <strong>Pagos con Mercado Pago</strong>
            <p>Aceptamos tarjetas, débito y transferencias.</p>
          </div>
        </div>

      </div>

    </section>
  );
}
