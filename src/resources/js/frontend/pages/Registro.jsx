import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Register() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    nombre: "",
    apellido: "", // <-- Agregado
    email: "",
    password: "",
    password_confirmation: ""
  });

  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const response = await fetch("api/frontend/v1/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(form)
      });

      const data = await response.json();
      

      if (!response.ok) {
          if (data.errors) {
              const firstErrorKey = Object.keys(data.errors)[0];
              const errorMessage = data.errors[firstErrorKey][0];
              throw new Error(errorMessage);
          }
          throw new Error(data.message || "Error al registrarse");
      }

      localStorage.setItem("token", data.token);
      localStorage.setItem("cliente", JSON.stringify(data.cliente));

      window.dispatchEvent(new Event("authChange"));

      navigate("/");
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page-wrapper">
      <div className="register-card">
        <div className="auth-header">
          <h2>Crear Cuenta</h2>
          <p>Únete a la comunidad ShopTudo</p>
        </div>

        <form onSubmit={handleSubmit} className="auth-form">
          <div className="input-row" style={{ display: 'flex', gap: '10px' }}>
            <div className="input-group" style={{ flex: 1 }}>
              <input 
                type="text" 
                name="nombre" 
                placeholder="Nombre" 
                value={form.nombre} 
                onChange={handleChange} 
                required 
              />
            </div>
            <div className="input-group" style={{ flex: 1 }}>
              <input 
                type="text" 
                name="apellido" 
                placeholder="Apellido" 
                value={form.apellido} 
                onChange={handleChange} 
                required 
              />
            </div>
          </div>

          <div className="input-group">
            <input type="email" name="email" placeholder="Correo electrónico" value={form.email} onChange={handleChange} required />
          </div>

          <div className="input-group">
            <input type="password" name="password" placeholder="Contraseña" value={form.password} onChange={handleChange} required />
          </div>

          <div className="input-group">
            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" value={form.password_confirmation} onChange={handleChange} required />
          </div>

          {error && <div className="auth-error">{error}</div>}

          <button type="submit" className="btn-auth" disabled={loading}>
            {loading ? <span className="spinner-small"></span> : "Registrarse"}
          </button>

          <p className="auth-footer">
            ¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a>
          </p>
        </form>
      </div>
    </div>
  );
}