import React from 'react';
import { Link } from 'react-router-dom';
import { FaLinkedin, FaEnvelope, FaMapMarkerAlt, FaPhone } from 'react-icons/fa';

export default function Footer() {
  return (
    <footer className="main-footer">
      <div className="footer-container">
        <div className="footer-column">
          <h3 className="footer-logo">Shop<span>Tudo</span></h3>
          <p className="footer-description">
            Tu librería de confianza. Todo lo que necesitas para tu estudio o oficina en un solo lugar.
          </p>
        </div>

        <div className="footer-column">
          <h4>Contacto</h4>
          <ul className="contact-info">
            <li>
              <FaEnvelope className="footer-icon" />
              <a href="mailto:leandroovejero@gmail.com">leandroovejero16197@gmail.com</a>
            </li>
            <li>
              <FaLinkedin className="footer-icon" />
              <a href="https://www.linkedin.com/in/leandroovejero/" target="_blank" rel="noopener noreferrer">
                LinkedIn
              </a>
            </li>
            <li>
              <FaMapMarkerAlt className="footer-icon" />
              <span>Buenos Aires, Argentina</span>
            </li>
          </ul>
        </div>
      </div>

      <div className="footer-bottom">
        <p>&copy; {new Date().getFullYear()} ShopTudo - Todos los derechos reservados.</p>
      </div>
    </footer>
  );
}